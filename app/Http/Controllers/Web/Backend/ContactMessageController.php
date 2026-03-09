<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Helpers\Helper;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ContactUs::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return '<div class="action-wrapper">
                     <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View" onclick="window.location.href=\'' . route('admin_contact_us.show', $data->id) . '\'">
                        <i class="material-symbols-outlined fs-16 text-primary">visibility</i>
                        </button>
                                <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="deleteRecord(event,' . $data->id . ')">
                                <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                                </button>
                            </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view("backend.layouts.contact_us.index");
    }

    public function show(int $id)
    {
        try {
            $data = ContactUs::findOrFail($id);
            return view("backend.layouts.contact_us.show", compact('data'));
        } catch (Exception $e) {
            flash()->error('something went wrong');
            return redirect()->back();
        }
    }


    public function destroy(string $id)
    {
        $data = ContactUs::findOrFail($id);
        if (empty($data)) {
            return response()->json([
                "success" => false,
                "message" => "Item not found."
            ], 404);
        }
        if (!empty($data->file)) {
            Helper::fileDelete($data->file);
        }
        $data->delete();

        return response()->json([
            "success" => true,
            "message" => "Item deleted successfully."
        ]);
    }

}
