<?php

namespace App\Http\Controllers;

use App\Models\Psychologist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PsychologistController extends Controller
{
    /**
     * Display a paginated list of psychologists.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Retrieve psychologists with pagination (10 per page)
        $psychologists = Psychologist::paginate(10);

        return response()->json($psychologists);
    }

    /**
     * Store a newly created psychologist in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:psychologists,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'The email has already been taken.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
        ]);

        // Hash the password
        $validated['password'] = bcrypt($validated['password']);

        // Create the psychologist record
        $psychologist = Psychologist::create($validated);

        return response()->json($psychologist, 201);
    }

    /**
     * Authenticate a psychologist and issue a token.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validate the incoming request
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Find the psychologist by email
        $psychologist = Psychologist::where('email', $credentials['email'])->first();

        // Check if the psychologist exists and if the password matches
        if (!$psychologist || !Hash::check($credentials['password'], $psychologist->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Generate a personal access token
        $token = $psychologist->createToken('auth_token')->plainTextToken;

        // Return a successful login response
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'psychologist' => $psychologist,
        ]);
    }

    /**
     * Revoke the current access token of the authenticated psychologist.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Revoke the current access token
        $request->user()->currentAccessToken()->delete();

        // Return a successful logout response
        return response()->json(['message' => 'Logged out successfully']);
    }
}
