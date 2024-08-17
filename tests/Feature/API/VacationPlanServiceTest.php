<?php

namespace Tests\Feature\API;

use App\Exceptions\VacationPlanException;
use App\Models\VacationPlan;
use App\Repositories\VacationPlanRepository;
use App\Services\VacationPlanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class VacationPlanServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $vacationPlanService;
    protected $vacationPlanRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->vacationPlanRepository = $this->mock(VacationPlanRepository::class);
        $this->vacationPlanService = new VacationPlanService($this->vacationPlanRepository);
    }

    public function tearDown(): void
    {
        Mockery::close(); // Feche o Mockery após cada teste
        parent::tearDown();
    }

    public function test_get_all_vacation_plans_when_data_available()
    {
        $perPage = 10;
        $vacationPlans = VacationPlan::factory()->count(5)->make();
        $paginatedVacationPlans = $vacationPlans;

        // Configure o mock para retornar dados
        $this->vacationPlanRepository
            ->shouldReceive('getAllVacationPlans')
            ->with($perPage)
            ->andReturn($paginatedVacationPlans);

        $result = $this->vacationPlanService->getAllVacationPlans($perPage);

        $this->assertEquals($paginatedVacationPlans, $result);
    }

    public function test_get_all_vacation_plans_when_no_data_available()
    {
        $perPage = 10;

        // Configure o mock para retornar uma coleção vazia
        $this->vacationPlanRepository
            ->shouldReceive('getAllVacationPlans')
            ->with($perPage)
            ->andReturn(collect([]));

        $this->expectException(VacationPlanException::class);
        $this->vacationPlanService->getAllVacationPlans($perPage);
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

        $vacationPlan = new VacationPlan($data);

        // Configure o mock para retornar o plano de férias criado
        $this->vacationPlanRepository
            ->shouldReceive('createVacationPlan')
            ->with($data)
            ->andReturn($vacationPlan);

        $result = $this->vacationPlanService->createVacationPlan($data);

        $this->assertEquals($vacationPlan, $result);
    }

    public function test_create_vacation_plan_fails()
    {
        $data = [
            'title' => 'Trip to Hawaii',
            'description' => 'A fun trip to Hawaii',
            'date' => '2023-12-25',
            'location' => 'Hawaii',
            'participants' => ['John', 'Jane']
        ];

        // Configure o mock para retornar false
        $this->vacationPlanRepository
            ->shouldReceive('createVacationPlan')
            ->with($data)
            ->andReturn(false);

        $this->expectException(VacationPlanException::class);
        $this->vacationPlanService->createVacationPlan($data);
    }

    public function test_get_vacation_plan_by_id()
    {
        $id = 1;
        $vacationPlan = VacationPlan::factory()->create();

        // Configure o mock para retornar o plano de férias
        $this->vacationPlanRepository
            ->shouldReceive('getVacationPlanById')
            ->with($id)
            ->andReturn($vacationPlan);

        $result = $this->vacationPlanService->getVacationPlanById($id);

        $this->assertEquals($vacationPlan, $result);
    }

    public function test_get_vacation_plan_by_id_not_found()
    {
        $id = 1;

        // Configure o mock para retornar null
        $this->vacationPlanRepository
            ->shouldReceive('getVacationPlanById')
            ->with($id)
            ->andReturn(null);

        $this->expectException(VacationPlanException::class);
        $this->vacationPlanService->getVacationPlanById($id);
    }

    public function test_update_vacation_plan()
    {
        $id = 1;
        $vacationPlan = VacationPlan::factory()->create();
        $data = [
            'title' => 'Updated Trip',
            'description' => 'An updated description',
            'date' => '2023-12-31',
            'location' => 'Bahamas',
            'participants' => ['Alice', 'Bob']
        ];

        // Configure o mock para retornar o plano existente e sucesso na atualização
        $this->vacationPlanRepository
            ->shouldReceive('getVacationPlanById')
            ->with($id)
            ->andReturn($vacationPlan);

        $this->vacationPlanRepository
            ->shouldReceive('updateVacationPlan')
            ->with($vacationPlan, $data)
            ->andReturn(true);

        $result = $this->vacationPlanService->updateVacationPlan($id, $data);

        $this->assertEquals($vacationPlan, $result);
    }

    public function test_update_vacation_plan_not_found()
    {
        $id = 1;
        $data = [
            'title' => 'Updated Trip',
            'description' => 'An updated description',
            'date' => '2023-12-31',
            'location' => 'Bahamas',
            'participants' => ['Alice', 'Bob']
        ];

        // Configure o mock para retornar null ao buscar o plano
        $this->vacationPlanRepository
            ->shouldReceive('getVacationPlanById')
            ->with($id)
            ->andReturn(null);

        $this->expectException(VacationPlanException::class);
        $this->vacationPlanService->updateVacationPlan($id, $data);
    }

    public function test_update_vacation_plan_update_fails()
    {
        $id = 1;
        $vacationPlan = VacationPlan::factory()->create();
        $data = [
            'title' => 'Updated Trip',
            'description' => 'An updated description',
            'date' => '2023-12-31',
            'location' => 'Bahamas',
            'participants' => ['Alice', 'Bob']
        ];

        // Configure o mock para retornar o plano existente e falha na atualização
        $this->vacationPlanRepository
            ->shouldReceive('getVacationPlanById')
            ->with($id)
            ->andReturn($vacationPlan);

        $this->vacationPlanRepository
            ->shouldReceive('updateVacationPlan')
            ->with($vacationPlan, $data)
            ->andReturn(false);

        $this->expectException(VacationPlanException::class);
        $this->vacationPlanService->updateVacationPlan($id, $data);
    }

    public function test_destroy_vacation_plan()
    {
        $id = 1;
        $vacationPlan = VacationPlan::factory()->create();

        // Configure o mock para retornar o plano existente e sucesso na exclusão
        $this->vacationPlanRepository
            ->shouldReceive('getVacationPlanById')
            ->with($id)
            ->andReturn($vacationPlan);

        $this->vacationPlanRepository
            ->shouldReceive('destroyVacationPlan')
            ->with($vacationPlan)
            ->andReturn(true);

        $result = $this->vacationPlanService->destroyVacationPlan($id);

        $this->assertTrue($result);
    }

    public function test_destroy_vacation_plan_not_found()
    {
        $id = 1;

        // Configure o mock para retornar null ao buscar o plano
        $this->vacationPlanRepository
            ->shouldReceive('getVacationPlanById')
            ->with($id)
            ->andReturn(null);

        $this->expectException(VacationPlanException::class);
        $this->vacationPlanService->destroyVacationPlan($id);
    }

    public function test_destroy_vacation_plan_delete_fails()
    {
        $id = 1;
        $vacationPlan = VacationPlan::factory()->create();

        // Configure o mock para retornar o plano existente e falha na exclusão
        $this->vacationPlanRepository
            ->shouldReceive('getVacationPlanById')
            ->with($id)
            ->andReturn($vacationPlan);

        $this->vacationPlanRepository
            ->shouldReceive('destroyVacationPlan')
            ->with($vacationPlan)
            ->andReturn(false);

        $this->expectException(VacationPlanException::class);
        $this->vacationPlanService->destroyVacationPlan($id);
    }


}
