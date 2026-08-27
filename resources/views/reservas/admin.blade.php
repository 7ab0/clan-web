<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Admin — Reservas</title>
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
  .btn-accent { background: #a3691f; color: #fff; }
  .btn-accent:hover { background: #8a5819; }
  .btn-sm { padding: 0.35rem 0.65rem; font-size: 0.78rem; border-radius: 4px; }
  .btn-whatsapp { background: #e5f4e6; color: #1f7a3f; }
  .btn-whatsapp:hover { background: #cdeccf; }

  .flash {
    background: #e5f4e6;
    border: 1px solid #bfe3c1;
    color: #2e7d32;
    padding: 0.75rem 1rem;
    border-radius: 6px;
    font-size: 0.85rem;
    margin-bottom: 1.25rem;
  }

  .summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
  }
  .card {
    background: #fff;
    border: 1px solid #e8e4dd;
    border-radius: 6px;
    padding: 1.1rem 1.25rem;
  }
  .card .num { font-size: 1.9rem; font-weight: 700; line-height: 1.1; }
  .card .label {
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #7a7365;
    margin-top: 0.35rem;
  }
  .card.confirmed .num { color: #2e7d32; }
  .card.pending .num { color: #b8860b; }
  .card.cancelled .num { color: #c0392b; }

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
  th a { color: inherit; text-decoration: none; }
  th a:hover { color: #a3691f; }
  .badge {
    display: inline-block;
    padding: 0.25rem 0.65rem;
    border-radius: 100px;
    font-size: 0.78rem;
    font-weight: 600;
  }
  .badge.pending { background: #fdf3d8; color: #9a7300; }
  .badge.confirmed { background: #e5f4e6; color: #2e7d32; }
  .badge.cancelled { background: #fbe4e1; color: #c0392b; }
  .badge.completed { background: #e3edfb; color: #1c4e91; }
  .empty { padding: 3rem 1rem; text-align: center; color: #7a7365; }
  .code { font-family: 'Courier New', monospace; font-size: 0.85rem; }
  .actions-cell { display: flex; gap: 0.4rem; flex-wrap: wrap; }
</style>
</head>
<body>

  <div class="topbar">
    <div>
      <h1>Reservas — Fermento &amp; Íntimo</h1>
      <p class="sub">Confirma el pago de la seña una vez recibido por WhatsApp</p>
    </div>
    <div style="display:flex;gap:0.75rem;align-items:center;">
      <a href="{{ route('reservas.admin.clientes') }}" class="btn btn-outline" style="background:#fff;border:1px solid #d8d2c4;color:#2a2a2a;">Clientes</a>
      <a href="{{ route('reservas.admin.guests') }}" class="btn btn-outline" style="background:#fff;border:1px solid #d8d2c4;color:#2a2a2a;">Invitados</a>
      <form method="POST" action="{{ route('reservas.admin.logout') }}">
        @csrf
        <button type="submit" class="btn btn-dark">Cerrar sesión</button>
      </form>
    </div>
  </div>

  @if (session('status'))
    <div class="flash">{{ session('status') }}</div>
  @endif

  <div class="summary">
    <div class="card">
      <div class="num">{{ $summary['total'] }}</div>
      <div class="label">Total reservas</div>
    </div>
    <div class="card pending">
      <div class="num">{{ $summary['pending'] }}</div>
      <div class="label">Pendientes</div>
    </div>
    <div class="card confirmed">
      <div class="num">{{ $summary['confirmed'] }}</div>
      <div class="label">Confirmadas</div>
    </div>
    <div class="card cancelled">
      <div class="num">{{ $summary['cancelled'] }}</div>
      <div class="label">Canceladas</div>
    </div>
  </div>

  <form method="GET" action="{{ route('reservas.admin.index') }}" class="filters">
    <input type="hidden" name="sort" value="{{ $sort }}">
    <input type="hidden" name="dir" value="{{ $dir }}">
    <select name="event">
      <option value="todos" @selected($eventSlug === 'todos')>Todos los eventos</option>
      @foreach ($events as $event)
        <option value="{{ $event->slug }}" @selected($eventSlug === $event->slug)>{{ $event->name }}</option>
      @endforeach
    </select>
    <select name="status">
      <option value="todos" @selected($status === 'todos')>Todos los estados</option>
      <option value="pending" @selected($status === 'pending')>Pendientes</option>
      <option value="confirmed" @selected($status === 'confirmed')>Confirmadas</option>
      <option value="cancelled" @selected($status === 'cancelled')>Canceladas</option>
      <option value="completed" @selected($status === 'completed')>Completadas</option>
    </select>
    <button type="submit">Filtrar</button>
    @if ($eventSlug !== 'todos' || $status !== 'todos')
      <a class="clear" href="{{ route('reservas.admin.index', ['sort' => $sort, 'dir' => $dir]) }}">Limpiar filtros</a>
    @endif
  </form>

  @php
    $sortLink = fn (string $column) => route('reservas.admin.index', [
        'event' => $eventSlug,
        'status' => $status,
        'sort' => $column,
        'dir' => ($sort === $column && $dir === 'desc') ? 'asc' : 'desc',
    ]);
    $arrow = fn (string $column) => $sort === $column ? ($dir === 'asc' ? ' ▲' : ' ▼') : '';
  @endphp

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th><a href="{{ $sortLink('code') }}">Código{{ $arrow('code') }}</a></th>
          <th><a href="{{ $sortLink('customer_name') }}">Cliente{{ $arrow('customer_name') }}</a></th>
          <th>Teléfono</th>
          <th>Evento</th>
          <th>Fecha</th>
          <th>Mesa</th>
          <th>Personas</th>
          <th>Seña</th>
          <th><a href="{{ $sortLink('status') }}">Estado{{ $arrow('status') }}</a></th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($reservations as $reservation)
          @php
            // Números locales (9 dígitos, como se piden en el formulario de
            // reserva) llevan el prefijo de país agregado; si el cliente ya
            // escribió el prefijo, se usa tal cual.
            $phoneDigits = preg_replace('/\D+/', '', (string) $reservation->customer_phone);
            $waPhone = $phoneDigits === '' ? null : (strlen($phoneDigits) === 9 ? '51' . $phoneDigits : $phoneDigits);
          @endphp
          <tr>
            <td class="code">{{ $reservation->code }}</td>
            <td>{{ $reservation->customer_name }}</td>
            <td>{{ $reservation->customer_phone ?: '—' }}</td>
            <td>{{ $reservation->event->name }}</td>
            <td>{{ $reservation->schedule->date->format('d/m/Y') }} {{ \Illuminate\Support\Str::of($reservation->schedule->start_time)->substr(0, 5) }}</td>
            <td>{{ $reservation->table ? '#' . $reservation->table->table_number : '—' }}</td>
            <td>{{ $reservation->party_size }}</td>
            <td>S/ {{ number_format($reservation->payment->amount ?? $reservation->total_amount, 2) }}</td>
            <td><span class="badge {{ $reservation->status }}">{{ ucfirst($reservation->status) }}</span></td>
            <td class="actions-cell">
              @if ($waPhone)
                <a class="btn btn-sm btn-whatsapp" target="_blank" rel="noopener"
                   href="https://wa.me/{{ $waPhone }}">WhatsApp</a>
              @endif
              @if ($reservation->status === 'pending')
                <form method="POST" action="{{ route('reservas.admin.confirmar', $reservation) }}"
                      onsubmit="return confirm('¿Confirmar el pago de la seña de {{ $reservation->customer_name }} ({{ $reservation->code }})?')">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-accent">Confirmar pago</button>
                </form>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="10" class="empty">No se encontraron reservas con esos filtros.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</body>
</html>
