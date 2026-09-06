<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Admin — Invitados</title>
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
  .ok { color: #2e7d32; font-weight: 600; }
  .muted { color: #7a7365; }
  .missing-phone { color: #b8860b; font-size: 0.82rem; font-style: italic; }
  .actions-cell { display: flex; gap: 0.4rem; flex-wrap: wrap; align-items: center; }

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
  .modal input {
    width: 100%;
    padding: 0.6rem 0.75rem;
    border: 1px solid #d8d2c4;
    border-radius: 4px;
    font-size: 0.9rem;
    font-family: inherit;
  }
  .modal-actions { display: flex; justify-content: flex-end; gap: 0.6rem; margin-top: 1.5rem; }

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
      <h1>Invitados — Fermento</h1>
      <p class="sub">Envío de WhatsApp por etapas: intriga → aceptó → invitación con link personalizado.</p>
    </div>
    <div style="display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap;">
      <button type="button" class="btn btn-accent" onclick="openCreateGuestModal()">+ Agregar invitado</button>
      <a href="{{ route('reservas.admin.guests.invitacion') }}" class="btn btn-accent">Generar invitación</a>
      <a href="{{ route('reservas.admin.index') }}" class="btn btn-outline">Reservas</a>
      <a href="{{ route('reservas.admin.clientes') }}" class="btn btn-outline">Clientes</a>
      <a href="{{ route('reservas.admin.mesas') }}" class="btn btn-outline">Mesas por fecha</a>
      <a href="{{ route('reservas.admin.waitlist') }}" class="btn btn-outline">Lista de espera</a>
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

  <form method="GET" action="{{ route('reservas.admin.guests') }}" class="filters" style="display:flex;gap:0.75rem;align-items:center;margin-bottom:1.25rem;background:#fff;border:1px solid #e8e4dd;border-radius:6px;padding:1rem;">
    <label style="font-size:0.8rem;color:#7a7365;display:flex;flex-direction:column;gap:0.3rem;">
      Fecha invitada
      <select name="fecha" onchange="this.form.submit()" style="padding:0.55rem 0.75rem;border:1px solid #d8d2c4;border-radius:4px;font-size:0.9rem;min-width:220px;">
        <option value="todas" @selected($scheduleFilter === 'todas')>Todas</option>
        @foreach ($inviteSchedules as $schedule)
          <option value="{{ $schedule->id }}" @selected((string) $scheduleFilter === (string) $schedule->id)>
            {{ $schedule->date->format('d/m/Y') }}
          </option>
        @endforeach
      </select>
    </label>
  </form>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Celular</th>
          <th>Fecha invitada</th>
          <th>Abrió el link</th>
          <th>Mensaje 1 (intriga)</th>
          <th>Aceptó</th>
          <th>Mensaje 2 (invitación)</th>
          <th>Reservó</th>
          <th>Pago confirmado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($guests as $guest)
          <tr>
            <td data-label="Nombre">{{ $guest->name }}</td>
            <td data-label="Celular">{{ $guest->phone ?: '—' }}</td>
            <td data-label="Fecha invitada">
              @if ($guest->schedule)
                {{ $guest->schedule->date->format('d/m/Y') }}
              @else
                <span class="muted">Sin asignar</span>
              @endif
            </td>
            <td data-label="Abrió el link">
              @if ($guest->opened_at)
                <span class="ok" title="{{ $guest->opened_at->format('d/m/Y H:i') }}">✓ {{ $guest->opened_at->format('d/m/Y') }}</span>
              @else
                <span class="muted">—</span>
              @endif
            </td>
            <td data-label="Mensaje 1">
              @if ($guest->whatsapp_sent_at)
                <span class="ok" title="{{ $guest->whatsapp_sent_at->format('d/m/Y H:i') }}">✓ {{ $guest->whatsapp_sent_at->format('d/m/Y') }}</span>
              @elseif ($guest->phone)
                <a href="{{ $guest->waLinkIntriga() }}" target="_blank" rel="noopener" class="btn btn-sm btn-whatsapp"
                   onclick="markSent('{{ route('reservas.admin.guests.mensaje1', $guest) }}')">Enviar intriga</a>
              @else
                <span class="missing-phone">Falta celular</span>
              @endif
            </td>
            <td data-label="Aceptó">
              <form method="POST" action="{{ route('reservas.admin.guests.aceptar', $guest) }}">
                @csrf
                <input type="checkbox" onchange="this.form.submit()" @checked($guest->interest_confirmed_at)>
              </form>
            </td>
            <td data-label="Mensaje 2">
              @if ($guest->invite_sent_at)
                <span class="ok" title="{{ $guest->invite_sent_at->format('d/m/Y H:i') }}">✓ {{ $guest->invite_sent_at->format('d/m/Y') }}</span>
              @elseif (! $guest->interest_confirmed_at)
                <span class="muted">—</span>
              @elseif ($guest->phone)
                <a href="{{ $guest->waLinkInvitacion() }}" target="_blank" rel="noopener" class="btn btn-sm btn-whatsapp"
                   onclick="markSent('{{ route('reservas.admin.guests.mensaje2', $guest) }}')">Enviar invitación</a>
              @else
                <span class="missing-phone">Falta celular</span>
              @endif
            </td>
            <td data-label="Reservó">
              @if ($guest->reservation)
                <span class="ok">✓</span>
              @else
                <span class="muted">—</span>
              @endif
            </td>
            <td data-label="Pago">
              @if ($guest->reservation && $guest->reservation->status === 'confirmed')
                <span class="ok">✓</span>
              @else
                <span class="muted">—</span>
              @endif
            </td>
            <td class="actions-cell">
              <button type="button" class="btn btn-sm btn-outline"
                      onclick="openEditModal({{ Js::from(['id' => $guest->id, 'name' => $guest->name, 'phone' => $guest->phone, 'event_schedule_id' => $guest->event_schedule_id]) }})">Editar</button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="10" class="empty">Todavía no hay invitados cargados.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="modal-backdrop" id="edit-modal-backdrop">
    <div class="modal">
      <h2>Editar invitado</h2>
      <form method="POST" id="edit-form" action="">
        @csrf
        @method('PUT')
        <label for="edit-name">Nombre</label>
        <input type="text" id="edit-name" name="name" required>
        <label for="edit-phone">Celular</label>
        <input type="text" id="edit-phone" name="phone" placeholder="987654321">
        <label for="edit-schedule">Fecha invitada</label>
        <select id="edit-schedule" name="event_schedule_id" style="width:100%;padding:0.6rem 0.75rem;border:1px solid #d8d2c4;border-radius:4px;font-size:0.9rem;font-family:inherit;">
          <option value="">Sin asignar</option>
          @foreach ($inviteSchedules as $schedule)
            <option value="{{ $schedule->id }}">{{ $schedule->date->format('d/m/Y') }}</option>
          @endforeach
        </select>
        <div class="modal-actions">
          <button type="button" class="btn btn-outline" onclick="closeModal()">Cancelar</button>
          <button type="submit" class="btn btn-accent">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>

  <div class="modal-backdrop" id="create-guest-modal-backdrop">
    <div class="modal">
      <h2>Agregar invitado</h2>
      <form method="POST" action="{{ route('reservas.admin.guests.store') }}">
        @csrf
        <input type="hidden" name="form_type" value="create_guest">
        <label for="create-guest-name">Nombre</label>
        <input type="text" id="create-guest-name" name="name" required>
        <label for="create-guest-phone">Celular</label>
        <input type="text" id="create-guest-phone" name="phone" placeholder="987654321">
        <label for="create-guest-schedule">Fecha invitada</label>
        <select id="create-guest-schedule" name="event_schedule_id" style="width:100%;padding:0.6rem 0.75rem;border:1px solid #d8d2c4;border-radius:4px;font-size:0.9rem;font-family:inherit;">
          <option value="">Sin asignar</option>
          @foreach ($inviteSchedules as $schedule)
            <option value="{{ $schedule->id }}">{{ $schedule->date->format('d/m/Y') }}</option>
          @endforeach
        </select>
        <label style="display:flex;align-items:center;gap:0.5rem;margin-top:0.9rem;">
          <input type="checkbox" name="is_test" value="1" style="width:auto;">
          <span style="font-size:0.82rem;color:#555;">Es de prueba (su reserva no cuenta como real)</span>
        </label>
        <div class="modal-actions">
          <button type="button" class="btn btn-outline" onclick="closeCreateGuestModal()">Cancelar</button>
          <button type="submit" class="btn btn-accent">Agregar</button>
        </div>
      </form>
    </div>
  </div>

<script>
  function markSent(url) {
    fetch(url, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
      },
    }).catch(function () {});
  }

  function openEditModal(guest) {
    const form = document.getElementById('edit-form');
    form.action = `/reservas/admin/invitados/${guest.id}`;
    document.getElementById('edit-name').value = guest.name || '';
    document.getElementById('edit-phone').value = guest.phone || '';
    document.getElementById('edit-schedule').value = guest.event_schedule_id || '';
    document.getElementById('edit-modal-backdrop').classList.add('open');
  }

  function closeModal() {
    document.getElementById('edit-modal-backdrop').classList.remove('open');
  }

  document.getElementById('edit-modal-backdrop').addEventListener('click', function (e) {
    if (e.target === this) closeModal();
  });

  function openCreateGuestModal() {
    document.getElementById('create-guest-modal-backdrop').classList.add('open');
  }

  function closeCreateGuestModal() {
    document.getElementById('create-guest-modal-backdrop').classList.remove('open');
  }

  document.getElementById('create-guest-modal-backdrop').addEventListener('click', function (e) {
    if (e.target === this) closeCreateGuestModal();
  });

  @if ($errors->any() && old('form_type') === 'create_guest')
    openCreateGuestModal();
    document.getElementById('create-guest-name').value = @json(old('name'));
    document.getElementById('create-guest-phone').value = @json(old('phone'));
  @endif
</script>

</body>
</html>
