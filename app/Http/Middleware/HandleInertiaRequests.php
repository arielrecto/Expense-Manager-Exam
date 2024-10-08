<?php

namespace App\Http\Middleware;

use App\Models\User;
use Inertia\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => User::where('id', Auth::user()->id ?? null)->with([
                    'roles'
                ])->first(),
            ],
            'flash' => [
                'message_success' =>  Session::get('message_success'),
                'message_warning' => Session::get('message_warning'),
                'message_error' =>  Session::get('message_error'),
            ]
        ];
    }
}
