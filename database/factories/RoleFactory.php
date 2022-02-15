<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'role_name' => $this->faker->jobTitle()
        ];
    }

    public function customer()
    {
        return $this->state(function (array $attributes) {
            return [
                'role_name' => 'customer',
            ];
        });
    }

    public function technical()
    {
        return $this->state(function (array $attributes) {
            return [
                'role_name' => 'technical',
            ];
        });
    }

    public function manager()
    {
        return $this->state(function (array $attributes) {
            return [
                'role_name' => 'manager',
            ];
        });
    }
}
