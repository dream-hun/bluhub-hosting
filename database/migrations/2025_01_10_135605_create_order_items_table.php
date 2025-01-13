<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /*public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('domain_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('hosting_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('ssl_id')->nullable()->constrained()->onDelete('set null');
            $table->unsignedInteger('quantity')->default(1);
            $table->integer('price');
            $table->integer('total');
            $table->integer('status')->default('pending');
            $table->json('meta')->nullable();
            $table->timestamps();

            // Indexes for better query performance
            $table->index(['status']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    /* public function down(): void
     {
         Schema::dropIfExists('order_items');
     }*/
};
