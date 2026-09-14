<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $services = [
            ['name' => 'Cuci Kiloan',     'price' => 7000,  'unit' => 'kg'],
            ['name' => 'Cuci Karpet',     'price' => 15000, 'unit' => 'm2'],
            ['name' => 'Setrika Express', 'price' => 10000, 'unit' => 'kg'],
            ['name' => 'Cuci Sepatu',     'price' => 30000, 'unit' => 'pcs'],
            ['name' => 'Cuci Bed Cover',  'price' => 25000, 'unit' => 'pcs'],
        ];

        $service = $this->faker->randomElement($services);

        return [
            'name'         => $service['name'],
            'price_per_kg' => $service['price'],
            'unit'         => $service['unit'],
        ];
    }
}