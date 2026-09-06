<?php

namespace App\Http\Controllers;

use App\Models\Influencer;
use App\Models\InfluencerPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Panel de staff del pre-cóctel de Fermento para influencers — a diferencia
 * de reservas/revision (solo lectura), este panel sí puede dar de alta,
 * editar y hacer check-in a mano. Contraseña y sesión propias, ver
 * config('services.influencers.admin_password').
 */
class InfluencerAdminController extends Controller
{
    public function loginForm(Request $request): View|RedirectResponse
    {
        if ($request->session()->get('influencers_admin_authenticated')) {
            return redirect()->route('influencers.admin.index');
        }

        return view('influencers.admin-login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate(['password' => ['required', 'string']]);

        $expected = (string) config('services.influencers.admin_password');

        if ($expected === '' || ! hash_equals($expected, (string) $request->input('password'))) {
            return back()->withErrors(['password' => 'Contraseña incorrecta.']);
        }

        $request->session()->put('influencers_admin_authenticated', true);
        $request->session()->regenerate();

        return redirect()->route('influencers.admin.index');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('influencers_admin_authenticated');

        return redirect()->route('influencers.admin.login');
    }

    /**
     * Dashboard: un influencer por fila, con un resumen rápido de sus posts
     * cargados (cantidad + vistas totales) resuelto en una sola pasada por
     * withCount/withSum en vez de N+1 por fila.
     */
    public function index(): View
    {
        $influencers = Influencer::withCount('posts')
            ->withSum('posts', 'views')
            ->orderBy('name')
            ->get();

        $summary = [
            'total' => Influencer::count(),
            'invitado' => Influencer::where('status', 'invitado')->count(),
            'confirmado' => Influencer::where('status', 'confirmado')->count(),
            'asistio' => Influencer::where('status', 'asistio')->count(),
            'declinado' => Influencer::where('status', 'declinado')->count(),
        ];

        return view('influencers.admin', [
            'influencers' => $influencers,
            'summary' => $summary,
        ]);
    }

    /**
     * Alta manual de un influencer — genera el token y su link personalizado
     * solo, igual que FermentoGuest (ver Influencer::booted()).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'instagram_handle' => ['nullable', 'string', 'max:60'],
            'tiktok_handle' => ['nullable', 'string', 'max:60'],
            'phone' => ['nullable', 'string', 'max:30'],
            'followers_count' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $influencer = Influencer::create([
            ...$validated,
            'is_test' => $request->boolean('is_test'),
        ]);

        return back()->with('status', "Influencer {$influencer->name} agregado.");
    }

    /**
     * Página para generar la imagen de invitación personalizada (Canvas en
     * el navegador, mismo mecanismo que la story card de confirmación de
     * Fermento) — solo necesita id/nombre de cada influencer para el
     * selector, el dibujo del canvas corre 100% en el cliente.
     */
    public function invitacion(): View
    {
        $influencers = Influencer::orderBy('name')->get(['id', 'name']);

        return view('influencers.invitacion', [
            'influencers' => $influencers,
        ]);
    }

    public function show(Influencer $influencer): View
    {
        $influencer->load(['posts' => fn ($q) => $q->orderBy('published_at', 'desc')]);

        return view('influencers.show', [
            'influencer' => $influencer,
        ]);
    }

    /**
     * Edita los datos del influencer y/o su estado. Marcar "asistio" desde
     * acá es el check-in manual del día del evento; confirmed_at/attended_at
     * se sellan la primera vez que el estado llega a ese punto, sin pisar la
     * fecha real si ya estaban seteados.
     */
    public function update(Request $request, Influencer $influencer): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'instagram_handle' => ['nullable', 'string', 'max:60'],
            'tiktok_handle' => ['nullable', 'string', 'max:60'],
            'phone' => ['nullable', 'string', 'max:30'],
            'followers_count' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:invitado,confirmado,declinado,asistio'],
        ]);

        $updates = $validated;

        if ($validated['status'] === 'confirmado' && ! $influencer->confirmed_at) {
            $updates['confirmed_at'] = now();
        }

        if ($validated['status'] === 'asistio' && ! $influencer->attended_at) {
            $updates['attended_at'] = now();
        }

        $influencer->update($updates);

        return back()->with('status', "Influencer {$influencer->name} actualizado.");
    }

    /**
     * Carga manual de un post/story/reel/video con sus métricas. El
     * screenshot es opcional (algunas publicaciones se registran solo con
     * el link) y se guarda en el disco público estándar de Laravel, mismo
     * criterio que storage:link ya documentado para producción.
     */
    public function storePost(Request $request, Influencer $influencer): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:post,story,reel,video'],
            'url' => ['nullable', 'url', 'max:500'],
            'screenshot' => ['nullable', 'image', 'max:8192'],
            'published_at' => ['required', 'date'],
            'views' => ['nullable', 'integer', 'min:0'],
            'likes' => ['nullable', 'integer', 'min:0'],
            'shares' => ['nullable', 'integer', 'min:0'],
            'comments' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $screenshotPath = $request->hasFile('screenshot')
            ? $request->file('screenshot')->store('influencer-posts', 'public')
            : null;

        InfluencerPost::create([
            'influencer_id' => $influencer->id,
            'type' => $validated['type'],
            'url' => $validated['url'] ?? null,
            'screenshot_path' => $screenshotPath,
            'published_at' => $validated['published_at'],
            'views' => $validated['views'] ?? null,
            'likes' => $validated['likes'] ?? null,
            'shares' => $validated['shares'] ?? null,
            'comments' => $validated['comments'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return back()->with('status', 'Post agregado.');
    }
}
