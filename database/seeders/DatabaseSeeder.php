<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Enums\Category;
use App\Enums\UserRoles;
use App\Models\Category as ModelsCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);



        $roles = UserRoles::cases();

        collect($roles)->map(function($role){
            Role::create([
                'name' => $role->value
            ]);
        });

        $categories = Category::cases();

        collect($categories)->map(function($category){
            ModelsCategory::create([
                'name' => $category->value
            ]);
        });


        $adminRole = Role::where('name', UserRoles::ADMIN->value)->first();



        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123')
        ]);


        $admin->assignRole($adminRole);
    }
}
