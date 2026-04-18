<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('invoice_number')->nullable()->unique()->after('id');
        });

        // Backfill existing invoices with per-year sequential numbers
        $counters = [];
        DB::table('invoices')
            ->orderBy('created_at')
            ->get()
            ->each(function ($invoice) use (&$counters) {
                $year = \Illuminate\Support\Carbon::parse($invoice->created_at)->year;
                $counters[$year] = ($counters[$year] ?? 0) + 1;
                DB::table('invoices')
                    ->where('id', $invoice->id)
                    ->update(['invoice_number' => sprintf('INV-%d-%04d', $year, $counters[$year])]);
            });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropUnique(['invoice_number']);
            $table->dropColumn('invoice_number');
        });
    }
};
