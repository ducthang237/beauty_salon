<?php

namespace App\Services;

use App\Repositories\Booking\BookingRepositoryInterface;
use App\Repositories\Salon\SalonRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use PDOException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;

class BookingService
{
    protected $bookingRepo;
    protected $salonRepo;

    public function __construct(BookingRepositoryInterface $bookingRepo, SalonRepositoryInterface $salonRepo)
    {
        $this->bookingRepo = $bookingRepo;
        $this->salonRepo = $salonRepo;
    }

    public function getAll()
    {
        return $this->bookingRepo->getAll()->toArray();
    }

    public function getById($id)
    {
        return $this->bookingRepo->find($id);
    }

    public function create($data)
    {
        $validator = Validator::make($data, [
            'booking_date' => [
                'required',
                'date'
            ],
            'status' => 'in:1,2,3',  // 1: waiting, 2: done, 3: canceled
            'salon_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $salons = $this->salonRepo->getAll()->toArray();
                    $salonIds = Arr::pluck($salons, 'id');
                    if (!in_array($value, $salonIds)) {
                        $fail('The '.$attribute.' is invalid.');
                    }
                },
            ],
            'technical_id' => [
                'required',
                function ($attribute, $value, $fail) use ($data) {
                    $technicals = $this->salonRepo->getTechnicalBySalon($data['salon_id']);
                    $technicalIds = Arr::pluck($technicals, 'id');
                    if (!in_array($value, $technicalIds)) {
                        $fail('The '.$attribute.' is invalid.');
                    }
                },
            ],
            'service_id' => [
                'required',
                function ($attribute, $value, $fail) use ($data) {

                },
            ]
        ]);

        if($validator->fails()){
            throw new InvalidArgumentException($validator->errors());
        }

        $data['customer_id'] = Auth::user()->id;

        DB::beginTransaction();
        try {
            $booking = $this->bookingRepo->create($data);
            $booking->services()->attach($data['service_id']);
            DB::commit();
            return $booking;
        } catch (PDOException $e) {
            DB::rollback();
        }
    }

    public function update($id, $data)
    {
        $booking = $this->bookingRepo->find($id);
        if (is_null($booking)) {
            throw new NotFoundHttpException('Booking not found');
        }

        $validator = Validator::make($data, [
            'booking_date' => [
                'required',
                'date'
            ],
            'status' => 'in:1,2,3',  // 1: waiting, 2: done, 3: canceled
            'salon_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $salons = $this->salonRepo->getAll()->toArray();
                    $salonIds = Arr::pluck($salons, 'id');
                    if (!in_array($value, $salonIds)) {
                        $fail('The '.$attribute.' is invalid.');
                    }
                },
            ],
            'technical_id' => [
                'required',
                function ($attribute, $value, $fail) use ($data) {
                    $technicals = $this->salonRepo->getTechnicalBySalon($data['salon_id']);
                    $technicalIds = Arr::pluck($technicals, 'id');
                    if (!in_array($value, $technicalIds)) {
                        $fail('The '.$attribute.' is invalid.');
                    }
                },
            ]
        ]);

        if($validator->fails()){
            throw new InvalidArgumentException($validator->errors());
        }

        DB::beginTransaction();
        try {
            $newBooking = $this->bookingRepo->update($id, $data);
            $newBooking->services()->attach($data['service_id']);
            DB::commit();
            return $newBooking;
        } catch (PDOException $e) {
            DB::rollback();
        }
    }

    public function delete($id)
    {
        $Booking = $this->bookingRepo->find($id);
        if (is_null($Booking)) {
            throw new NotFoundHttpException('Booking not found');
        }

        return $this->bookingRepo->delete($id);
    }
}
