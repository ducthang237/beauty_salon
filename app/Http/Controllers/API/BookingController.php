<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\BookingService;
use Exception;
use Illuminate\Support\Facades\Log;

class BookingController extends BaseController
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }


    /**
     * Display all Bookings
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $bookings = $this->bookingService->getAll();
            return $this->sendResponse($bookings, 'Bookings retrieved successfully');
        } catch (Exception $e) {
            Log::error('Retrieve Bookings failed: ', $e->getMessage());
            return $this->sendError('Retrieve Bookings failed', $e->getMessage(), 500);
        }

    }
    /**
     * Create a new Booking
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $booking = $this->bookingService->create($input);
            return $this->sendResponse($booking, 'Booking created successfully');
        } catch (Exception $e) {
            Log::error('Create Booking failed: '. $e->getMessage());
            return $this->sendError('Create Booking failed', $e->getMessage(), 500);
        }

    }

    /**
     * Show Booking details by id
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $booking = $this->bookingService->getById($id);
            if (is_null($booking)) {
                return $this->sendError('Booking not found');
            }

            return $this->sendResponse($booking, 'Booking retrieved successfully');
        } catch (Exception $e) {
            Log::error('Booking retrieved failed: '. $e->getMessage());
            return $this->sendError('Booking retrieved failed', $e->getMessage(), 500);
        }

    }

    /**
     * Update Booking by id
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $input = $request->all();
            $booking = $this->bookingService->update($id, $input);
            return $this->sendResponse($booking, 'Booking updated successfully');
        } catch (Exception $e) {
            Log::error('Update Booking failed: '. $e->getMessage());
            return $this->sendError('Update Booking failed', $e->getMessage(), 500);
        }
    }

    /**
     * Remove Booking by id
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->bookingService->delete($id);
            return $this->sendResponse([], 'Booking deleted successfully');
        } catch (Exception $e) {
            Log::error('Delete Booking failed: '. $e->getMessage());
            return $this->sendError('Delete Booking failed', $e->getMessage(), 500);
        }
    }
}
