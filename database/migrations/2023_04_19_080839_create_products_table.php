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
            $table->text('category');
            $table->string('title', 50);
            $table->string('slug', 50);
            $table->text('featured_image')->nullable();
            $table->text('gallery')->nullable();
            $table->text('description')->nullable();
            $table->integer('status')->comment('1.active, 2.inactive')->default(1);
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
