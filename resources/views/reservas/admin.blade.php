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
  .btn-outline { background: #fff; border: 1px solid #d8d2c4; color: #2a2a2a; }
  .btn-outline:hover { background: #f5f1e4; }
  .btn-danger { background: #fbe4e1; color: #c0392b; }
  .btn-danger:hover { background: #f6cdc7; }
  .badge.test { background: #eee; color: #666; margin-left: 0.4rem; }

  .modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    align-items: center;
    justify-content: center;
    z-index: 50;
    padding: 1rem;
  }
  .modal-overlay.open { display: flex; }
  .modal {
    background: #fff;
    border-radius: 8px;
    padding: 1.5rem;
    width: 100%;
    max-width: 420px;
    max-height: 90vh;
    overflow-y: auto;
  }
  .modal h3 { font-size: 1.05rem; margin-bottom: 1rem; }
  .modal label {
    display: block;
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    color: #555;
    margin-bottom: 0.35rem;
    margin-top: 0.9rem;
  }
  .modal label:first-of-type { margin-top: 0; }
  .modal input, .modal select, .modal textarea {
    width: 100%;
    padding: 0.6rem 0.75rem;
    border: 1px solid #d8d2c4;
    border-radius: 4px;
    font-size: 0.9rem;
    font-family: inherit;
  }
  .modal textarea { resize: vertical; min-height: 70px; }
  .modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.6rem;
    margin-top: 1.5rem;
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
      <h1>Reservas — Fermento &amp; Íntimo</h1>
      <p class="sub">Confirma el pago de la seña una vez recibido por WhatsApp</p>
    </div>
    <div style="display:flex;gap:0.75rem;align-items:center;">
      <button type="button" class="btn btn-accent" onclick="openCreateModal()">+ Agregar reserva</button>
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

  @if ($errors->any())
    <div class="flash" style="background:#fbe4e1;border-color:#f3c6bf;color:#c0392b;">{{ $errors->first() }}</div>
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
            <td class="code" data-label="Código">{{ $reservation->code }} @if ($reservation->is_test)<span class="badge test">PRUEBA</span>@endif</td>
            <td data-label="Cliente">{{ $reservation->customer_name }}</td>
            <td data-label="Teléfono">{{ $reservation->customer_phone ?: '—' }}</td>
            <td data-label="Evento">{{ $reservation->event->name }}</td>
            <td data-label="Fecha">{{ $reservation->schedule->date->format('d/m/Y') }} {{ \Illuminate\Support\Str::of($reservation->schedule->start_time)->substr(0, 5) }}</td>
            <td data-label="Mesa">{{ $reservation->table ? '#' . $reservation->table->table_number : '—' }}</td>
            <td data-label="Personas">{{ $reservation->party_size }}</td>
            <td data-label="Seña">S/ {{ number_format($reservation->payment->amount ?? $reservation->total_amount, 2) }}</td>
            <td data-label="Estado"><span class="badge {{ $reservation->status }}">{{ ucfirst($reservation->status) }}</span></td>
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
              @if ($reservation->event->slug === 'fermento')
                <a class="btn btn-sm btn-outline" target="_blank" rel="noopener"
                   href="{{ route('reservas.confirmacion', $reservation->code) }}">Ver imagen</a>
              @endif
              @if ($reservation->event->slug === 'fermento' && $reservation->status === 'confirmed' && $waPhone)
                @php
                  $storyFirstName = explode(' ', trim($reservation->customer_name))[0];
                  $storyFecha = $reservation->schedule->date->format('d/m/Y');
                  $storyHora = \Illuminate\Support\Str::of($reservation->schedule->start_time)->substr(0, 5);
                  $storyMesa = $reservation->table ? '#' . $reservation->table->table_number : '—';
                  $storyBlocks = [
                      'Hola, *' . $storyFirstName . '* 👋',
                      "Tu reserva para *FERMENTO* está confirmada ✅\n📅 {$storyFecha} {$storyHora} · 🍽️ Mesa {$storyMesa}",
                      "Aquí puedes ver y descargar tu tarjeta de confirmación:\n👉 " . route('reservas.confirmacion', $reservation->code),
                      '¡Nos vemos pronto!',
                  ];
                  $storyText = rawurlencode(implode("\n\n", $storyBlocks));
                @endphp
                <a class="btn btn-sm btn-whatsapp" target="_blank" rel="noopener"
                   href="https://wa.me/{{ $waPhone }}?text={{ $storyText }}">Enviar story</a>
              @endif
              <a class="btn btn-sm btn-outline" href="{{ route('reservas.admin.history', $reservation) }}">Historial</a>
              <button type="button" class="btn btn-sm btn-outline"
                      onclick="openEditModal({{ Js::from([
                          'action' => route('reservas.admin.update', $reservation),
                          'code' => $reservation->code,
                          'customer_name' => $reservation->customer_name,
                          'customer_phone' => $reservation->customer_phone,
                          'customer_email' => $reservation->customer_email,
                          'party_size' => $reservation->party_size,
                          'notes' => $reservation->notes,
                          'status' => $reservation->status,
                          'event_id' => $reservation->event_id,
                          'event_schedule_id' => $reservation->event_schedule_id,
                          'event_table_id' => $reservation->event_table_id,
                          'payment_amount' => $reservation->payment->amount ?? $reservation->total_amount,
                      ]) }})">Editar</button>
              <form method="POST" action="{{ route('reservas.admin.destroy', $reservation) }}"
                    onsubmit="return confirm('¿Eliminar definitivamente la reserva {{ $reservation->code }} de {{ $reservation->customer_name }}? Esto no se puede deshacer.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
              </form>
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

  <div class="modal-overlay" id="editModalOverlay">
    <div class="modal">
      <h3 id="editModalTitle">Editar reserva</h3>
      <form method="POST" id="editReservationForm">
        @csrf
        @method('PUT')

        <label for="edit_customer_name">Nombre</label>
        <input type="text" name="customer_name" id="edit_customer_name" required>

        <label for="edit_customer_phone">Teléfono</label>
        <input type="text" name="customer_phone" id="edit_customer_phone">

        <label for="edit_customer_email">Correo</label>
        <input type="email" name="customer_email" id="edit_customer_email" required>

        <label for="edit_event_schedule_id">Fecha</label>
        <select name="event_schedule_id" id="edit_event_schedule_id" required
                onchange="populateEditTables(window.editContext.eventId, parseInt(this.value, 10), null)"></select>

        <div id="edit_table_wrap">
          <label for="edit_event_table_id">Mesa</label>
          <select name="event_table_id" id="edit_event_table_id"></select>
        </div>

        <label for="edit_party_size">Personas</label>
        <input type="number" name="party_size" id="edit_party_size" min="1" max="20" required>

        <label for="edit_payment_amount">Seña (S/)</label>
        <input type="number" name="payment_amount" id="edit_payment_amount" min="0" step="0.01" required>

        <label for="edit_status">Estado</label>
        <select name="status" id="edit_status" required>
          <option value="pending">Pendiente</option>
          <option value="confirmed">Confirmada</option>
          <option value="cancelled">Cancelada</option>
          <option value="completed">Completada</option>
        </select>

        <label for="edit_notes">Notas</label>
        <textarea name="notes" id="edit_notes"></textarea>

        <div class="modal-actions">
          <button type="button" class="btn btn-sm btn-outline" onclick="closeEditModal()">Cancelar</button>
          <button type="submit" class="btn btn-sm btn-accent">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>

  <div class="modal-overlay" id="createModalOverlay">
    <div class="modal">
      <h3>Agregar reserva</h3>
      <form method="POST" id="createReservationForm" action="{{ route('reservas.admin.store') }}">
        @csrf

        <label for="create_event_id">Evento</label>
        <select name="event_id" id="create_event_id" required onchange="onCreateEventChange()">
          <option value="">Elige un evento</option>
          @foreach ($eventsForForm as $eventOption)
            <option value="{{ $eventOption['id'] }}">{{ $eventOption['name'] }}</option>
          @endforeach
        </select>

        <label for="create_schedule_id">Fecha</label>
        <select name="event_schedule_id" id="create_schedule_id" required onchange="populateCreateTables()">
          <option value="">Elige un evento primero</option>
        </select>

        <div id="create_table_wrap" style="display:none;">
          <label for="create_table_id">Mesa</label>
          <select name="event_table_id" id="create_table_id">
            <option value="">Elige una mesa</option>
          </select>
        </div>

        <label for="create_customer_name">Nombre</label>
        <input type="text" name="customer_name" id="create_customer_name" required>

        <label for="create_customer_phone">Teléfono</label>
        <input type="text" name="customer_phone" id="create_customer_phone" placeholder="987654321">

        <label for="create_customer_email">Correo</label>
        <input type="email" name="customer_email" id="create_customer_email" required>

        <label for="create_party_size">Personas</label>
        <input type="number" name="party_size" id="create_party_size" min="1" max="20" value="2" required>

        <label for="create_deposit_amount">Seña (S/, opcional)</label>
        <input type="number" name="deposit_amount" id="create_deposit_amount" min="0" step="0.01" placeholder="Si se deja vacío, se usa el total">

        <label for="create_status">Estado</label>
        <select name="status" id="create_status" required>
          <option value="confirmed" selected>Confirmada</option>
          <option value="pending">Pendiente</option>
          <option value="completed">Completada</option>
          <option value="cancelled">Cancelada</option>
        </select>

        <label for="create_notes">Notas</label>
        <textarea name="notes" id="create_notes"></textarea>

        <div class="modal-actions">
          <button type="button" class="btn btn-sm btn-outline" onclick="closeCreateModal()">Cancelar</button>
          <button type="submit" class="btn btn-sm btn-accent">Crear reserva</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    window.RESERVAS_EVENTS = @json($eventsForForm);
    window.RESERVAS_SCHEDULES = @json($schedulesForForm);
    window.RESERVAS_TABLES = @json($tablesForForm);
    window.RESERVAS_TAKEN = @json($takenForForm);

    function openEditModal(data) {
      window.editContext = {
        eventId: data.event_id,
        originalScheduleId: data.event_schedule_id,
        originalTableId: data.event_table_id,
      };
      populateEditSchedules(data.event_id, data.event_schedule_id);
      populateEditTables(data.event_id, data.event_schedule_id, data.event_table_id);

      document.getElementById('editModalTitle').textContent = 'Editar reserva ' + data.code;
      document.getElementById('editReservationForm').action = data.action;
      document.getElementById('edit_customer_name').value = data.customer_name || '';
      document.getElementById('edit_customer_phone').value = data.customer_phone || '';
      document.getElementById('edit_customer_email').value = data.customer_email || '';
      document.getElementById('edit_party_size').value = data.party_size || 1;
      document.getElementById('edit_payment_amount').value = data.payment_amount || 0;
      document.getElementById('edit_status').value = data.status || 'pending';
      document.getElementById('edit_notes').value = data.notes || '';
      document.getElementById('editModalOverlay').classList.add('open');
    }

    function populateEditSchedules(eventId, selectedScheduleId) {
      const select = document.getElementById('edit_event_schedule_id');
      select.innerHTML = '';
      window.RESERVAS_SCHEDULES
        .filter((s) => s.event_id === eventId)
        .forEach((s) => {
          const option = document.createElement('option');
          option.value = s.id;
          option.textContent = s.label;
          select.appendChild(option);
        });
      select.value = selectedScheduleId;
    }

    function populateEditTables(eventId, scheduleId, selectedTableId) {
      const select = document.getElementById('edit_event_table_id');
      select.innerHTML = '<option value="">Elige una mesa</option>';
      const ctx = window.editContext;
      const isOwnOriginalSchedule = scheduleId === ctx.originalScheduleId;
      const taken = (window.RESERVAS_TAKEN[scheduleId] || []).filter((id) => {
        // La mesa que la reserva YA tenía no cuenta como "ocupada" si
        // seguimos viendo su mismo turno original — es su propia ocupación.
        return !(isOwnOriginalSchedule && id === ctx.originalTableId);
      });
      const event = window.RESERVAS_EVENTS.find((e) => e.id === eventId);
      document.getElementById('edit_table_wrap').style.display = (event && event.has_tables) ? 'block' : 'none';

      window.RESERVAS_TABLES
        .filter((t) => t.event_id === eventId)
        .forEach((t) => {
          const isTaken = taken.includes(t.id);
          const option = document.createElement('option');
          option.value = t.id;
          option.textContent = `Mesa ${t.table_number} (${t.capacity_min}-${t.capacity_max} personas)` + (isTaken ? ' — ocupada' : '');
          option.disabled = isTaken;
          select.appendChild(option);
        });
      select.value = selectedTableId != null ? selectedTableId : '';
    }

    function closeEditModal() {
      document.getElementById('editModalOverlay').classList.remove('open');
    }

    function openCreateModal() {
      document.getElementById('createReservationForm').reset();
      document.getElementById('create_schedule_id').innerHTML = '<option value="">Elige un evento primero</option>';
      document.getElementById('create_table_id').innerHTML = '<option value="">Elige una mesa</option>';
      document.getElementById('create_table_wrap').style.display = 'none';
      document.getElementById('createModalOverlay').classList.add('open');
    }

    function closeCreateModal() {
      document.getElementById('createModalOverlay').classList.remove('open');
    }

    function onCreateEventChange() {
      const eventId = parseInt(document.getElementById('create_event_id').value, 10);
      const scheduleSelect = document.getElementById('create_schedule_id');
      scheduleSelect.innerHTML = '<option value="">Elige una fecha</option>';

      window.RESERVAS_SCHEDULES
        .filter((schedule) => schedule.event_id === eventId)
        .forEach((schedule) => {
          const option = document.createElement('option');
          option.value = schedule.id;
          option.textContent = schedule.label;
          scheduleSelect.appendChild(option);
        });

      const event = window.RESERVAS_EVENTS.find((e) => e.id === eventId);
      document.getElementById('create_table_wrap').style.display = (event && event.has_tables) ? 'block' : 'none';
      document.getElementById('create_table_id').innerHTML = '<option value="">Elige una mesa</option>';
    }

    function populateCreateTables() {
      const eventId = parseInt(document.getElementById('create_event_id').value, 10);
      const scheduleId = parseInt(document.getElementById('create_schedule_id').value, 10);
      const tableSelect = document.getElementById('create_table_id');
      tableSelect.innerHTML = '<option value="">Elige una mesa</option>';
      const taken = window.RESERVAS_TAKEN[scheduleId] || [];

      window.RESERVAS_TABLES
        .filter((table) => table.event_id === eventId)
        .forEach((table) => {
          const isTaken = taken.includes(table.id);
          const option = document.createElement('option');
          option.value = table.id;
          option.textContent = `Mesa ${table.table_number} (${table.capacity_min}-${table.capacity_max} personas)` + (isTaken ? ' — ocupada' : '');
          option.disabled = isTaken;
          tableSelect.appendChild(option);
        });
    }

    document.getElementById('createModalOverlay').addEventListener('click', function (e) {
      if (e.target === this) closeCreateModal();
    });

    @if ($errors->any() && old('event_id'))
      // Si el error vino del modal "Agregar reserva" (identificado por
      // tener event_id en el input viejo — el modal de editar no usa old()),
      // lo reabrimos con lo que el staff ya había escrito.
      openCreateModal();
      document.getElementById('create_event_id').value = @json(old('event_id'));
      onCreateEventChange();
      document.getElementById('create_schedule_id').value = @json(old('event_schedule_id'));
      populateCreateTables();
      document.getElementById('create_table_id').value = @json(old('event_table_id'));
      document.getElementById('create_customer_name').value = @json(old('customer_name'));
      document.getElementById('create_customer_phone').value = @json(old('customer_phone'));
      document.getElementById('create_customer_email').value = @json(old('customer_email'));
      document.getElementById('create_party_size').value = @json(old('party_size'));
      document.getElementById('create_deposit_amount').value = @json(old('deposit_amount'));
      document.getElementById('create_status').value = @json(old('status'));
      document.getElementById('create_notes').value = @json(old('notes'));
    @endif
  </script>

</body>
</html>
