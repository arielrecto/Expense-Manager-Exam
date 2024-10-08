<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\ProfileController;
use App\Models\Category;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/dashboard', function () {
    $categories = Category::with(['expenses' => function ($query) {
        $query->where('user_id', Auth::user()->id);
    }])
        ->get()
        ->map(function ($category) {
            $totalExpenses = $category->expenses->sum('amount');
            return [
                'category' => $category->name,
                'total_expenses' => $totalExpenses,
            ];
        });
    return Inertia::render('Dashboard', [
        'categories' => $categories
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['role:admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::resource('roles', RoleController::class);
    Route::resource('user', UserController::class);
    Route::resource('categories', CategoryController::class);
});


Route::resource('expenses', ExpensesController::class);

require __DIR__ . '/auth.php';
