<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $stats = [
            'users'               => DB::selectOne('SELECT COUNT(*) as count FROM users')->count,
            'doctors'             => DB::selectOne('SELECT COUNT(*) as count FROM doctors')->count,
            'appointments'        => DB::selectOne('SELECT COUNT(*) as count FROM appointments')->count,
            'pending_appointments' => DB::selectOne(
                "SELECT COUNT(*) as count FROM appointments WHERE status = 'pending'"
            )->count,
        ];

        return Inertia::render('Dashboard', compact('stats'));
    }
}
