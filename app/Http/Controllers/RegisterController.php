<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Display register page.
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function show()
    {
        return view('auth.register');
    }

    /**
     * Handle account registration request
     * 
     * @param RegisterRequest $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(RegisterRequest $request)
    {
        // Wrap user creation in a try-catch block for error handling
        try {
            // Create the user using the validated request data
            $user = User::create($request->validated());

            // Log the user in
            Auth::login($user);

            // Redirect to the home page with a success message
            return redirect('/')->with('success', "Account successfully registered.");
        } catch (\Exception $e) {
            // Log the exception and return an error message
            \Log::error('Registration Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}
