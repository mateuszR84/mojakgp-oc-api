<?php

namespace StDevs\Kgp\Api\Controllers;

use Mail;
use Response;
use Exception;
use Validator;
use Illuminate\Support\Str;
use RainLab\User\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register()
    {
        try {
            $data = input();

            $validator = Validator::make($data, [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'username' => 'nullable|string|max:255|unique:users',
                'email' => 'required|email|unique:users',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/', // minimum 1 mała, 1 duża, 1 cyfra, 1 znak specjalny
                ],
                'password_confirmation' => 'required|same:password'
            ], [
                'first_name.required' => 'Imię jest wymagane',
                'last_name.required' => 'Nazwisko jest wymagane',
                'email.required' => 'Email jest wymagany',
                'email.email' => 'Podaj poprawny adres email',
                'email.unique' => 'Użytkownik z tym adresem email już istnieje',
                'username.unique' => 'Ta nazwa użytkownika jest już zajęta',
                'password.required' => 'Hasło jest wymagane',
                'password.min' => 'Hasło musi mieć minimum 8 znaków',
                'password.confirmed' => 'Hasła nie są identyczne',
                'password.regex' => 'Hasło musi zawierać minimum: 1 małą literę, 1 dużą literę, 1 cyfrę i 1 znak specjalny',
                'password_confirmation.required' => 'Potwierdzenie hasła jest wymagane',
                'password_confirmation.same' => 'Potwierdzenie hasła musi być identyczne z hasłem'
            ]);

            if ($validator->fails()) {
                return Response::json([
                    'success' => false,
                    'message' => 'Błędy walidacji',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = new User();
            $user->first_name = $data['first_name'];
            $user->last_name = $data['last_name'];
            $user->username = $data['username'] ?? null;
            $user->email = $data['email'];
            $user->password = $data['password'];
            $user->password_confirmation = $data['password_confirmation'];

            $user->save();

            try {
                Mail::send('stdevs.kgp::mail.welcome', ['user' => $user], function ($message) use ($user) {
                    $message->to($user->email, $user->name);
                    $message->subject('Witaj w naszej aplikacji!');
                });
            } catch (Exception $e) {
                \Log::error('Błąd wysyłki emaila powitalnego: ' . $e->getMessage());
            }

            return Response::json([
                'success' => true,
                'message' => 'Użytkownik został pomyślnie zarejestrowany',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'username' => $user->username,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'phone' => $user->phone,
                    'created_at' => $user->created_at
                ]
            ], 201);
        } catch (Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Wystąpił błąd podczas rejestracji: ' . $e->getMessage()
            ], 500);
        }
    }

    public function login()
    {
        $loginData = request()->only('email', 'password');

        try {
            $user = \RainLab\User\Models\User::where('email', $loginData['email'])->first();

            if (!$user || !Hash::check($loginData['password'], $user->password)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            if (!$user->is_activated) {
                return response()->json(['error' => 'Account not activated'], 401);
            }

            $token = Str::random(60);
            $user->api_token = hash('sha256', $token);
            $user->save();

            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Login failed'], 500);
        }
    }
}
