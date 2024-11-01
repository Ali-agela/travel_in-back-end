<?php

namespace App\Http\Controllers;
use App\Models\Resevations;
use App\Models\spasification;
use App\Models\User;
use Illuminate\Http\Request;
use Hash;
use App\Models\Reservation;

class UserController extends Controller
{
    public function logIn(Request $request)
    {

        $credentials = request(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            $credentials = request(key: ['phone', 'password']);
            if (!$token = auth()->attempt($credentials)) {
                return response()->json(['status' => 'error'], 401);
            }
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
            'email' => ['required', 'string', 'unique:users,email', 'email'],
            'name' => ['string', 'required'],
            'phone' => ['required', 'string', 'unique:users,phone', 'min:9', 'max:10'],
            'password' => ['required', 'string'],
        ]);
        User::create([
            'email' => $input['email'],
            'name' => $input['name'],
            'phone' => $input['phone'],
            'password' => Hash::make($input['password'])
        ]);

        return response()->json([
            'data' => 'user registered successfully'
        ]);
    }

    public function user()
    {
        $user = auth()->user();
        return response()->json(["data" => $user]);
    }

    public function reserveResort(Request $request)
    {
        $input = $request->validate([
            'resort_id' => ['required', 'integer', 'exists:resorts,id'],
            'date' => ['required', 'date'],
        ]);

        $existingReservation = Resevations::where('resort_id', $input['resort_id'])
            ->where('date', $input['date'])
            ->first();

        if ($existingReservation) {
            return response()->json(['status' => 'error', 'message' => 'Resort is already reserved for this date']);
        }

        Resevations::create([
            'user_id' => auth()->id(),
            'resort_id' => $input['resort_id'],
            'start_datr' => $input['start_date'],
            'end_date' => $input['end_date'],
        ]);

        return response()->json(['status' => 'success', 'message' => 'Resort reserved successfully']);
    }

    public function reservation()
    {
        $reservations = auth()->user()->reservations;
        $results = $reservations->map(function ($reservation) {
            return [
                'id' => $reservation->id,
                'owner' => $reservation->resort->name,
                'resevation_status' => $reservation->status,
                'resort' => $reservation->resort->name,
                'resort_id' => $reservation->resort->id,
                'resort_location' => $reservation->resort->location,
                'start_date' => $reservation->start_date,
                'end_date' => $reservation->end_date,
                'image_url' => $reservation->resort->images->first()->image_url,
            ];
        });
        return response()->json(['data' => $results]);
    }

    public function deleteReservation(Request $request, $id)
    {
        $reservation = Resevations::find($id);

        if (!$reservation) {
            return response()->json(['status' => 'error', 'message' => 'Reservation not found']);
        }

        if ($reservation->user_id !== auth()->id()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $reservation->delete();

        return response()->json(['status' => 'success', 'message' => 'Reservation deleted successfully']);
    }
    public function updateReservation(Request $request, $id)
    {
        $input = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
        ]);
        $reservation = auth()->user()->reservations()->findOrFail($id);
        try {
            $reservation->update($input);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => ' date is not avalibale']);
        }
        return response()->json(['status' => 'success', 'message' => 'Reservation updated successfully']);
    }


    public function addToFavorites(Request $request)
    {
        $input = $request->validate([
            'resort_id' => ['required', 'integer', 'exists:resorts,id'],
        ]);

        $user = auth()->user()->favorites()->create($input);


        return response()->json(['status' => 'success', 'message' => 'Resort added to favorites successfully']);
    }

    public function removeFromFavorites(Request $request)
    {
        $input = $request->validate([
            'resort_id' => ['required', 'integer', 'exists:resorts,id'],
        ]);

        $user = auth()->user();
        $user->favorites()->findOrFail($input['resort_id'])->delete();

        return response()->json(['status' => 'success', 'message' => 'Resort removed from favorites successfully']);
    }

    public function favorites()
    {
        $favorites = auth()->user()->favorites()->get();
        // $results = $favorites->map(function ($favorite) {
        //     return 
        //         $favorite->resort->with(['images','spasifications','reservations'])->get();
        // });
        $results = [];
        foreach ($favorites as $favorite) {
            $resort = $favorite->resort;
            $resort->images;
            $resort->spasifications;
            $resort->reservations;
            $results[] = $resort;
        }
        return response()->json(
            ['data'=>$results]
        );
    }

    public function updateUser(Request $request)
    {
        $input = $request->validate([
            'name' => ['string'],
            'email' => ['email'],
            'phone' => ['string'],
            
        ]);

        $user = auth()->user();
        $user->update($input);

        return response()->json(['status' => 'success', 'message' => 'User updated successfully']);
    }
    
    

    

}
