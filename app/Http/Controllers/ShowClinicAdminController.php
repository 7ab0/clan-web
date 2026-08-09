<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ShowClinicAdminController extends Controller
{
    public function loginForm(Request $request): View|RedirectResponse
    {
        if ($request->session()->get('showclinic_admin_authenticated')) {
            return redirect()->route('showclinic.admin.index');
        }

        return view('showclinic.admin-login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate(['password' => ['required', 'string']]);

        $expected = (string) config('services.showclinic.admin_password');

        if ($expected === '' || ! hash_equals($expected, (string) $request->input('password'))) {
            return back()->withErrors(['password' => 'Contraseña incorrecta.']);
        }

        $request->session()->put('showclinic_admin_authenticated', true);
        $request->session()->regenerate();

        return redirect()->route('showclinic.admin.index');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('showclinic_admin_authenticated');

        return redirect()->route('showclinic.admin.login');
    }

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $status = (string) $request->query('status', 'todos');

        $sortable = ['name', 'code', 'status', 'confirmed_at'];
        $sort = in_array($request->query('sort'), $sortable, true) ? $request->query('sort') : 'name';
        $dir = $request->query('dir') === 'desc' ? 'desc' : 'asc';

        $query = Guest::query();

        if ($search !== '') {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if (in_array($status, ['confirmado', 'pendiente', 'rechazado'], true)) {
            $query->where('status', $status);
        }

        $guests = $query->orderBy($sort, $dir)->get();

        $summary = [
            'total' => Guest::count(),
            'confirmado' => Guest::where('status', 'confirmado')->count(),
            'pendiente' => Guest::where('status', 'pendiente')->count(),
            'rechazado' => Guest::where('status', 'rechazado')->count(),
            'plus_one' => Guest::where('plus_one', true)->count(),
        ];

        return view('showclinic.admin', [
            'guests' => $guests,
            'summary' => $summary,
            'search' => $search,
            'status' => $status,
            'sort' => $sort,
            'dir' => $dir,
        ]);
    }
}
