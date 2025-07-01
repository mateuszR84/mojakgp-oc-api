<?php

namespace StDevs\Kgp\Api\Controllers;

use Auth;
use Mail;
use Response;
use Exception;
use Validator;
use RainLab\User\Models\User;
use Illuminate\Routing\Controller;

class HikesController extends Controller
{
    public function create()
    {
        // Middleware już sprawdził token i ustawił użytkownika
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

        // Stwórz nową wyprawę
        $hike = new \YourPlugin\Models\Hike();
        $hike->fill($data);
        $hike->user_id = $user->id; // Używaj ID z tokenu, nie z requesta!
        $hike->save();

        return response()->json([
            'message' => 'Hike added successfully',
            'hike' => $hike
        ]);
    }
}
