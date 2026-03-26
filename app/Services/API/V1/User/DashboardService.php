<?php

namespace App\Services\API\V1\User;

use App\Models\Loan;
use App\Models\LoanRepaymentSchedule;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardService
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function dashboardData()
    {
        try {
            $userId = $this->user->id;
            $data = [];

            return $data;

        } catch (\Exception $e) {
            Log::error('DashboardService::dashboardData - '.$e->getMessage());
            throw $e;
        }
    }

}
