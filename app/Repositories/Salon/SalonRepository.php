<?php
namespace App\Repositories\Salon;

use App\Models\Salon;
use App\Repositories\BaseRepository;
use App\Repositories\Salon\SalonRepositoryInterface;

class SalonRepository extends BaseRepository implements SalonRepositoryInterface
{
    public function getModel()
    {
        return Salon::class;
    }

    public function getTechnicalBySalon($salonId)
    {
        $salon = $this->find($salonId);
        if ($salon) {
            return $salon->technicals->toArray();
        }
        return [];
    }
}
