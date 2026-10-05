<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('entries');
        Schema::dropIfExists('categories');
    }

    public function down(): void
    {
        // Saharituwaiq feature was removed permanently; nothing to restore.
    }
};
