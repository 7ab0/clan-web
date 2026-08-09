<?php

namespace Database\Seeders;

use App\Models\Guest;
use Illuminate\Database\Seeder;

class GuestSeeder extends Seeder
{
    /**
     * Lista original de invitados, migrada desde
     * public/assets/showclinic/js/guests.js
     */
    private const GUESTS = [
        ['code' => 'MDC7K2', 'name' => 'Maria del Carmen'],
        ['code' => 'GTV4P9', 'name' => 'Gustavo'],
        ['code' => 'MRC1L6', 'name' => 'Mauricio'],
        ['code' => 'DEE9X3', 'name' => 'Erick Espetia'],
    ];

    public function run(): void
    {
        foreach (self::GUESTS as $guest) {
            Guest::updateOrCreate(
                ['code' => $guest['code']],
                ['name' => $guest['name'], 'status' => 'pendiente']
            );
        }
    }
}
