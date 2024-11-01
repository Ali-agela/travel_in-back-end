<?php

namespace App\Http\Controllers;

use App\Models\admin;
use Illuminate\Http\Request;
use App\Models\Resort;

class ResortController extends Controller
{
    public function getResorts($id)
    {
        $resorts = Resort::with(['images', 'spasifications', 'reservations'])->Where('admin_id', $id)->get();
        //$resorts = $resorts::with('resorts_images');
        return response()->json(['data' => $resorts]);
    }
    public function addSpasification(Request $request)
    {
        $input = $request->validate([
            'resort_id' => ['required', 'integer'],
            'spasification' => ['required', 'string'],
        ]);
        $resort = auth('admin')->user()->resorts()->findOrFail($input['resort_id']);
        $resort->spasifications()->create([
            'spasification' => $input['spasification']
        ]);
        return response()->json([
            'data' => 'spasification added successfully'
        ]);
    }
    public function index()
    {
        $resorts = auth('admin')->user()->resorts()->get();
        return response()->json(['data' => $resorts]);
    }
    public function show($id)
    {
        $resort = auth('admin')->user()->resorts->findOrfaile($id);
        if ($resort && $resort->owner_id == auth('admin')->id()) {
            return response()->json(['data' => $resort]);
        } else {
            return response()->json(['error' => 'Unauthorized or resort not found'], 403);
        }
    }
    public function store(Request $request)
    {
        $input = $request->validate([
            'name' => ['required', 'string'],
            'location' => ['required', 'string'],
            'description' => ['required', 'string'],
            'number_of_rooms' => ['required', 'integer'],
            'price_per_room' => ['required', 'integer'],
            'number_of_poeple' => ['required', 'integer'],
        ]);
        $resort = Resort::create([
            'name' => $input['name'],
            'location' => $input['location'],
            'description' => $input['description'],
            'admin_id' => auth('admin')->id(),
            'number_of_rooms' => $input['number_of_rooms'],
            'price_per_room' => $input['price_per_room'],
            'number_of_poeple' => $input['number_of_poeple'],
            
        ]);

        return response()->json([
            'data' => 'resort created successfully'
        ]);
    }
    public function update(Request $request, $id)
    {
        $resort = Resort::find($id);
        if ($resort && $resort->owner_id == auth('admin')->id()) {
            $input = $request->validate([
                'name' => ['required', 'string'],
                'location' => ['required', 'string'],
                'description' => ['required', 'string'],
            ]);
            $resort->update([
                'name' => $input['name'],
                'location' => $input['location'],
                'description' => $input['description'],
            ]);
            return response()->json([
                'data' => 'resort updated successfully'
            ]);
        } else {
            return response()->json(['error' => 'Unauthorized or resort not found'], 403);
        }
    }
    public function destroy($id)
    {
        $resort = Resort::find($id);
        $resort->delete();
        return response()->json([
            'data' => 'resort deleted successfully'
        ]);
    }
    public function reservations($id)
    {
        $resort = Resort::find($id);
        if ($resort) {
            $reservations = $resort->reservations;
            return response()->json(['data' => $reservations]);
        } else {
            return response()->json(['error' => 'Resort not found'], 404);
        }
    }

    public function updateResort(Request $request, $id)
    {
        $resort = Resort::find($id);
        if ($resort && $resort->owner_id == auth('admin')->id()) {
            $input = $request->validate([
                'name' => ['required', 'string'],
                'location' => ['required', 'string'],
                'description' => ['required', 'string'],
            ]);
            $resort->update([
                'name' => $input['name'],
                'location' => $input['location'],
                'description' => $input['description'],
            ]);
            return response()->json([
                'data' => 'Resort updated successfully'
            ]);
        } else {
            return response()->json(['error' => 'Unauthorized or resort not found'], 403);
        }
    }

    public function getTwoResortsFromEachAdmin()
    {

        $admins = admin::with([
            'resorts' => function ($query) {
                $query->with(['images', 'spasifications', 'reservations'])->take(2);
            }
        ])->get();


        $result = [];
        foreach ($admins as $admin) {
            foreach ($admin->resorts as $resort) {
                $result[] = $resort;
            }
        }

        return response()->json(['data' => $result]);
    }

}
