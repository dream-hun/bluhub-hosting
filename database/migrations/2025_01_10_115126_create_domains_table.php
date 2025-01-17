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
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->string('domain');
            $table->string('tld');
            $table->string('status')->default('active');
            $table->timestamp('registered_at')->nullable();
            $table->timestamp('expiration_date')->nullable();
            $table->timestamp('transfer_date')->nullable();
            $table->string('who_is_privacy')->default('disabled');
            $table->string('auto_renew')->nullable()->default('true');
            $table->string('auth_code')->nullable();
            $table->foreignId('domain_pricing_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
