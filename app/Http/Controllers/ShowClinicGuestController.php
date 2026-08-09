<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ShowClinicGuestController extends Controller
{
    public function show(Request $request): View
    {
        $guest = null;
        $code = $request->query('inv');

        if ($code) {
            $guest = Guest::where('code', $code)->first();
        }

        return view('showclinic', ['guest' => $guest]);
    }

    public function confirm(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string'],
            'response' => ['required', 'in:confirmado,rechazado'],
            'plus_one' => ['nullable', 'boolean'],
            'companion_name' => ['nullable', 'string', 'max:255'],
            'preferences' => ['nullable', 'string', 'max:1000'],
        ]);

        $guest = Guest::where('code', $validated['code'])->firstOrFail();

        $isConfirmed = $validated['response'] === 'confirmado';
        $plusOne = $isConfirmed && $request->boolean('plus_one');

        $guest->update([
            'status' => $validated['response'],
            'plus_one' => $plusOne,
            'companion_name' => $plusOne ? ($validated['companion_name'] ?? null) : null,
            'preferences' => $isConfirmed ? ($validated['preferences'] ?? null) : null,
            'confirmed_at' => now(),
        ]);

        return redirect()->route('showclinic', ['inv' => $guest->code]);
    }
}
