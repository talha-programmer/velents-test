<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = UserResource::collection(User::all());
        $response = formatResponse(['users' => $users]);

        return response()->json($response);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|unique:users,email',
                'password' => 'required|string',
                'photo' => 'nullable|image'
            ]);

            $photoUrl = $request->photo ? $request->file('photo')->store('photos') : null;
            $password = Hash::make($request->password);

            $user = User::create([
                ...$request->only(['first_name', 'last_name', 'email']),
                'password' => $password,
                'photo' => $photoUrl,
            ]);

            $user = new UserResource($user);

            $response = formatResponse(['user' => $user], true, 'User saved successfully');
            return response()->json($response, 201);
        } catch (Exception $e) {
            return response()->json(formatResponse(null, false, $e->getMessage()), 500);
        }
    }


    public function update(Request $request, User $user)
    {
        try {
            $request->validate([
                'first_name' => 'nullable|string',
                'last_name' => 'nullable|string',
                'password' => 'nullable|string',
                'email' => ["nullable", Rule::unique('users')->ignore($user->id)],
                'photo' => 'nullable|image'
            ]);

            $photoUrl = null;
            if ($request->photo) {
                Storage::delete("photos/{$user->photo}");
                $photoUrl = $request->file('photo')->store('photos');
            }

            $password = $request->password ? Hash::make($request->password) : null;

            $values = [
                ...$request->only(['first_name', 'last_name', 'email']),
                'password' => $password,
                'photo' => $photoUrl,
            ];

            $values = array_filter($values, fn ($item) => !empty($item));

            $user->update($values);
            $user = new UserResource($user);

            $response = formatResponse(['user' => $user], true, 'User updated successfully');
            return response()->json($response);
        } catch (Exception $e) {
            return response()->json(formatResponse(null, false, $e->getMessage()), 500);
        }
    }


    public function destroy(User $user)
    {
        try {
            if (is_file($user->photo)) {
                Storage::delete("photos/{$user->photo}");
            }

            $user->delete();

            $response = formatResponse(null, true, 'User deleted successfully');
            return response()->json($response);
        } catch (Exception $e) {

            return response()->json(formatResponse(null, false, $e->getMessage()), 500);
        }
    }
}
