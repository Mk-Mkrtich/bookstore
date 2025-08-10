<?php

namespace App\Service;


use App\Events\ReservationCreated;
use App\Models\Book;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

final class ReservationService implements IReservationService, IReservationsExpired, IReservationsAdmin
{
    private int $pendingTTLMinutes = 30;

    /**
     * @throws \Exception
     */
    public function createReservation(int $userId, int $bookId, int $quantity): Reservation
    {
        return DB::transaction(function () use ($userId, $bookId, $quantity) {
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

            DB::afterCommit(function () use ($reservation) {
                event(new ReservationCreated($reservation));
            });

            return $reservation;
        }, 5);
    }

    /**
     * @return int
     */
    public function expirePendingReservations(): int
    {
        $now = Carbon::now();

        $expiredReservations = Reservation::where('status', Reservation::STATUS_PENDING)
            ->where('expires_at', '<=', $now)
            ->get(['id', 'book_id', 'quantity']);

        if ($expiredReservations->isEmpty()) {
            return 0;
        }

        DB::transaction(function () use ($expiredReservations) {
            Reservation::whereIn('id', $expiredReservations->pluck('id'))
                ->update([
                    'status' => Reservation::STATUS_CANCELLED
                ]);

            $bookQuantities = $expiredReservations
                ->groupBy('book_id')
                ->map(fn($group) => $group->sum('quantity'));

            foreach ($bookQuantities as $bookId => $quantityToRestore) {
                Book::where('id', $bookId)->increment('stock', $quantityToRestore);
            }
        });

        return $expiredReservations->count();
    }

    /**
     * @param int $reservationId
     * @return Reservation
     */
    public function confirmReservation(int $reservationId): Reservation
    {
        return DB::transaction(function () use ($reservationId) {
            $reservation = Reservation::where('id', $reservationId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($reservation->status !== Reservation::STATUS_PENDING) {
                throw new \RuntimeException('Only pending reservations can be confirmed.');
            }

            $reservation->status = Reservation::STATUS_CONFIRMED;
            $reservation->expires_at = null;
            $reservation->save();

            return $reservation;
        });
    }

    /**
     * @param int $reservationId
     * @return Reservation
     */
    public function cancelReservation(int $reservationId): Reservation
    {
        return DB::transaction(function () use ($reservationId) {
            $reservation = Reservation::where('id', $reservationId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($reservation->status === Reservation::STATUS_CANCELLED) {
                return $reservation;
            }

            $wasPending = $reservation->status === Reservation::STATUS_PENDING;

            $reservation->status = Reservation::STATUS_CANCELLED;
            $reservation->expires_at = null;
            $reservation->save();

            if ($wasPending) {
                $book = Book::where('id', $reservation->book_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $book->stock += $reservation->quantity;
                $book->save();
            }

            return $reservation;
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPendingReservations(): \Illuminate\Database\Eloquent\Collection
    {
        return Reservation::with('book', 'user')
            ->where('status', Reservation::STATUS_PENDING)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
