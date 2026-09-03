<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — Lista de espera</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background: #f5f1e4;
    color: #2a2a2a;
    min-height: 100vh;
    padding: 2rem;
  }
  .topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
  }
  .topbar h1 { font-size: 1.4rem; font-weight: 700; }
  .topbar .sub { font-size: 0.85rem; color: #7a7365; }
  .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    border: none;
    padding: 0.55rem 1.1rem;
    border-radius: 4px;
    font-size: 0.85rem;
    cursor: pointer;
    font-family: inherit;
    text-decoration: none;
  }
  .btn-dark { background: #2a2a2a; color: #f5f1e4; }
  .btn-dark:hover { background: #a3691f; }
  .btn-outline { background: #fff; border: 1px solid #d8d2c4; color: #2a2a2a; }
  .btn-outline:hover { border-color: #a3691f; color: #a3691f; }
  .btn-sm { padding: 0.35rem 0.75rem; font-size: 0.78rem; }

  .flash {
    background: #e5f4e6;
    border: 1px solid #bfe3c1;
    color: #2e7d32;
    padding: 0.75rem 1rem;
    border-radius: 6px;
    font-size: 0.85rem;
    margin-bottom: 1.25rem;
  }

  .schedule-card {
    background: #fff;
    border: 1px solid #e8e4dd;
    border-radius: 6px;
    margin-bottom: 1.5rem;
    overflow: hidden;
  }
  .schedule-card .head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.25rem;
    background: #faf8f3;
    border-bottom: 1px solid #e8e4dd;
    flex-wrap: wrap;
    gap: 0.75rem;
  }
  .schedule-card .head h2 { font-size: 1rem; }
  .badge {
    display: inline-block;
    padding: 0.25rem 0.65rem;
    border-radius: 100px;
    font-size: 0.78rem;
    font-weight: 600;
  }
  .badge.open { background: #e5f4e6; color: #2e7d32; }
  .badge.closed { background: #fbeae7; color: #a13a2f; }

  table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
  th, td { text-align: left; padding: 0.65rem 1.25rem; border-bottom: 1px solid #eee; }
  th {
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #7a7365;
  }
  .empty { padding: 1.5rem 1.25rem; color: #7a7365; font-size: 0.85rem; }

  @media (max-width: 720px) {
    table, thead, tbody, th, td, tr { display: block; }
    thead { display: none; }
    tbody tr { border-bottom: 1px solid #eee; padding: 0.5rem 0; }
    td { border: none; padding: 0.25rem 1.25rem; }
  }
</style>
</head>
<body>

  <div class="topbar">
    <div>
      <h1>Lista de espera — Fermento</h1>
      <p class="sub">Manifestaciones de interés cuando una fecha se queda sin mesas. No asignan mesa ni confirman nada — contactar manualmente.</p>
    </div>
    <div style="display:flex;gap:0.75rem;align-items:center;">
      <a href="{{ route('reservas.admin.index') }}" class="btn btn-outline">Reservas</a>
      <a href="{{ route('reservas.admin.mesas') }}" class="btn btn-outline">Mesas por fecha</a>
      <form method="POST" action="{{ route('reservas.admin.logout') }}">
        @csrf
        <button type="submit" class="btn btn-dark">Cerrar sesión</button>
      </form>
    </div>
  </div>

  @if (session('status'))
    <div class="flash">{{ session('status') }}</div>
  @endif

  @forelse ($schedules as $schedule)
    <div class="schedule-card">
      <div class="head">
        <h2>{{ $schedule->date->format('d/m/Y') }} · {{ \Illuminate\Support\Str::of($schedule->start_time)->substr(0, 5) }}
          <span class="badge {{ $schedule->waitlist_closed ? 'closed' : 'open' }}">
            {{ $schedule->waitlist_closed ? 'Lista cerrada' : 'Lista abierta' }}
          </span>
        </h2>
        <form method="POST" action="{{ route('reservas.admin.waitlist.toggle', $schedule) }}">
          @csrf
          <button type="submit" class="btn btn-sm btn-outline">
            {{ $schedule->waitlist_closed ? 'Reabrir lista de espera' : 'Cerrar lista de espera' }}
          </button>
        </form>
      </div>

      @if ($schedule->waitlistEntries->isEmpty())
        <p class="empty">Nadie anotado todavía.</p>
      @else
        <table>
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Teléfono</th>
              <th>Personas</th>
              <th>Anotado</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($schedule->waitlistEntries as $entry)
              <tr>
                <td>{{ $entry->name }}</td>
                <td>{{ $entry->phone }}</td>
                <td>{{ $entry->party_size }}</td>
                <td>{{ $entry->created_at->format('d/m/Y H:i') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>
  @empty
    <div class="schedule-card">
      <p class="empty">No hay fechas activas de Fermento por ahora.</p>
    </div>
  @endforelse

</body>
</html>
