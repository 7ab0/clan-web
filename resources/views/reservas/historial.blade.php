<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Historial — {{ $reservation->code }}</title>
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
  .btn-outline { background: #fff; border: 1px solid #d8d2c4; color: #2a2a2a; }
  .btn-outline:hover { background: #f5f1e4; }

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
  td.wrap { white-space: normal; }
  .empty { padding: 3rem 1rem; text-align: center; color: #7a7365; }

  @media (max-width: 720px) {
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
    tbody td.empty { display: block; text-align: center; }
    tbody td.empty::before { display: none; }
  }
</style>
</head>
<body>

  <div class="topbar">
    <div>
      <h1>Historial — {{ $reservation->code }}</h1>
      <p class="sub">{{ $reservation->customer_name }}</p>
    </div>
    <a href="{{ route('reservas.admin.index') }}" class="btn btn-outline">← Volver</a>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Campo</th>
          <th>Antes</th>
          <th>Después</th>
          <th>Fecha</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($changes as $change)
          <tr>
            <td data-label="Campo">{{ $change->field }}</td>
            <td class="wrap" data-label="Antes">{{ $change->old_value ?: '—' }}</td>
            <td class="wrap" data-label="Después">{{ $change->new_value ?: '—' }}</td>
            <td data-label="Fecha">{{ $change->created_at->format('d/m/Y H:i') }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="empty">Sin cambios registrados todavía.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</body>
</html>
