<?php
namespace App\Repositories\Booking;

use App\Models\Booking;
use App\Repositories\BaseRepository;
use App\Repositories\Booking\BookingRepositoryInterface;

class BookingRepository extends BaseRepository implements BookingRepositoryInterface
{
    public function getModel()
    {
        return Booking::class;
    }
}
