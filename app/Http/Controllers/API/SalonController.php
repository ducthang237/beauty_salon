<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\SalonService;
use Exception;
use Illuminate\Support\Facades\Log;

class SalonController extends BaseController
{
    protected $salonService;

    public function __construct(SalonService $salonService)
    {
        $this->salonService = $salonService;
    }


    /**
     * Display all salons
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $salons = $this->salonService->getAll();
            return $this->sendResponse($salons, 'Salons retrieved successfully');
        } catch (Exception $e) {
            Log::error('Retrieve salons failed: ', $e->getMessage());
            return $this->sendError('Retrieve salons failed', $e->getMessage(), 500);
        }

    }
    /**
     * Create a new salon
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $salon = $this->salonService->create($input);
            return $this->sendResponse($salon, 'Salon created successfully');
        } catch (Exception $e) {
            Log::error('Create salon failed: '. $e->getMessage());
            return $this->sendError('Create salon failed', $e->getMessage(), 500);
        }

    }

    /**
     * Show salon details by id
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $salon = $this->salonService->getById($id);
            if (is_null($salon)) {
                return $this->sendError('Salon not found');
            }

            return $this->sendResponse($salon, 'Salon retrieved successfully');
        } catch (Exception $e) {
            Log::error('Salon retrieved failed: '. $e->getMessage());
            return $this->sendError('Salon retrieved failed', $e->getMessage(), 500);
        }

    }

    /**
     * Update salon by id
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $input = $request->all();
            $salon = $this->salonService->update($id, $input);
            return $this->sendResponse($salon, 'Salon updated successfully');
        } catch (Exception $e) {
            Log::error('Update salon failed: '. $e->getMessage());
            return $this->sendError('Update salon failed', $e->getMessage(), 500);
        }
    }

    /**
     * Remove salon by id
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->salonService->delete($id);
            return $this->sendResponse([], 'Salon deleted successfully');
        } catch (Exception $e) {
            Log::error('Delete salon failed: '. $e->getMessage());
            return $this->sendError('Delete salon failed', $e->getMessage(), 500);
        }
    }
}
