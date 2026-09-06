<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Influencers invitados al pre-cóctel de Fermento (martes 1 de
     * septiembre 2026), cada uno con un token único para su link
     * personalizado. Mismo mecanismo de token que fermento_guests, en tabla
     * propia: el pre-cóctel es un evento aparte de las reservas de mesa de
     * Fermento, con su propio flujo de confirmación y check-in.
     */
    public function up(): void
    {
        Schema::create('influencers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('instagram_handle')->nullable();
            $table->string('tiktok_handle')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedInteger('followers_count')->nullable();
            $table->string('token', 8)->unique();
            $table->enum('status', ['invitado', 'confirmado', 'declinado', 'asistio'])->default('invitado');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('attended_at')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_test')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('influencers');
    }
};
