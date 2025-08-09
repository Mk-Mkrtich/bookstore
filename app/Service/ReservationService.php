<?php

namespace App\Service;


use App\Models\Book;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

final class ReservationService implements IReservationService
{
    private int $pendingTTLMinutes = 30;

    /**
     *
     * @throws \Exception
     */
    public function createReservation(int $userId, int $bookId, int $quantity): Reservation
    {
        return DB::transaction(function () use ($userId, $bookId, $quantity) {
            // for ensure the isolation level is read commited
            DB::statement('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');

            /** @var Book $book */
            $book = Book::where('id', $bookId)->lockForUpdate()->firstOrFail();

            $existingPending = Reservation::where('user_id', $userId)
                ->where('book_id', $bookId)
                ->where('status', Reservation::STATUS_PENDING)
                ->lockForUpdate()
                ->first();

            if ($existingPending) {
                throw new \RuntimeException('You already have a pending reservation for this book.');
            }

            if ($book->stock < $quantity) {
                throw new \RuntimeException('Not enough stock available.');
            }

            $book->stock -= $quantity;
            $book->save();

            $expiresAt = Carbon::now()->addMinutes($this->pendingTTLMinutes);
            $reservation = Reservation::create([
                'user_id' => $userId,
                'book_id' => $bookId,
                'quantity' => $quantity,
                'status' => Reservation::STATUS_PENDING,
                'expires_at' => $expiresAt,
            ]);

            return $reservation;
        }, 5);
    }
}
