<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\ClientProjectStatus;
use App\Enums\InvoiceStatus;
use App\Http\Controllers\Controller;
use App\Models\ClientProject;
use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Stats cards
        $totalCustomers = Customer::count();
        $activeClientProjects = ClientProject::where('status', ClientProjectStatus::Active)->count();
        $totalUnpaidAmount = Invoice::where('status', '!=', InvoiceStatus::Paid)->sum('amount');
        $overdueProjects = ClientProject::whereNotNull('deadline')
            ->where('deadline', '<', now()->toDateString())
            ->whereNotIn('status', [ClientProjectStatus::Completed, ClientProjectStatus::Cancelled])
            ->count();

        // Recent customers with project count
        $recentCustomers = Customer::withCount('clientProjects')
            ->latest()
            ->take(5)
            ->get();

        // Client projects by status for bar breakdown
        $projectsByStatus = [];
        foreach (ClientProjectStatus::cases() as $status) {
            $projectsByStatus[$status->value] = [
                'label' => $status->label(),
                'color' => $status->color(),
                'count' => ClientProject::where('status', $status)->count(),
            ];
        }
        $totalProjects = array_sum(array_column($projectsByStatus, 'count'));

        return view('dashboard.home', compact(
            'totalCustomers',
            'activeClientProjects',
            'totalUnpaidAmount',
            'overdueProjects',
            'recentCustomers',
            'projectsByStatus',
            'totalProjects',
        ));
    }
}
