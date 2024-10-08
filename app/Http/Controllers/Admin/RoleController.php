<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRoles;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::latest()->get();

        return Inertia::render('Admin/RoleManagement/Index', [
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
            'description' => 'required'
        ]);



        Role::create([
            'name' => $request->name,
            'description' => $request->description
        ]);


        return back()->with(['message_success' => 'Role Added Success']);
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
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $role = Role::find($id);


        $role->update([
            'name' => $request->name,
            'description' => $request->description
        ]);


        return back()->with([
            'message_success' => 'Role Data Updated!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);


        if($role->name === UserRoles::ADMIN->value){
            return back()->with([
                'message_error' => "Admin Role can't be Deleted"
            ]);
        }

        $role->delete();


        return back()->with(['message_success' => 'Role Deleted!']);
    }
}
