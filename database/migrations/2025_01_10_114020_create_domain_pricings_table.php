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
        Schema::create('domain_pricings', function (Blueprint $table) {
            $table->id();
            $table->string('tld')->unique();
            $table->integer('registration_price');
            $table->integer('renewal_price');
            $table->integer('transfer_price');
            $table->integer('grace_period')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domain_pricings');
    }
};
