<?php
namespace App\Repositories\Salon;

use App\Repositories\RepositoryInterface;

interface SalonRepositoryInterface extends RepositoryInterface
{
    /**
     * Get technicals by salon id
     */
    public function getTechnicalBySalon($salonId);
}

