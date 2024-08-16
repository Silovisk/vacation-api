<?php

namespace Database\Seeders;

use App\Models\VacationPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VacationPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        VacationPlan::factory()->count(1500)->create();
    }
}
