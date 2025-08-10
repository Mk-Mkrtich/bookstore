<?php

namespace App\Http\Controllers\API;

use App\Service\IReservationsAdmin;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class ReservationAdminController
{
    public function __construct(private IReservationsAdmin $service)
    {
    }

    public function index(): JsonResponse
    {
        $pendingReservations = $this->service->getPendingReservations();

        return response()->json([
            'success' => true,
            'reservations' => $pendingReservations,
        ], 200);
    }

    public function confirm(int $resId): JsonResponse
    {
        try {
            $this->service->confirmReservation($resId);

            return response()->json([
                'success' => true,
            ], 200);
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

    public function cancel(int $resId): JsonResponse
    {
        try {
            $this->service->cancelReservation($resId);

            return response()->json([
                'success' => true,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Reservation not found.',
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unexpected error occurred.',
            ], 500);
        }
    }
}
