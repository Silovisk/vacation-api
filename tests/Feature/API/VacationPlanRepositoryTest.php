<?php

namespace Tests\Feature\API;

use App\Models\VacationPlan;
use App\Repositories\VacationPlanRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class VacationPlanRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new VacationPlanRepository(new VacationPlan());
    }

    public function test_create_vacation_plan()
{
    $data = [
        'title' => 'Trip to Hawaii',
        'description' => 'A fun trip to Hawaii',
        'date' => '2023-12-25',
        'location' => 'Hawaii',
        'participants' => ['John', 'Jane']
    ];

    $vacationPlan = $this->repository->createVacationPlan($data);

    $expectedData = $data;
    $expectedData['date'] = '2023-12-25 00:00:00';
    $expectedData['participants'] = json_encode($data['participants']);

    $this->assertDatabaseHas('vacation_plans', $expectedData);
    $this->assertInstanceOf(VacationPlan::class, $vacationPlan);
}


    public function test_get_all_vacation_plans()
    {
        VacationPlan::factory()->count(5)->create();
        $vacationPlans = $this->repository->getAllVacationPlans(10);

        $this->assertCount(5, $vacationPlans->items());
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $vacationPlans);
    }

    public function test_get_vacation_plan_by_id()
    {
        $vacationPlan = VacationPlan::factory()->create();
        $foundVacationPlan = $this->repository->getVacationPlanById($vacationPlan->id);

        $this->assertInstanceOf(VacationPlan::class, $foundVacationPlan);
        $this->assertEquals($vacationPlan->id, $foundVacationPlan->id);
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

        $updated = $this->repository->updateVacationPlan($vacationPlan, $data);

        $expectedData = $data;
        $expectedData['date'] = '2023-12-31 00:00:00';
        $expectedData['participants'] = json_encode($data['participants']);

        $this->assertTrue($updated);
        $this->assertDatabaseHas('vacation_plans', $expectedData);
    }


    public function test_destroy_vacation_plan()
    {
        $vacationPlan = VacationPlan::factory()->create();
        $deleted = $this->repository->destroyVacationPlan($vacationPlan);

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('vacation_plans', ['id' => $vacationPlan->id]);
    }
}
