<?php

namespace App\Services;

use App\Repositories\Salon\SalonRepositoryInterface;
use App\Models\City;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SalonService
{
    protected $salonRepo;

    public function __construct(SalonRepositoryInterface $salonRepo)
    {
        $this->salonRepo = $salonRepo;
    }

    public function getAll()
    {
        return $this->salonRepo->getAll()->toArray();
    }

    public function getById($id)
    {
        return $this->salonRepo->find($id);
    }

    public function create($data)
    {
        $cities = City::all()->toArray();
        $cityIds = Arr::pluck($cities, 'id');

        $validator = Validator::make($data, [
            'salon_name' => [
                'required',
                'unique:salons,salon_name'
            ],
            'address' => 'required',
            'phone' => [
                'required',
                'regex:/^\d{3}-\d{3}-\d{4}$/'
            ],
            'city_id' => [
                'required',
                function ($attribute, $value, $fail) use ($cityIds) {
                    if (!in_array($value, $cityIds)) {
                        $fail('The '.$attribute.' is invalid.');
                    }
                },
            ]
        ]);

        if($validator->fails()){
            throw new InvalidArgumentException($validator->errors());
        }

        return $this->salonRepo->create($data);
    }

    public function update($id, $data)
    {
        $salon = $this->salonRepo->find($id);
        if (is_null($salon)) {
            throw new NotFoundHttpException('Salon not found');
        }

        $cities = City::all()->toArray();
        $cityIds = Arr::pluck($cities, 'id');

        $validator = Validator::make($data, [
            'salon_name' => [
                'required',
                Rule::unique('salons')->ignore($salon->id),
            ],
            'address' => 'required',
            'phone' => [
                'required',
                'regex:/^\d{3}-\d{3}-\d{4}$/'
            ],
            'city_id' => [
                'required',
                function ($attribute, $value, $fail) use ($cityIds) {
                    if (!in_array($value, $cityIds)) {
                        $fail('The '.$attribute.' is invalid.');
                    }
                },
            ]
        ]);

        if($validator->fails()){
            throw new InvalidArgumentException($validator->errors());
        }

        return $this->salonRepo->update($id, $data);
    }

    public function delete($id)
    {
        $salon = $this->salonRepo->find($id);
        if (is_null($salon)) {
            throw new NotFoundHttpException('Salon not found');
        }

        return $this->salonRepo->delete($id);
    }
}
