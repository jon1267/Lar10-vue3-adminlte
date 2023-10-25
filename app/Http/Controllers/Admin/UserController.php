<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        //dd(request('query'));
        //$searchQuery = request('query');

        $users = User::query()
            ->when(request('query'), function ($query, $searchQuery) {
                $query->orWhere('name', 'like', "%{$searchQuery}%")
                    ->orWhere('email', 'like', "%{$searchQuery}%");
            })
            ->latest()
            ->paginate(setting('pagination_limit'));

        return $users; //return response()->json($users);
        /*->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at->format(config('app.date_format')),//see 'date_format' in config/app.php
            ];
        }); */
    }

    public function store()
    {
        request()->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8'
        ]);

        return User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
        ]);
    }

    public function update(User $user)
    {
        request()->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|unique:users,email,'.$user->id ,

        ]);

        $user->update([
            'name' => request('name'),
            'email' => request('email'),
            'password' => request('password') ? bcrypt(request('password')) : $user->password,
        ]);

        return $user;
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->noContent();
    }

    public function changeRole(User $user)
    {
        //dd(request('role'));
        $user->update([
            'role' => request('role'),
        ]);

        return response()->json(['success' => true]);
    }

    /*public function search()
    {
        $searchQuery = request('query');
        $users = User::where('name', 'like', "%{$searchQuery}%")
            ->orWhere('email', 'like', "%{$searchQuery}%")
            ->latest()
            ->paginate(1);

        return response()->json($users);
    }*/

    public function bulkDelete()
    {
        $users = User::whereIn('id', request('ids')) ;
        if ($users) {
            $users->delete();

            return response()->json(['message' => 'Users deleted successfully.']);
        }

        return response()->json(['message' => 'Something went wrong. Users not deleted.']);
    }
}
