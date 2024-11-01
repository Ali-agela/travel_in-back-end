<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resevations;

class ResevationsController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate([
            'resort_id' => 'required|exists:resorts,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'adults' => 'required|integer|min:1',
            'kids' => 'required|integer|min:0',
            'method' => 'required|in:credit_card,cash',
            'amount' => 'required|integer|min:1',
        ]);

        // Check for overlapping reservations
        $overlappingReservations = Resevations::where('resort_id', $request->resort_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->exists();

        if ($overlappingReservations) {
            return response()->json(['error' => 'The selected dates are already reserved.'], 409);
        }

        // Create the reservation if no conflicts
            $reservation= auth()->user()->reservations()->create([
            'resort_id' => $request->resort_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'adults' => $request->adults,
            'kids' => $request->kids,
            'method' => $request->method,
            'amount' => $request->amount,

        ]);

        return response()->json($reservation);
    }
    public function showAdminsReservations()
    {
        $reservations = auth('admin')->user()->resorts()->with('resevations')->get();
        return response()->json($reservations);
    }

    public function showUserReservation($id)
    {
        $reservation = Resevations::All()->findOrFail($id);
        return response()->json($reservation);
    }
    
}
