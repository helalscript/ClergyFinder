<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = Newsletter::query()->latest();

                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('ip', function ($row) {
                        return $row->ip ?? '-';
                    })
                    ->addColumn('created_at', function ($row) {
                        return $row->created_at?->format('d M, Y h:i A') ?? '-';
                    })
                    ->rawColumns(['ip'])
                    ->make(true);
            }

            return view('backend.layouts.newsletters.index');
        } catch (Exception $e) {
            Log::error('NewsletterController::index ' . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to fetch newsletters', 500);
        }
    }
}

