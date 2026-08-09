<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — Invitados ShowClinic</title>
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
  .topbar h1 {
    font-size: 1.4rem;
    font-weight: 700;
  }
  .topbar .sub {
    font-size: 0.85rem;
    color: #7a7365;
  }
  .logout-btn {
    background: #2a2a2a;
    color: #f5f1e4;
    border: none;
    padding: 0.55rem 1.1rem;
    border-radius: 4px;
    font-size: 0.85rem;
    cursor: pointer;
  }
  .logout-btn:hover {
    background: #a3691f;
  }

  /* Summary cards */
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
  .card .num {
    font-size: 1.9rem;
    font-weight: 700;
    line-height: 1.1;
  }
  .card .label {
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #7a7365;
    margin-top: 0.35rem;
  }
  .card.confirmado .num { color: #2e7d32; }
  .card.pendiente .num { color: #b8860b; }
  .card.rechazado .num { color: #c0392b; }
  .card.plus-one .num { color: #a3691f; }

  /* Filters */
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
  .filters input[type="text"],
  .filters select {
    padding: 0.55rem 0.75rem;
    border: 1px solid #d8d2c4;
    border-radius: 4px;
    font-size: 0.9rem;
    background: #fff;
    color: #2a2a2a;
  }
  .filters input[type="text"] {
    min-width: 220px;
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
  .filters button:hover {
    background: #8a5819;
  }
  .filters a.clear {
    font-size: 0.82rem;
    color: #7a7365;
    text-decoration: underline;
  }

  /* Table */
  .table-wrap {
    background: #fff;
    border: 1px solid #e8e4dd;
    border-radius: 6px;
    overflow-x: auto;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.88rem;
  }
  th, td {
    text-align: left;
    padding: 0.7rem 1rem;
    border-bottom: 1px solid #eee;
    white-space: nowrap;
  }
  td.wrap {
    white-space: normal;
    max-width: 260px;
  }
  th {
    background: #faf8f3;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #7a7365;
  }
  th a {
    color: inherit;
    text-decoration: none;
  }
  th a:hover {
    color: #a3691f;
  }
  tbody tr:hover {
    background: #fbf9f4;
  }
  .badge {
    display: inline-block;
    padding: 0.25rem 0.65rem;
    border-radius: 100px;
    font-size: 0.78rem;
    font-weight: 600;
  }
  .badge.confirmado {
    background: #e5f4e6;
    color: #2e7d32;
  }
  .badge.pendiente {
    background: #fdf3d8;
    color: #9a7300;
  }
  .badge.rechazado {
    background: #fbe4e1;
    color: #c0392b;
  }
  .empty {
    padding: 3rem 1rem;
    text-align: center;
    color: #7a7365;
  }
  .code {
    font-family: 'Courier New', monospace;
    font-size: 0.85rem;
  }
</style>
</head>
<body>

  <div class="topbar">
    <div>
      <h1>Invitados — ShowClinic</h1>
      <p class="sub">Panel de administración del aniversario</p>
    </div>
    <form method="POST" action="{{ route('showclinic.admin.logout') }}">
      @csrf
      <button type="submit" class="logout-btn">Cerrar sesión</button>
    </form>
  </div>

  <div class="summary">
    <div class="card">
      <div class="num">{{ $summary['total'] }}</div>
      <div class="label">Total invitados</div>
    </div>
    <div class="card confirmado">
      <div class="num">{{ $summary['confirmado'] }}</div>
      <div class="label">Confirmados</div>
    </div>
    <div class="card pendiente">
      <div class="num">{{ $summary['pendiente'] }}</div>
      <div class="label">Pendientes</div>
    </div>
    <div class="card rechazado">
      <div class="num">{{ $summary['rechazado'] }}</div>
      <div class="label">Rechazados</div>
    </div>
    <div class="card plus-one">
      <div class="num">{{ $summary['plus_one'] }}</div>
      <div class="label">Con acompañante</div>
    </div>
  </div>

  <form method="GET" action="{{ route('showclinic.admin.index') }}" class="filters">
    <input type="hidden" name="sort" value="{{ $sort }}">
    <input type="hidden" name="dir" value="{{ $dir }}">
    <input type="text" name="search" placeholder="Buscar por nombre…" value="{{ $search }}">
    <select name="status">
      <option value="todos" @selected($status === 'todos')>Todos los estados</option>
      <option value="confirmado" @selected($status === 'confirmado')>Confirmados</option>
      <option value="pendiente" @selected($status === 'pendiente')>Pendientes</option>
      <option value="rechazado" @selected($status === 'rechazado')>Rechazados</option>
    </select>
    <button type="submit">Filtrar</button>
    @if ($search !== '' || $status !== 'todos')
      <a class="clear" href="{{ route('showclinic.admin.index', ['sort' => $sort, 'dir' => $dir]) }}">Limpiar filtros</a>
    @endif
  </form>

  @php
    $sortLink = fn (string $column) => route('showclinic.admin.index', [
        'search' => $search,
        'status' => $status,
        'sort' => $column,
        'dir' => ($sort === $column && $dir === 'asc') ? 'desc' : 'asc',
    ]);
    $arrow = fn (string $column) => $sort === $column ? ($dir === 'asc' ? ' ▲' : ' ▼') : '';
  @endphp

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th><a href="{{ $sortLink('name') }}">Nombre{{ $arrow('name') }}</a></th>
          <th><a href="{{ $sortLink('code') }}">Código{{ $arrow('code') }}</a></th>
          <th><a href="{{ $sortLink('status') }}">Estado{{ $arrow('status') }}</a></th>
          <th>Acompañante</th>
          <th>Preferencias</th>
          <th><a href="{{ $sortLink('confirmed_at') }}">Confirmado el{{ $arrow('confirmed_at') }}</a></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($guests as $guest)
          <tr>
            <td>{{ $guest->name }}</td>
            <td class="code">{{ $guest->code }}</td>
            <td><span class="badge {{ $guest->status }}">{{ ucfirst($guest->status) }}</span></td>
            <td>
              @if ($guest->plus_one)
                Sí{{ $guest->companion_name ? ' — ' . $guest->companion_name : '' }}
              @else
                No
              @endif
            </td>
            <td class="wrap">{{ $guest->preferences ?: '—' }}</td>
            <td>{{ $guest->confirmed_at?->format('d/m/Y H:i') ?? '—' }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="empty">No se encontraron invitados con esos filtros.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</body>
</html>
