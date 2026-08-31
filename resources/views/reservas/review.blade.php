<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Revisión — Reservas confirmadas</title>
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
  .btn-sm { padding: 0.35rem 0.65rem; font-size: 0.78rem; border-radius: 4px; }
  .btn-outline { background: #fff; border: 1px solid #d8d2c4; color: #2a2a2a; }
  .btn-outline:hover { background: #f5f1e4; }

  .filters {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
    align-items: center;
    margin-bottom: 1.25rem;
    background: #fff;
    border: 1px solid #e8e4dd;
    border-radius: 6px;
    padding: 1rem;
  }
  .filters select {
    padding: 0.55rem 0.75rem;
    border: 1px solid #d8d2c4;
    border-radius: 4px;
    font-size: 0.9rem;
    background: #fff;
    color: #2a2a2a;
  }
  .filters button {
    padding: 0.55rem 1.1rem;
    border: none;
    border-radius: 4px;
    background: #a3691f;
    color: #fff;
    font-size: 0.85rem;
    cursor: pointer;
  }
  .filters button:hover { background: #8a5819; }
  .filters a.clear { font-size: 0.82rem; color: #7a7365; text-decoration: underline; }

  .table-wrap {
    background: #fff;
    border: 1px solid #e8e4dd;
    border-radius: 6px;
    overflow-x: auto;
  }
  table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
  th, td { text-align: left; padding: 0.7rem 1rem; border-bottom: 1px solid #eee; white-space: nowrap; }
  th {
    background: #faf8f3;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #7a7365;
  }
  .empty { padding: 3rem 1rem; text-align: center; color: #7a7365; }
  .code { font-family: 'Courier New', monospace; font-size: 0.85rem; }
  .badge {
    display: inline-block;
    padding: 0.25rem 0.65rem;
    border-radius: 100px;
    font-size: 0.78rem;
    font-weight: 600;
    background: #e5f4e6;
    color: #2e7d32;
  }

  .summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
  }
  .card {
    background: #fff;
    border: 1px solid #e8e4dd;
    border-radius: 6px;
    padding: 1rem 1.15rem;
  }
  .card .fecha { font-size: 0.85rem; font-weight: 600; margin-bottom: 0.4rem; }
  .card .ocupacion { font-size: 0.82rem; color: #7a7365; }
  .card .ocupacion strong { color: #2a2a2a; }
  .card .badge-agotado {
    display: inline-block;
    margin-top: 0.4rem;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    color: #a13a2f;
    background: #fbeae7;
    border-radius: 4px;
    padding: 0.15rem 0.5rem;
  }

  @media (max-width: 720px) {
    .filters { flex-direction: column; align-items: stretch; }
    .filters select, .filters button, .filters a.clear { width: 100%; text-align: center; }

    .table-wrap { border: none; background: transparent; overflow-x: visible; }
    table, thead, tbody, th, td, tr { display: block; }
    thead { display: none; }
    tbody tr {
      background: #fff;
      border: 1px solid #e8e4dd;
      border-radius: 8px;
      margin-bottom: 0.75rem;
      padding: 0.75rem 1rem;
    }
    tbody td {
      border: none;
      padding: 0.4rem 0;
      white-space: normal;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 0.75rem;
      text-align: right;
    }
    tbody td::before {
      content: attr(data-label);
      font-weight: 600;
      font-size: 0.72rem;
      text-transform: uppercase;
      letter-spacing: 0.03em;
      color: #7a7365;
      flex-shrink: 0;
      text-align: left;
    }
    tbody td.actions-cell {
      justify-content: flex-start;
      flex-wrap: wrap;
    }
    tbody td.actions-cell::before { display: none; }
    tbody td.empty { display: block; text-align: center; }
    tbody td.empty::before { display: none; }

    .btn { padding: 0.75rem 1.1rem; font-size: 0.92rem; }
    .btn-sm { padding: 0.55rem 0.85rem; font-size: 0.82rem; }

    .modal-overlay, .modal-backdrop { padding: 0; align-items: flex-end; }
    .modal { max-width: 100%; width: 100%; max-height: 92vh; border-radius: 16px 16px 0 0; }
  }
</style>
</head>
<body>

  <div class="topbar">
    <div>
      <h1>Fermento — reservas confirmadas</h1>
      <p class="sub">Panel de solo consulta</p>
    </div>
    <form method="POST" action="{{ route('reservas.review.logout') }}">
      @csrf
      <button type="submit" class="btn btn-dark">Cerrar sesión</button>
    </form>
  </div>

  <div class="summary">
    @foreach ($schedules as $schedule)
      <div class="card">
        <div class="fecha">{{ $schedule['fecha'] }}</div>
        <div class="ocupacion"><strong>{{ $schedule['ocupadas'] }}</strong> de {{ $schedule['total'] }} mesas ocupadas ({{ $schedule['libres'] }} libres)</div>
        @unless ($schedule['is_active'])
          <div class="badge-agotado">Agotado</div>
        @endunless
      </div>
    @endforeach
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Código</th>
          <th>Cliente</th>
          <th>Teléfono</th>
          <th>Fecha</th>
          <th>Mesa</th>
          <th>Personas</th>
          <th>Estado</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($reservations as $reservation)
          <tr>
            <td class="code" data-label="Código">{{ $reservation->code }}</td>
            <td data-label="Cliente">{{ $reservation->customer_name }}</td>
            <td data-label="Teléfono">{{ $reservation->customer_phone ?: '—' }}</td>
            <td data-label="Fecha">{{ $reservation->schedule->date->format('d/m/Y') }} {{ \Illuminate\Support\Str::of($reservation->schedule->start_time)->substr(0, 5) }}</td>
            <td data-label="Mesa">{{ $reservation->table ? '#' . $reservation->table->table_number : '—' }}</td>
            <td data-label="Personas">{{ $reservation->party_size }}</td>
            <td data-label="Estado"><span class="badge">Confirmada</span></td>
            <td class="actions-cell">
              @if ($reservation->event->slug === 'fermento')
                <a class="btn btn-sm btn-outline" target="_blank" rel="noopener"
                   href="{{ route('reservas.confirmacion', $reservation->code) }}">Ver imagen</a>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="empty">Todavía no hay reservas confirmadas.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</body>
</html>
