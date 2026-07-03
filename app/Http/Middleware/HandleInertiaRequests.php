<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $user = $request->user();

        $doctorId = null;
        if ($user && $user->hasRole('doctor')) {
            $doctor   = \Illuminate\Support\Facades\DB::selectOne(
                'SELECT id FROM doctors WHERE user_id = ?',
                [$user->id]
            );
            $doctorId = $doctor?->id;
        }

        return [
            ...parent::share($request),

            'auth' => [
                'user' => $user ? [
                    'id'          => $user->id,
                    'name'        => $user->name,
                    'email'       => $user->email,
                    'role'        => $user->getRoleNames()->first(),
                    'permissions' => $user->getAllPermissions()->pluck('name'),
                    'doctor_id'   => $doctorId,
                ] : null,
            ],

            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error'   => fn() => $request->session()->get('error'),
            ],
        ];
    }
}
