<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Uploader extends Controller
{
    public function uploadForAdmin(Request $request)
    {
        $request->validate([
            'img' => ['mimes:jpg,png',]
        ]);
        $img = $request->file('img')->store('/admins', 'public');
        $admin = auth('admin')->user();
        $admin->images()->create([
            'image_url' => $img
        ]);
        return response()->json([
            'message' => 'image uploaded successfully',
            'image_name' => $img
        ]);
    }
    public function uploadForResort(Request $request, $id)
    {
        $request->validate([
            'img' => ['mimes:jpg,png']
        ]);
        $img = $request->file('img')->store('/resorts', 'public');
        $resort = auth('admin')->user()->resorts()->findOrFail($id);
        $resort->images()->create([
            'image_url' => $img,
            'resort_id' => $id

        ]);
        return response()->json([
            'message' => 'image uploaded successfully',
            'image_name' => $img
        ]);
    }

    public function uploadForUser(Request $request)  {

        $request->validate([
            'img' => ['mimes:jpg,png',]
        ]);
        $img = $request->file('img')->store('/users', 'public');
        $user = auth()->user();
        $user->update([
            'image_url' => $img,
        ]) ;
        
        return response()->json([
            'message' => 'image uploaded successfully',
            'image_name' => $img
        ]);
    }

    public function updatePic(Request $request)
    {
        $request->validate([
            'img' => ['mimes:jpg,png']
        ]);
        $img = $request->file('img')->store('/users', 'public');
        $user = auth()->user();
        $user->update([
            'image_url' => $img,

        ]);
        return response()->json([
            'message' => 'image uploaded successfully',
            'image_name' => $img
        ]);
    }
}
