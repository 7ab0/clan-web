<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Admin — Clientes</title>
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
  .btn-outline { background: #fff; border: 1px solid #d8d2c4; color: #2a2a2a; }
  .btn-outline:hover { border-color: #a3691f; color: #a3691f; }
  .btn-sm { padding: 0.35rem 0.65rem; font-size: 0.78rem; border-radius: 4px; }

  .flash {
    background: #e5f4e6;
    border: 1px solid #bfe3c1;
    color: #2e7d32;
    padding: 0.75rem 1rem;
    border-radius: 6px;
    font-size: 0.85rem;
    margin-bottom: 1.25rem;
  }

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
  .filters label { font-size: 0.85rem; display: flex; align-items: center; gap: 0.4rem; }
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
  td.wrap { white-space: normal; max-width: 240px; }
  th {
    background: #faf8f3;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #7a7365;
  }
  .badge {
    display: inline-block;
    padding: 0.25rem 0.65rem;
    border-radius: 100px;
    font-size: 0.78rem;
    font-weight: 600;
  }
  .badge.nueva { background: #e3edfb; color: #1c4e91; }
  .badge.ocasional { background: #fdf3d8; color: #9a7300; }
  .badge.frecuente { background: #e5f4e6; color: #2e7d32; }
  .badge.vip { background: #f7e6c4; color: #8a5819; }
  .brand-tag {
    display: inline-block;
    background: #f0ece0;
    color: #5a5344;
    border-radius: 100px;
    padding: 0.15rem 0.55rem;
    font-size: 0.74rem;
    margin-right: 0.25rem;
  }
  .empty { padding: 3rem 1rem; text-align: center; color: #7a7365; }

  .modal-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(20, 18, 12, 0.55);
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
    z-index: 50;
  }
  .modal-backdrop.open { display: flex; }
  .modal { background: #fff; border-radius: 8px; width: 100%; max-width: 420px; padding: 1.75rem; max-height: 90vh; overflow-y: auto; }
  .modal h2 { font-size: 1.1rem; margin-bottom: 1.25rem; }
  .modal label { display: block; font-size: 0.8rem; color: #555; margin-bottom: 0.35rem; margin-top: 0.9rem; }
  .modal input, .modal select, .modal textarea {
    width: 100%;
    padding: 0.6rem 0.75rem;
    border: 1px solid #d8d2c4;
    border-radius: 4px;
    font-size: 0.9rem;
    font-family: inherit;
  }
  .modal .row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
  .modal .checkbox-row { display: flex; align-items: center; gap: 0.5rem; margin-top: 0.9rem; }
  .modal .checkbox-row input { width: auto; }
  .modal-actions { display: flex; justify-content: flex-end; gap: 0.6rem; margin-top: 1.5rem; }

  @media (max-width: 720px) {
    .filters { flex-direction: column; align-items: stretch; }
    .filters select, .filters button, .filters a.clear, .filters label { width: 100%; text-align: center; }
    .filters label { justify-content: center; }

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
      <h1>Clientes — Fermento</h1>
      <p class="sub">Se llena automáticamente con cada reserva. Frecuencia, VIP, cumpleaños y notas se editan a mano.</p>
    </div>
    <div style="display:flex;gap:0.75rem;align-items:center;">
      <a href="{{ route('reservas.admin.index') }}" class="btn btn-outline">Reservas</a>
      <a href="{{ route('reservas.admin.guests') }}" class="btn btn-outline">Invitados</a>
      <form method="POST" action="{{ route('reservas.admin.logout') }}">
        @csrf
        <button type="submit" class="btn btn-dark">Cerrar sesión</button>
      </form>
    </div>
  </div>

  @if (session('status'))
    <div class="flash">{{ session('status') }}</div>
  @endif

  @if ($errors->any())
    <div class="flash" style="background:#fbe4e1;border-color:#f3c6bf;color:#c0392b;">{{ $errors->first() }}</div>
  @endif

  <form method="GET" action="{{ route('reservas.admin.clientes') }}" class="filters">
    <select name="frequency">
      <option value="todas" @selected($frequency === 'todas')>Todas las frecuencias</option>
      <option value="nueva" @selected($frequency === 'nueva')>Nueva</option>
      <option value="ocasional" @selected($frequency === 'ocasional')>Ocasional</option>
      <option value="frecuente" @selected($frequency === 'frecuente')>Frecuente</option>
    </select>
    <label><input type="checkbox" name="vip" value="1" @checked($vipOnly)> Solo VIP</label>
    <button type="submit">Filtrar</button>
    @if ($frequency !== 'todas' || $vipOnly)
      <a class="clear" href="{{ route('reservas.admin.clientes') }}">Limpiar filtros</a>
    @endif
  </form>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Teléfono</th>
          <th>Email</th>
          <th>Marcas</th>
          <th>Frecuencia</th>
          <th>VIP</th>
          <th>Cumpleaños</th>
          <th>Notas</th>
          <th>Cliente desde</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($customers as $customer)
          @php
            $meses = [1=>'ene',2=>'feb',3=>'mar',4=>'abr',5=>'may',6=>'jun',7=>'jul',8=>'ago',9=>'sep',10=>'oct',11=>'nov',12=>'dic'];
            $cumple = $customer->birth_day && $customer->birth_month
                ? $customer->birth_day . ' ' . $meses[$customer->birth_month]
                : '—';
          @endphp
          <tr>
            <td data-label="Nombre">{{ $customer->name }}</td>
            <td data-label="Teléfono">{{ $customer->phone }}</td>
            <td data-label="Email">{{ $customer->email ?: '—' }}</td>
            <td data-label="Marcas">
              @foreach ($customer->brands ?? [] as $brand)
                <span class="brand-tag">{{ $brand }}</span>
              @endforeach
            </td>
            <td data-label="Frecuencia"><span class="badge {{ $customer->frequency }}">{{ ucfirst($customer->frequency) }}</span></td>
            <td data-label="VIP">@if($customer->vip)<span class="badge vip">VIP</span>@else —@endif</td>
            <td data-label="Cumpleaños">{{ $cumple }}</td>
            <td class="wrap" data-label="Notas">{{ $customer->notes ?: '—' }}</td>
            <td data-label="Cliente desde">{{ $customer->created_at->format('d/m/Y') }}</td>
            <td class="actions-cell">
              <button type="button" class="btn btn-sm btn-outline"
                      onclick="openEditModal({{ Js::from([
                          'id' => $customer->id,
                          'name' => $customer->name,
                          'phone' => $customer->phone,
                          'email' => $customer->email,
                          'frequency' => $customer->frequency,
                          'vip' => $customer->vip,
                          'birth_month' => $customer->birth_month,
                          'birth_day' => $customer->birth_day,
                          'notes' => $customer->notes,
                      ]) }})">Editar</button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="10" class="empty">Todavía no hay clientes registrados. Se van a ir agregando solos con cada reserva de Fermento.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="modal-backdrop" id="edit-modal-backdrop">
    <div class="modal">
      <h2>Editar cliente</h2>
      <form method="POST" id="edit-form" action="">
        @csrf
        @method('PUT')
        <label for="edit-name">Nombre</label>
        <input type="text" id="edit-name" name="name" required>
        <div class="row2">
          <div>
            <label for="edit-phone">Teléfono</label>
            <input type="text" id="edit-phone" name="phone" required>
          </div>
          <div>
            <label for="edit-email">Email</label>
            <input type="email" id="edit-email" name="email">
          </div>
        </div>
        <label for="edit-frequency">Frecuencia</label>
        <select id="edit-frequency" name="frequency">
          <option value="nueva">Nueva</option>
          <option value="ocasional">Ocasional</option>
          <option value="frecuente">Frecuente</option>
        </select>
        <div class="row2">
          <div>
            <label for="edit-birth-month">Mes de cumpleaños</label>
            <input type="number" id="edit-birth-month" name="birth_month" min="1" max="12">
          </div>
          <div>
            <label for="edit-birth-day">Día de cumpleaños</label>
            <input type="number" id="edit-birth-day" name="birth_day" min="1" max="31">
          </div>
        </div>
        <label for="edit-notes">Notas</label>
        <textarea id="edit-notes" name="notes" rows="3"></textarea>
        <div class="checkbox-row">
          <input type="checkbox" id="edit-vip" name="vip" value="1">
          <label for="edit-vip" style="margin:0;">Cliente VIP</label>
        </div>
        <div class="modal-actions">
          <button type="button" class="btn btn-outline" onclick="closeModal()">Cancelar</button>
          <button type="submit" class="btn btn-accent">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>

<script>
  function openEditModal(customer) {
    const form = document.getElementById('edit-form');
    form.action = `/reservas/admin/clientes/${customer.id}`;
    document.getElementById('edit-name').value = customer.name || '';
    document.getElementById('edit-phone').value = customer.phone || '';
    document.getElementById('edit-email').value = customer.email || '';
    document.getElementById('edit-frequency').value = customer.frequency || 'nueva';
    document.getElementById('edit-birth-month').value = customer.birth_month || '';
    document.getElementById('edit-birth-day').value = customer.birth_day || '';
    document.getElementById('edit-notes').value = customer.notes || '';
    document.getElementById('edit-vip').checked = !!customer.vip;
    document.getElementById('edit-modal-backdrop').classList.add('open');
  }

  function closeModal() {
    document.getElementById('edit-modal-backdrop').classList.remove('open');
  }

  document.querySelector('.modal-backdrop').addEventListener('click', function (e) {
    if (e.target === this) closeModal();
  });
</script>

</body>
</html>
