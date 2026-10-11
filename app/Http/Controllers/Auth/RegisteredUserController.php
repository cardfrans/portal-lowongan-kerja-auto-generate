<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'business_area' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'company_website' => ['required', 'string', 'max:255'],
            'company_email' => ['required', 'email', 'max:255'],
            'contact_name' => ['required', 'string', 'max:100'],
            'contact_position' => ['required', 'string', 'max:100'],
            'contact_phone' => ['required', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:10240'],
        ]);

        $logoPath = $request->hasFile('logo')
            ? $request->file('logo')->store('company-logos', 'public')
            : null;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'company',
            'logo_path' => $logoPath,
            'business_area' => strip_tags(trim($request->business_area)),
            'province' => strip_tags(trim($request->province)),
            'company_website' => strip_tags(trim($request->company_website)),
            'company_email' => strip_tags(trim($request->company_email)),
            'contact_name' => strip_tags(trim($request->contact_name)),
            'contact_position' => strip_tags(trim($request->contact_position)),
            'contact_phone' => strip_tags(trim($request->contact_phone)),
        ]);

        $role = Role::firstOrCreate(['name' => 'company']);
        $user->assignRole($role);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
