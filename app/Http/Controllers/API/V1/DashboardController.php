<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Services\API\V1\User\DashboardService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display a listing of the resource.
     */
    public function dashboardData()
    {
        try {
            $dashboardData = $this->dashboardService->dashboardData();

            return Helper::jsonResponse(true, 'Dashboard data retrieved successfully.', 200, $dashboardData);
        } catch (Exception $e) {
            Log::error('Error retrieving dashboard data: '.$e->getMessage());

            return Helper::jsonResponse(false, 'Failed to retrieve dashboard data: '.$e->getMessage(), 500);
        }
    }

    public function HistoryPageData(Request $request)
    {
        try {
            $dashboardData = $this->dashboardService->HistoryPageData($request);

            return Helper::jsonResponse(true, 'Data retrieved successfully.', 200, $dashboardData,true);
        } catch (Exception $e) {
            Log::error('Error retrieving dashboard data: '.$e->getMessage());

            return Helper::jsonResponse(false, 'Failed to retrieve dashboard data: '.$e->getMessage(), 500);
        }
    }

    public function LoanManagementToolPageData(Request $request)
    {
        try {
            $dashboardData = $this->dashboardService->LoanManagementToolPageData($request);

            return Helper::jsonResponse(true, 'Data retrieved successfully.', 200, $dashboardData);
        } catch (Exception $e) {
            Log::error('Error retrieving dashboard data: '.$e->getMessage());

            return Helper::jsonResponse(false, 'Failed to retrieve dashboard data: '.$e->getMessage(), 500);
        }
    }
}
