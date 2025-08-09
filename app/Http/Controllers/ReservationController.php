<?php

namespace App\Http\Controllers;


use App\Http\Requests\CreateReservationRequest;
use App\Service\IReservationService;
use Illuminate\Http\JsonResponse;

class ReservationController extends Controller
{
    public function __construct(
        private IReservationService $service
    )
    {
        $this->middleware('auth:sanctum');
    }

    public function store(CreateReservationRequest $request): JsonResponse
    {
        $user = $request->user();

        $reservation = $this->service->createReservation(
            $user->id,
            $request->input('book_id'),
            $request->input('quantity')
        );

        return response()->json([
            'success' => true,
            'reservation' => $reservation,
        ], 201);
    }

    public function myReservations(): JsonResponse
    {
        $reservations = auth()->user()->reservations()->with('book')->latest()->get();

        return response()->json($reservations);
    }
}
