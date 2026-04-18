<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = AuditLog::with('user')->orderBy('created_at', 'desc');

        if ($type = $request->input('subject_type')) {
            $query->where('subject_type', $type);
        }

        if ($action = $request->input('action')) {
            $query->where('action', $action);
        }

        $logs = $query->paginate(30)->withQueryString();

        return view('dashboard.audit-logs.index', compact('logs'));
    }
}
