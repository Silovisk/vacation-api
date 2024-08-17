<?php

namespace Tests\Feature\API;

use App\Models\VacationPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VacationPlanTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_vacation_plan()
    {
        $data = [
            'title' => 'Trip to Hawaii',
            'description' => 'A fun trip to Hawaii',
            'date' => '2023-12-25',
            'location' => 'Hawaii',
            'participants' => ['John', 'Jane']
        ];

        $vacationPlan = VacationPlan::create($data);

        $expectedData = $data;
        $expectedData['date'] = '2023-12-25 00:00:00';
        $expectedData['participants'] = json_encode($data['participants']);

        $this->assertDatabaseHas('vacation_plans', $expectedData);
    }

    public function test_update_vacation_plan()
    {
        $vacationPlan = VacationPlan::factory()->create();

        $data = [
            'title' => 'Updated Trip',
            'description' => 'An updated description',
            'date' => '2023-12-31',
            'location' => 'Bahamas',
            'participants' => ['Alice', 'Bob']
        ];

        $vacationPlan->update($data);

        $expectedData = $data;
        $expectedData['date'] = '2023-12-31 00:00:00';
        $expectedData['participants'] = json_encode($data['participants']);

        $this->assertDatabaseHas('vacation_plans', $expectedData);
    }

    public function test_delete_vacation_plan()
    {
        $vacationPlan = VacationPlan::factory()->create();

        $vacationPlan->delete();

        $this->assertDatabaseMissing('vacation_plans', ['id' => $vacationPlan->id]);
    }
}

