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

        $recentPatients = Patient::orderBy('created_at', 'desc')->take(3)->get();
        $recentExams = Examen::orderBy('created_at', 'desc')->take(3)->get();

        $examenesHoy = Examen::whereDate('created_at', Carbon::today())->count();
        $examenesSemana = Examen::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $examenesMes = Examen::whereMonth('created_at', Carbon::now()->month)
                            ->whereYear('created_at', Carbon::now()->year)
                            ->count();

        return view('dashboard', compact('facturasHoy', 'pacientesTotal', 'examenesTotal', 'recentActivity', 'recentPatients', 'recentExams', 'examenesHoy', 'examenesSemana', 'examenesMes'));
    }
}
