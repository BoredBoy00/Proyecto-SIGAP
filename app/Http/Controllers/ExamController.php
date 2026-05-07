<?php

namespace App\Http\Controllers;

use App\Models\Examen;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $examenes = Examen::all();

        // If table is empty, seed some dummy data for the view
        if ($examenes->isEmpty()) {
            $examenes = collect([
                (object)['codigo_examen' => 'HEM-01', 'nombre_examen' => 'Hematología Completa', 'precio_actual' => '15.00'],
                (object)['codigo_examen' => 'QUI-05', 'nombre_examen' => 'Perfil Lipídico', 'precio_actual' => '25.00'],
            ]);
        }

        return view('exams.index', compact('examenes'));
    }
}
