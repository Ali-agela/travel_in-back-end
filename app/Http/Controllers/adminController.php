<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\admin;
use App\Models\Resort;
use Hash;

class adminController extends Controller
{
    public function index()
    {
        $admins = admin::with('images')->get();

        return response()->json(['admins' => $admins]);


    }
    public function logIn(Request $request)
    {

        $credentials = request(['email', 'password']);
        if (!$token = auth('admin')->attempt($credentials)) {
            return response()->json(['status' => 'error'], 401);
        }
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    public function logout(Request $request)
    {
        auth()->logout(true);

        return response()->json(['data' => 'Successfully logged out']);
    }
    public function register(Request $request)
    {
        $input = $request->validate([
            'email' => ['required', 'string', 'unique:admins,email', 'email'],
            'name' => ['string', 'required'],
            'phone' => ['required', 'string', 'unique:admins,phone', 'min:9', 'max:10'],
            'password' => ['required', 'string'],
            'description' => ['required', 'string'],
            'phone_numbers' => ['required', 'string'],
            'for_families' => ['required', 'boolean'],
            'location' => ['required', 'string'],
        ]);
        admin::create([
            'email' => $input['email'],
            'name' => $input['name'],
            'phone' => $input['phone'],
            'password' => Hash::make($input['password']),
            'description' => $input['description'],
            'phone_numbers' => $input['phone_numbers'],
            'for_families' => $input['for_families'],
            'location' => $input['location'],
            'rating' => 0
        ]);

        return response()->json([
            'data' => 'admin registered successfully'
        ]);
    }

    public function user()
    {
        $user = auth('admins')->user();
        return response()->json(["the logged in admin data" => $user]);
    }

    public function showResortWithReservations($id)
    {
        $user = auth('admin')->user();
        
        $resort = Resort::where('id', $id)->where('owner_id', $user->id)->with('reservations')->first();
        if (!$resort) {
            return response()->json(['status' => 'error', 'message' => 'No resort found for this user'], 404);
        }

        return response()->json(['resort' => $resort]);
    }

}
