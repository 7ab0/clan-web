<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — Mesas por fecha</title>
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
  .btn-sm { padding: 0.3rem 0.65rem; font-size: 0.78rem; }

  .filters {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
    align-items: center;
    margin-bottom: 1.5rem;
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
    min-width: 220px;
  }
  .filters label { font-size: 0.8rem; color: #7a7365; display: flex; flex-direction: column; gap: 0.3rem; }

  .empty { padding: 3rem 1rem; text-align: center; color: #7a7365; background: #fff; border: 1px solid #e8e4dd; border-radius: 6px; }

  .aforo-card {
    background: #fff;
    border: 1px solid #e8e4dd;
    border-radius: 6px;
    padding: 1.5rem;
    display: flex;
    gap: 2.5rem;
    flex-wrap: wrap;
  }
  .aforo-card .stat .num { font-size: 1.6rem; font-weight: 700; }
  .aforo-card .stat .label { font-size: 0.8rem; color: #7a7365; text-transform: uppercase; letter-spacing: 0.03em; }

  .mesas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
  }
  .mesa-card {
    background: #fff;
    border: 1px solid #e8e4dd;
    border-left: 4px solid #bfe3c1;
    border-radius: 6px;
    padding: 1rem 1.15rem;
  }
  .mesa-card.taken { border-left-color: #c0392b; }
  .mesa-card .num { font-size: 1.05rem; font-weight: 700; margin-bottom: 0.2rem; }
  .mesa-card .cap { font-size: 0.78rem; color: #7a7365; margin-bottom: 0.6rem; }
  .badge {
    display: inline-block;
    padding: 0.25rem 0.65rem;
    border-radius: 100px;
    font-size: 0.78rem;
    font-weight: 600;
  }
  .badge.libre { background: #e5f4e6; color: #2e7d32; }
  .badge.ocupada { background: #fbeae7; color: #a13a2f; }
  .mesa-card .reserva { margin-top: 0.6rem; font-size: 0.85rem; }
  .mesa-card .reserva .code { font-family: 'Courier New', monospace; color: #7a7365; font-size: 0.78rem; }

  @media (max-width: 720px) {
    .filters { flex-direction: column; align-items: stretch; }
    .filters select { width: 100%; }
    .aforo-card { flex-direction: column; gap: 1rem; }
  }
</style>
</head>
<body>

  <div class="topbar">
    <div>
      <h1>Mesas por fecha</h1>
      <p class="sub">Para editar o cancelar una reserva, usa el listado principal — la única edición posible acá es el cupo de la mesa comunitaria.</p>
    </div>
    <div style="display:flex;gap:0.75rem;align-items:center;">
      <a href="{{ route('reservas.admin.index') }}" class="btn btn-outline">Reservas</a>
      <a href="{{ route('reservas.admin.clientes') }}" class="btn btn-outline">Clientes</a>
      <a href="{{ route('reservas.admin.guests') }}" class="btn btn-outline">Invitados</a>
      <a href="{{ route('reservas.admin.waitlist') }}" class="btn btn-outline">Lista de espera</a>
      <form method="POST" action="{{ route('reservas.admin.logout') }}">
        @csrf
        <button type="submit" class="btn btn-dark">Cerrar sesión</button>
      </form>
    </div>
  </div>

  @if (session('status'))
    <div class="empty" style="background:#e5f4e6;border-color:#bfe3c1;color:#2e7d32;padding:0.75rem 1rem;text-align:left;margin-bottom:1.25rem;">{{ session('status') }}</div>
  @endif
  @if ($errors->any())
    <div class="empty" style="background:#fbe4e1;border-color:#f3c6bf;color:#c0392b;padding:0.75rem 1rem;text-align:left;margin-bottom:1.25rem;">{{ $errors->first() }}</div>
  @endif

  <form method="GET" action="{{ route('reservas.admin.mesas') }}" class="filters">
    <label>
      Evento
      <select name="event" onchange="this.form.submit()">
        @foreach ($events as $event)
          <option value="{{ $event->slug }}" @selected($eventSlug === $event->slug)>{{ $event->name }}</option>
        @endforeach
      </select>
    </label>

    @if ($schedules->isNotEmpty())
      <label>
        Fecha
        <select name="schedule" onchange="this.form.submit()">
          @foreach ($schedules as $schedule)
            <option value="{{ $schedule->id }}" @selected($selectedSchedule && $selectedSchedule->id === $schedule->id)>
              {{ $schedule->date->format('d/m/Y') }} · {{ \Illuminate\Support\Str::of($schedule->start_time)->substr(0, 5) }}
              {{ $schedule->is_active ? '' : '(Agotado)' }}
            </option>
          @endforeach
        </select>
      </label>
    @endif
  </form>

  @if ($schedules->isEmpty())
    <div class="empty">Este evento todavía no tiene fechas configuradas.</div>
  @elseif ($tables !== null)
    <div class="mesas-grid">
      @foreach ($tables as $table)
        @php $occupied = $table['reservations']->sum('party_size'); @endphp
        <div class="mesa-card {{ $table['reservations']->isNotEmpty() ? 'taken' : '' }}">
          <div class="num">Mesa #{{ $table['table_number'] }}{{ $table['is_social'] ? ' (comunitaria)' : '' }}</div>
          <div class="cap">{{ $table['capacity_min'] }}–{{ $table['capacity_max'] }} personas</div>
          <span class="badge" style="background:{{ $table['is_active'] ? '#e8f3e8' : '#f3e8e8' }};color:{{ $table['is_active'] ? '#2f6b2f' : '#8a2f2f' }};">
            {{ $table['is_active'] ? 'Habilitada' : 'No disponible' }}
          </span>
          @if ($table['reservations']->isNotEmpty())
            <span class="badge ocupada">{{ $table['is_social'] ? $occupied . '/' . $table['capacity_max'] . ' ocupado' : 'Ocupada' }}</span>
            <div class="reserva">
              @foreach ($table['reservations'] as $reservation)
                {{ $reservation->customer_name }} ({{ $reservation->party_size }})<br>
                <span class="code">{{ $reservation->code }}</span>
                @if (! $loop->last)<hr style="margin:0.4rem 0;border:none;border-top:1px dashed #e8e4dd;">@endif
              @endforeach
            </div>
          @else
            <span class="badge libre">Libre</span>
          @endif

          @if ($table['is_social'])
            <form method="POST" action="{{ route('reservas.admin.mesas.social-capacity', $table['id']) }}" style="margin-top:0.75rem;display:flex;gap:0.4rem;align-items:center;">
              @csrf
              <label style="font-size:0.75rem;color:#7a7365;">Cupo total
                <input type="number" name="capacity_max" value="{{ $table['capacity_max'] }}" min="{{ max($occupied, 1) }}" max="100"
                       style="width:70px;padding:0.3rem 0.4rem;border:1px solid #d8d2c4;border-radius:4px;font-size:0.85rem;margin-left:0.3rem;">
              </label>
              <button type="submit" class="btn btn-sm btn-outline">Guardar</button>
            </form>
          @endif

          <form method="POST" action="{{ route('reservas.admin.mesas.disponibilidad', $table['id']) }}" style="margin-top:0.5rem;">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline" onclick="return confirm('¿{{ $table['is_active'] ? 'Deshabilitar' : 'Habilitar' }} la Mesa #{{ $table['table_number'] }} para el flujo público?');">
              {{ $table['is_active'] ? 'Deshabilitar' : 'Habilitar' }}
            </button>
          </form>
        </div>
      @endforeach
    </div>
  @else
    <div class="aforo-card">
      <div class="stat">
        <div class="num">{{ $aforo['capacity'] }}</div>
        <div class="label">Aforo total</div>
      </div>
      <div class="stat">
        <div class="num">{{ $aforo['reserved'] }}</div>
        <div class="label">Reservados</div>
      </div>
      <div class="stat">
        <div class="num">{{ $aforo['available'] }}</div>
        <div class="label">Disponibles</div>
      </div>
    </div>
    <p class="sub" style="margin-top:1rem;">Este evento no asigna mesas individuales — el cupo se controla por aforo total del turno.</p>
  @endif

</body>
</html>
