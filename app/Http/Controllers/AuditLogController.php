<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        //Filtro por acción
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        //Filtro por modelo
        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }

        //Filtro por fecha
        if ($request->filled('date')) {
            $query->where('created_at', $request->date);
        }

        $logs = $query->paginate(30)->appends(request()->query());
        $models = AuditLog::distinct()->pluck('model')->sort();
        $actions = ['created', 'updated', 'deleted', 'reserved', 'cancelled', 'resolved'];

        return view('audit.index', compact('logs', 'models', 'actions'));
    }
}
