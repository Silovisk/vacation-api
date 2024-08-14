<?php

namespace Database\Factories;

use App\Models\VacationPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VacationPlan>
 */
class VacationPlanFactory extends Factory
{
    protected $model = VacationPlan::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'date' => $this->faker->date,
            'location' => $this->faker->city,
            'participants' => $this->faker->randomElements(
                ['Alice', 'Bob', 'Charlie', 'Dave', 'Eve'],
                $this->faker->numberBetween(1, 5)
            ),
        ];
    }
}
