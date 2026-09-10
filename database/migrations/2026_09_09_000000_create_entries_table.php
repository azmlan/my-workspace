<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('sku')->nullable()->unique();
            $table->string('option_name_1')->nullable();
            $table->string('option_value_1')->nullable();
            $table->string('option_name_2')->nullable();
            $table->string('option_value_2')->nullable();
            $table->string('option_name_3')->nullable();
            $table->string('option_value_3')->nullable();
            $table->decimal('retail_price', 10, 2)->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->string('category');
            $table->integer('quantity')->nullable();
            $table->string('barcode')->nullable();
            $table->string('tax')->nullable();
            $table->integer('weight_grams')->nullable();
            $table->decimal('length_cm', 6, 2)->nullable();
            $table->decimal('width_cm', 6, 2)->nullable();
            $table->decimal('height_cm', 6, 2)->nullable();
            $table->string('submitted_by');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};
