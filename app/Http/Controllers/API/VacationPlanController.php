<?php

namespace App\Http\Controllers\API;

use App\Exceptions\VacationPlanException;
use App\Http\Requests\IndexVacationPlanRequest;
use App\Http\Resources\VacationPlanResource;
use App\Models\VacationPlan;
use App\Http\Requests\StoreVacationPlanRequest;
use App\Http\Requests\UpdateVacationPlanRequest;
use App\Services\VacationPlanService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VacationPlanController extends BaseController
{
    public function __construct
    (
        private VacationPlanService $vacationPlanService,
    )
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index(IndexVacationPlanRequest $request)
    {
        try {
            $perPage = $request->get('per_page', 15);
            $vacationPlans = $this->vacationPlanService->getAllVacationPlans($perPage);

            return VacationPlanResource::collection($vacationPlans);
        } catch (VacationPlanException $e) {
            Log::error($e->getMessage(), ['vacation-plan-exception' => $e]);

            return $this->sendError(
                $e->getMessage(),
                [],
                $e->getStatusCode()
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return $this->sendError(
                'An unexpected error occurred.',
                [],
                500
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVacationPlanRequest $request)
    {
        try {
            $vacationPlan = $this->vacationPlanService->createVacationPlan($request->validated());
            return (new VacationPlanResource($vacationPlan))->response()->setStatusCode(201);
        } catch (VacationPlanException $e) {
            return $e->render($request);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        Log::info('$id: ' . print_r($id, true));
        Log::info('in ' . __FILE__ . ' on line ' . __LINE__);

        $vacationPlan = $this->vacationPlanService->getVacationPlanById($id);
        return new VacationPlanResource($vacationPlan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVacationPlanRequest $request, $id)
    {
        $vacationPlan = $this->vacationPlanService->updateVacationPlan($id, $request->validated());
        return new VacationPlanResource($vacationPlan);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $vacationPlan = $this->vacationPlanService->destroyVacationPlan($id);
        return $vacationPlan;
    }
}
