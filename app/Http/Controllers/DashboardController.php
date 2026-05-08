<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facturacion;
use App\Models\Patient;
use App\Models\Examen;
use App\Models\Activity;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $facturasHoy = Facturacion::whereDate('created_at', Carbon::today())->count();
        $pacientesTotal = Patient::count();
        $examenesTotal = Examen::count();

        $recentActivity = Activity::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('dashboard', compact('facturasHoy', 'pacientesTotal', 'examenesTotal', 'recentActivity'));
    }
}
