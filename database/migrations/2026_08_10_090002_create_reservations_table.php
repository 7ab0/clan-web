<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // código de reserva legible, ej. INT-7F3K2
            $table->foreignId('event_id')->constrained();
            $table->foreignId('event_schedule_id')->constrained();

            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();

            $table->unsignedTinyInteger('party_size')->default(2);
            // Con quién vive la experiencia: pareja, amigos, hermanos, padre e hijo, madre e hija, etc.
            $table->string('relationship_type')->nullable();
            $table->text('notes')->nullable();

            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
