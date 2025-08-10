<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateReservationRequest;
use App\Service\IReservationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class ReservationController extends Controller
{
    public function __construct(
        private IReservationService $service
    )
    {}

    public function store(CreateReservationRequest $request): JsonResponse
    {
        $user = $request->user();
        try {
            $reservation = $this->service->createReservation(
                $user->id,
                $request->input('book_id'),
                $request->input('quantity')
            );

            return response()->json([
                'success' => true,
                'reservation' => $reservation,
            ], 201);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found.',
            ], 404);

        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unexpected error occurred.',
            ], 500);
        }
    }

    public function myReservations(): JsonResponse
    {
        $reservations = auth()->user()->reservations()->with('book')->latest()->get();

        return response()->json($reservations);
    }
}
