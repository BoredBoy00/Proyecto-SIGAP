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
        $examenesTotal = \App\Models\Detalle::count();

        $recentActivity = Activity::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $recentPatients = Patient::orderBy('created_at', 'desc')->take(3)->get();
        $recentExams = \App\Models\Detalle::with(['examen', 'orden.paciente'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $examenesHoy = \App\Models\Detalle::whereDate('created_at', Carbon::today())->count();
        $examenesSemana = \App\Models\Detalle::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $examenesMes = \App\Models\Detalle::whereMonth('created_at', Carbon::now()->month)
                            ->whereYear('created_at', Carbon::now()->year)
                            ->count();

        return view('dashboard', compact('facturasHoy', 'pacientesTotal', 'examenesTotal', 'recentActivity', 'recentPatients', 'recentExams', 'examenesHoy', 'examenesSemana', 'examenesMes'));
    }
}
