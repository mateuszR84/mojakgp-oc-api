<?php

namespace StDevs\Kgp\Api\Controllers;

use Auth;
use Validator;
use StDevs\Kgp\Models\Hike;
use Illuminate\Routing\Controller;

class HikesController extends Controller
{
    public function create()
    {
        $user = Auth::getUser();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = request()->only('peak', 'date', 'description', 'companion');

        // Walidacja
        $validator = \Validator::make($data, [
            'peak' => 'required|string|max:255',
            'date' => 'required|date|before_or_equal:today',
            'description' => 'required|string|max:1000',
            'companion' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'details' => $validator->errors()
            ], 400);
        }

        $hike = new Hike();
        $hike->fill($data);
        $hike->user_id = $user->id;
        $hike->save();

        return response()->json([
            'message' => 'Hike added successfully',
            'hike' => $hike
        ]);
    }
}
