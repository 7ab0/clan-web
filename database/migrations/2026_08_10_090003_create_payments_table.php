<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('PEN');
            // simulated | whatsapp | culqi | mercadopago | stripe
            // "whatsapp" = seña coordinada por WhatsApp, confirmada a mano por
            // el staff desde el panel admin de reservas (sin pasarela real).
            $table->string('provider')->default('simulated');
            $table->string('provider_reference')->nullable(); // id de cargo/transacción de la pasarela
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
