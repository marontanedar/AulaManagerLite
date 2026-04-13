<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
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
            $query->whereDate('created_at', $request->date);
        }

        // Filtro por usuario
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->paginate(30)->appends(request()->query());
        $models = AuditLog::distinct()->pluck('model')->sort()->values();
        $actions = ['created', 'updated', 'deleted', 'reserved', 'cancelled', 'resolved'];
        $users = User::orderBy('name')->get(['user_id', 'name']);

        return view('audit.index', compact('logs', 'models', 'actions', 'users'));
    }
}
