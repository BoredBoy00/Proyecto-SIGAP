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

        // 1. Datos Reales para Gráfica de Facturas (Últimos 5 días)
        $facturasData = [];
        $facturasLabels = [];
        for ($i = 4; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $facturasData[] = Facturacion::whereDate('created_at', $date)->count();
            // Formatear día (ej. Lun, Mar)
            $dias = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
            $facturasLabels[] = $dias[$date->dayOfWeek];
        }

        // 2. Datos Reales para Gráfica de Pacientes (Nuevos vs Recurrentes)
        $pacientesNuevos = Patient::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $pacientesRecurrentes = $pacientesTotal - $pacientesNuevos;

        // 3. Datos Reales para Gráfica de Exámenes (Porcentaje de completados)
        $resultadosTotal = \App\Models\ResulExamen::count();
        $porcentajeCompletados = $examenesTotal > 0 ? round(($resultadosTotal / $examenesTotal) * 100) : 0;

        $recentActivity = Activity::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $recentPatients = Patient::orderBy('created_at', 'desc')->paginate(5, ['*'], 'patients_page');
        $recentExams = \App\Models\Detalle::with(['examen', 'orden.paciente'])
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'exams_page');

        $examenesHoy = \App\Models\Detalle::whereDate('created_at', Carbon::today())->count();
        $examenesSemana = \App\Models\Detalle::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $examenesMes = \App\Models\Detalle::whereMonth('created_at', Carbon::now()->month)
                            ->whereYear('created_at', Carbon::now()->year)
                            ->count();

        return view('dashboard', compact(
            'facturasHoy', 'pacientesTotal', 'examenesTotal', 
            'recentActivity', 'recentPatients', 'recentExams', 
            'examenesHoy', 'examenesSemana', 'examenesMes',
            'facturasData', 'facturasLabels', 'pacientesNuevos', 'pacientesRecurrentes', 'porcentajeCompletados'
        ));
    }
}
