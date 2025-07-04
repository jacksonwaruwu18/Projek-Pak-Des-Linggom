<?php

namespace Database\Factories;

use App\Models\Gudang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gudang>
 */
class GudangFactory extends Factory
{
    protected $model = Gudang::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->company(),
            'status' => $this->faker->boolean(), // true (aktif) atau false (tidak aktif)
        ];
    }
}
