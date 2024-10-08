<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with([
            'roles'
        ])->latest()->get();

        $roles = Role::get();



        return Inertia::render('Admin/UserManagement/Index', [
            'users' => $users,
            'roles' => $roles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'role' => 'required'
        ]);

        $user  = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('123')
        ]);

        $role = Role::find($request->role);

        $user->assignRole($role);


        return back()->with([
            'message_success' =>  'User Added '
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);


        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);


        $isSameRole = $user->roles()->where('id', $request->role)->exists();

        if(!$isSameRole){

            $newRole = Role::find($request->role);

            $oldRole = $user->roles()->first();

            $user->removeRole($oldRole);

            $user->assignRole($newRole);

        }

        return back()->with([
            'message_success' => 'User Data Updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);


        $user->delete();


        return back()->with(['message_success' => 'User Deleted Success']);
    }
}
