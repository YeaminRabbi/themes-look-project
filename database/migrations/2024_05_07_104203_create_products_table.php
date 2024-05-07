<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->foreignId('category_id');
            $table->foreignId('color_id');
            $table->foreignId('size_id');
            $table->string('unit');
            $table->string('unit_value');
            $table->float('selling_price');
            $table->float('purchase_price');
            $table->tinyinteger('discount')->nullable();
            $table->tinyinteger('tax')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
