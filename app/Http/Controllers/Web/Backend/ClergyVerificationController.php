<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Mail\ClergyRegistrationRejected;
use App\Models\Clergy;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class ClergyVerificationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Clergy::query()
                ->where('registration_status', 'pending_admin')
                ->with('user:id,name,email,phone,avatar,status')
                ->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('avatar', function (Clergy $row) {
                    $path = $row->user?->getRawOriginal('avatar');
                    $src = $path ? asset($path) : asset('backend/admin/assets/images/avatar_defult.png');

                    return '<img src="'.$src.'" class="wh-40 rounded-3" alt="avatar">';
                })
                ->addColumn('name', fn (Clergy $row) => e($row->user?->name ?? '-'))
                ->addColumn('email', fn (Clergy $row) => e($row->user?->email ?? '-'))
                ->editColumn('religion', fn (Clergy $row) => e($row->religion ?? '-'))
                ->editColumn('denomination', fn (Clergy $row) => e($row->denomination ?? '-'))
                ->editColumn('created_at', fn (Clergy $row) => $row->created_at?->format('M j, Y g:i A') ?? '-')
                ->addColumn('action', function (Clergy $row) {
                    return '<div class="action-wrapper">
                        <a type="button" href="'.route('admin.clergy_verification.show', $row).'" class="ps-0 border-0 bg-transparent lh-1" title="Review">
                            <i class="material-symbols-outlined fs-16 text-primary">visibility</i>
                        </a>
                    </div>';
                })
                ->filterColumn('name', function ($query, $keyword) {
                    $query->whereHas('user', fn ($q) => $q->where('name', 'like', '%'.$keyword.'%'));
                })
                ->filterColumn('email', function ($query, $keyword) {
                    $query->whereHas('user', fn ($q) => $q->where('email', 'like', '%'.$keyword.'%'));
                })
                ->rawColumns(['avatar', 'action'])
                ->make(true);
        }

        return view('backend.layouts.clergy_verification.index');
    }

    public function show(Clergy $clergy): View|RedirectResponse
    {
        if ($clergy->registration_status !== 'pending_admin') {
            return redirect()
                ->route('admin.clergy_verification.index')
                ->with('warning', 'This clergy registration is not pending review.');
        }

        $clergy->load('user');

        return view('backend.layouts.clergy_verification.show', compact('clergy'));
    }

    public function approve(Clergy $clergy): RedirectResponse
    {
        if ($clergy->registration_status !== 'pending_admin') {
            return redirect()->back()->with('error', 'This registration is not pending.');
        }

        try {
            $clergy->update([
                'registration_status' => 'approved',
                'registration_rejection_reason' => null,
                'registration_reviewed_at' => now(),
                'is_verified' => true,
            ]);

            return redirect()
                ->route('admin.clergy_verification.index')
                ->with('success', 'Clergy registration approved successfully.');
        } catch (Exception $e) {
            Log::error('ClergyVerificationController::approve '.$e->getMessage());

            return redirect()->back()->with('error', 'Could not approve this registration.');
        }
    }

    public function reject(Request $request, Clergy $clergy): RedirectResponse
    {
        if ($clergy->registration_status !== 'pending_admin') {
            return redirect()->back()->with('error', 'This registration is not pending.');
        }

        $data = $request->validate([
            'reason' => 'required|string|max:2000',
        ]);

        try {
            $clergy->load('user');
            if (! $clergy->user) {
                return redirect()->back()->with('error', 'User record missing.');
            }

            $clergy->update([
                'registration_status' => 'rejected',
                'registration_rejection_reason' => $data['reason'],
                'registration_reviewed_at' => now(),
            ]);

            Mail::to($clergy->user->email)->queue(new ClergyRegistrationRejected($clergy->user, $data['reason']));

            return redirect()
                ->route('admin.clergy_verification.index')
                ->with('success', 'Clergy registration rejected and email sent.');
        } catch (Exception $e) {
            Log::error('ClergyVerificationController::reject '.$e->getMessage());

            return redirect()->back()->with('error', 'Could not reject this registration.');
        }
    }
}
