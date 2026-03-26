<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\Web\Backend\ContractorRegisterService;
use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisterContractorController extends Controller
{
    protected ContractorRegisterService $contractorRegisterService;

    public function __construct(ContractorRegisterService $contractorRegisterService)
    {
        $this->contractorRegisterService = $contractorRegisterService;
    }
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $categories = Category::where('status', 'active')->get();
        $morocco_city = json_decode(file_get_contents(public_path('backend/admin/assets/morocco_city_list.json')), true);

        // dd($morocco_city);
        return view('auth.contractor_register', compact('morocco_city', 'categories'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        // dd($request->all());
        $validateData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'instagram_social_link' => ['nullable', 'url'],
            'category_id' => 'required|exists:categories,id',
            'address' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'avatar' => 'file|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
        // dd($validateData);
        try {
            $this->contractorRegisterService->store($validateData);
            Log::info('RegisterContractorController ::store- complete');
            return redirect(route('contractor.dashboard'));
        } catch (Exception $e) {
            Log::error('RegisterContractorController ::store-' . $e->getMessage());
            flash()->error($e->getMessage());
            return redirect()->route('contractor.dashboard');
        }

    }

}
