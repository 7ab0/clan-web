<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
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
  .topbar-actions {
    display: flex;
    gap: 0.75rem;
    align-items: center;
  }
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
  }
  .btn-dark {
    background: #2a2a2a;
    color: #f5f1e4;
  }
  .btn-dark:hover { background: #a3691f; }
  .btn-accent {
    background: #a3691f;
    color: #fff;
  }
  .btn-accent:hover { background: #8a5819; }
  .btn-outline {
    background: #fff;
    border: 1px solid #d8d2c4;
    color: #2a2a2a;
  }
  .btn-outline:hover { border-color: #a3691f; color: #a3691f; }
  .btn-danger {
    background: #fbe4e1;
    color: #c0392b;
  }
  .btn-danger:hover { background: #f6cec8; }
  .btn-sm {
    padding: 0.35rem 0.65rem;
    font-size: 0.78rem;
    border-radius: 4px;
  }

  .flash {
    background: #e5f4e6;
    border: 1px solid #bfe3c1;
    color: #2e7d32;
    padding: 0.75rem 1rem;
    border-radius: 6px;
    font-size: 0.85rem;
    margin-bottom: 1.25rem;
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
  tbody tr.guest-row {
    cursor: pointer;
  }
  tbody tr.guest-row:hover {
    background: #fbf9f4;
  }
  tbody tr.detail-row {
    display: none;
    background: #faf8f3;
  }
  tbody tr.detail-row.open {
    display: table-row;
  }
  tbody tr.detail-row td {
    white-space: normal;
    padding: 1rem 1.25rem;
  }
  .detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 0.85rem;
  }
  .detail-grid dt {
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #7a7365;
    margin-bottom: 0.2rem;
  }
  .detail-grid dd {
    font-size: 0.88rem;
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
  .link-cell {
    display: flex;
    align-items: center;
    gap: 0.4rem;
  }
  .link-cell .link-text {
    font-family: 'Courier New', monospace;
    font-size: 0.78rem;
    color: #7a7365;
    max-width: 160px;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .copy-btn {
    background: #f5f1e4;
    border: 1px solid #d8d2c4;
    border-radius: 4px;
    font-size: 0.72rem;
    padding: 0.3rem 0.5rem;
    cursor: pointer;
  }
  .copy-btn:hover { border-color: #a3691f; }
  .actions-cell {
    display: flex;
    gap: 0.4rem;
    flex-wrap: wrap;
  }
  .actions-cell .btn-whatsapp {
    background: #e5f4e6;
    color: #1f7a3f;
  }
  .actions-cell .btn-whatsapp:hover { background: #cdeccf; }
  .actions-cell .btn-whatsapp[disabled] {
    background: #f0f0f0;
    color: #999;
    cursor: not-allowed;
  }

  /* Modal */
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
  .modal-backdrop.open {
    display: flex;
  }
  .modal {
    background: #fff;
    border-radius: 8px;
    width: 100%;
    max-width: 420px;
    padding: 1.75rem;
  }
  .modal h2 {
    font-size: 1.1rem;
    margin-bottom: 1.25rem;
  }
  .modal label {
    display: block;
    font-size: 0.8rem;
    color: #555;
    margin-bottom: 0.35rem;
    margin-top: 0.9rem;
  }
  .modal input {
    width: 100%;
    padding: 0.6rem 0.75rem;
    border: 1px solid #d8d2c4;
    border-radius: 4px;
    font-size: 0.9rem;
  }
  .modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.6rem;
    margin-top: 1.5rem;
  }

  /* Plantilla de mensaje */
  .template-box {
    background: #fff;
    border: 1px solid #e8e4dd;
    border-radius: 6px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.25rem;
  }
  .template-box summary {
    cursor: pointer;
    font-size: 0.85rem;
    font-weight: 600;
    color: #2a2a2a;
    list-style: none;
  }
  .template-box summary::-webkit-details-marker { display: none; }
  .template-box summary::before {
    content: '▶';
    display: inline-block;
    margin-right: 0.5rem;
    font-size: 0.65rem;
    color: #a3691f;
    transition: transform 0.15s;
  }
  .template-box[open] summary::before {
    transform: rotate(90deg);
  }
  .template-box .template-body {
    margin-top: 0.85rem;
  }
  .template-box textarea {
    width: 100%;
    min-height: 90px;
    padding: 0.65rem 0.75rem;
    border: 1px solid #d8d2c4;
    border-radius: 4px;
    font-size: 0.88rem;
    font-family: inherit;
    resize: vertical;
  }
  .template-box .template-hint {
    font-size: 0.76rem;
    color: #7a7365;
    margin-top: 0.4rem;
  }
  .template-box .template-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.6rem;
    margin-top: 0.6rem;
  }
  .template-box .template-status {
    font-size: 0.76rem;
    color: #2e7d32;
    align-self: center;
    margin-right: auto;
  }
</style>
</head>
<body>

  <div class="topbar">
    <div>
      <h1>Invitados — ShowClinic</h1>
      <p class="sub">Panel de administración del aniversario</p>
    </div>
    <div class="topbar-actions">
      <button type="button" class="btn btn-outline" onclick="copyAllLinks(this)">Copiar todos los links</button>
      <button type="button" class="btn btn-accent" onclick="openAddModal()">+ Agregar invitado</button>
      <form method="POST" action="{{ route('showclinic.admin.logout') }}">
        @csrf
        <button type="submit" class="btn btn-dark">Cerrar sesión</button>
      </form>
    </div>
  </div>

  <details class="template-box" id="template-box">
    <summary>Mensaje de WhatsApp (plantilla)</summary>
    <div class="template-body">
      <textarea id="wa-template" spellcheck="false"></textarea>
      <p class="template-hint">Se usa al presionar "WhatsApp" en cada invitado. Puedes usar <strong>{nombre}</strong> y <strong>{link}</strong> — se reemplazan automáticamente por los datos de cada invitado. Los cambios se guardan solos en este navegador.</p>
      <div class="template-actions">
        <span class="template-status" id="template-status"></span>
        <button type="button" class="btn btn-sm btn-outline" onclick="resetWaTemplate()">Restablecer</button>
      </div>
    </div>
  </details>

  @if (session('status'))
    <div class="flash">{{ session('status') }}</div>
  @endif

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
          <th>Celular</th>
          <th>Acomp. permitidos</th>
          <th><a href="{{ $sortLink('status') }}">RSVP{{ $arrow('status') }}</a></th>
          <th>Pre-invitación</th>
          <th>Invitación</th>
          <th>Link</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($guests as $guest)
          @php
            $link = route('showclinic', ['inv' => $guest->code]);
            $waPhone = $guest->phone ? '51' . preg_replace('/\D+/', '', $guest->phone) : null;
          @endphp
          <tr class="guest-row"
              data-detail-target="detail-{{ $guest->id }}"
              data-guest-name="{{ $guest->name }}"
              data-guest-link="{{ $link }}">
            <td>{{ $guest->name }}</td>
            <td class="code">{{ $guest->code }}</td>
            <td>{{ $guest->phone ?: '—' }}</td>
            <td>{{ $guest->allowed_companions }}</td>
            <td><span class="badge {{ $guest->status }}">{{ ucfirst($guest->status) }}</span></td>
            <td onclick="event.stopPropagation()">
              <div class="link-cell">
                <input type="checkbox"
                       data-guest-toggle
                       data-guest-id="{{ $guest->id }}"
                       data-field="pre_invitation_sent"
                       @checked($guest->pre_invitation_sent)>
                <button type="button" class="copy-btn" title="Copiar link" onclick="copyLink(this, '{{ $link }}')">Copiar</button>
              </div>
            </td>
            <td onclick="event.stopPropagation()">
              <input type="checkbox"
                     data-guest-toggle
                     data-guest-id="{{ $guest->id }}"
                     data-field="invitation_sent"
                     @checked($guest->invitation_sent)>
            </td>
            <td onclick="event.stopPropagation()">
              <div class="link-cell">
                <span class="link-text">{{ $link }}</span>
                <button type="button" class="copy-btn" onclick="copyLink(this, '{{ $link }}')">Copiar</button>
              </div>
            </td>
            <td class="actions-cell" onclick="event.stopPropagation()">
              <button type="button"
                      class="btn btn-sm btn-whatsapp"
                      @if(! $waPhone) disabled title="Este invitado no tiene un celular válido" @endif
                      onclick="sendWhatsapp('{{ $waPhone }}', {{ Js::from($guest->name) }}, {{ Js::from($link) }})">WhatsApp</button>
              <button type="button"
                      class="btn btn-sm btn-outline"
                      onclick='openEditModal({{ Js::from([
                          "id" => $guest->id,
                          "name" => $guest->name,
                          "phone" => $guest->phone,
                          "allowed_companions" => $guest->allowed_companions,
                      ]) }})'>Editar</button>
              <form method="POST" action="{{ route('showclinic.admin.guests.destroy', $guest) }}"
                    onsubmit="return confirm('¿Eliminar a {{ $guest->name }}? Esta acción no se puede deshacer.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
              </form>
            </td>
          </tr>
          <tr class="detail-row" id="detail-{{ $guest->id }}">
            <td colspan="9">
              <dl class="detail-grid">
                <div>
                  <dt>Profesión</dt>
                  <dd>{{ $guest->profession ?: '—' }}</dd>
                </div>
                <div>
                  <dt>Cumplido</dt>
                  <dd>{{ $guest->compliment ?: '—' }}</dd>
                </div>
                <div>
                  <dt>Acompañante (RSVP)</dt>
                  <dd>
                    @if ($guest->plus_one)
                      Sí{{ $guest->companion_name ? ' — ' . $guest->companion_name : '' }}
                    @else
                      No
                    @endif
                  </dd>
                </div>
                <div>
                  <dt>Preferencias</dt>
                  <dd>{{ $guest->preferences ?: '—' }}</dd>
                </div>
                <div>
                  <dt>Notas</dt>
                  <dd>{{ $guest->notes ?: '—' }}</dd>
                </div>
                <div>
                  <dt>Confirmado el</dt>
                  <dd>{{ $guest->confirmed_at?->format('d/m/Y H:i') ?? '—' }}</dd>
                </div>
              </dl>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="9" class="empty">No se encontraron invitados con esos filtros.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Modal: agregar invitado -->
  <div class="modal-backdrop" id="add-modal-backdrop">
    <div class="modal">
      <h2>Agregar invitado</h2>
      <form method="POST" action="{{ route('showclinic.admin.guests.store') }}">
        @csrf
        <label for="add-name">Nombre</label>
        <input type="text" id="add-name" name="name" required>
        <label for="add-phone">Celular</label>
        <input type="text" id="add-phone" name="phone">
        <label for="add-allowed-companions">Acompañantes permitidos</label>
        <input type="number" id="add-allowed-companions" name="allowed_companions" min="0" value="0">
        <div class="modal-actions">
          <button type="button" class="btn btn-outline" onclick="closeModal('add-modal-backdrop')">Cancelar</button>
          <button type="submit" class="btn btn-accent">Guardar</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal: editar invitado -->
  <div class="modal-backdrop" id="edit-modal-backdrop">
    <div class="modal">
      <h2>Editar invitado</h2>
      <form method="POST" id="edit-form" action="">
        @csrf
        @method('PUT')
        <label for="edit-name">Nombre</label>
        <input type="text" id="edit-name" name="name" required>
        <label for="edit-phone">Celular</label>
        <input type="text" id="edit-phone" name="phone">
        <label for="edit-allowed-companions">Acompañantes permitidos</label>
        <input type="number" id="edit-allowed-companions" name="allowed_companions" min="0">
        <div class="modal-actions">
          <button type="button" class="btn btn-outline" onclick="closeModal('edit-modal-backdrop')">Cancelar</button>
          <button type="submit" class="btn btn-accent">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>

<script>
  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

  // Expandir/colapsar fila de detalle al hacer clic en la fila (no en checkboxes/links/botones)
  document.querySelectorAll('tr.guest-row').forEach(function (row) {
    row.addEventListener('click', function () {
      const target = document.getElementById(row.dataset.detailTarget);
      if (target) target.classList.toggle('open');
    });
  });

  // Checkboxes de pre-invitación / invitación: guardan por AJAX sin recargar
  document.querySelectorAll('[data-guest-toggle]').forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
      const guestId = checkbox.dataset.guestId;
      const field = checkbox.dataset.field;
      const value = checkbox.checked;

      fetch(`/showclinic/admin/guests/${guestId}/toggle`, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
        },
        body: JSON.stringify({ field: field, value: value }),
      })
        .then(function (res) {
          if (!res.ok) throw new Error('No se pudo guardar');
          return res.json();
        })
        .catch(function () {
          checkbox.checked = !value;
          alert('No se pudo guardar el cambio. Intenta de nuevo.');
        });
    });
  });

  function copyLink(button, link) {
    navigator.clipboard.writeText(link).then(function () {
      const original = button.textContent;
      button.textContent = '¡Copiado!';
      setTimeout(function () { button.textContent = original; }, 1500);
    });
  }

  function copyAllLinks(button) {
    const rows = document.querySelectorAll('tr.guest-row');
    if (!rows.length) {
      alert('No hay invitados para copiar con los filtros actuales.');
      return;
    }

    const lines = Array.from(rows).map(function (row) {
      return `${row.dataset.guestName}: ${row.dataset.guestLink}`;
    });

    navigator.clipboard.writeText(lines.join('\n')).then(function () {
      const original = button.textContent;
      button.textContent = `¡${rows.length} links copiados!`;
      setTimeout(function () { button.textContent = original; }, 1800);
    }).catch(function () {
      alert('No se pudo copiar. Intenta de nuevo.');
    });
  }

  // Plantilla del mensaje de WhatsApp — se guarda en este navegador (localStorage)
  const WA_TEMPLATE_DEFAULT = 'Hola {nombre}, tienes una invitación especial para nuestro Aniversario ShowClinic. Ábrela aquí: {link}';
  const WA_TEMPLATE_STORAGE_KEY = 'showclinic_admin_wa_template';
  const waTemplateInput = document.getElementById('wa-template');
  const waTemplateStatus = document.getElementById('template-status');
  let waTemplateStatusTimeout = null;

  function getWaTemplate() {
    return localStorage.getItem(WA_TEMPLATE_STORAGE_KEY) || WA_TEMPLATE_DEFAULT;
  }

  function renderWaMessage(name, link) {
    return getWaTemplate()
      .replaceAll('{nombre}', name)
      .replaceAll('{link}', link);
  }

  if (waTemplateInput) {
    waTemplateInput.value = getWaTemplate();

    waTemplateInput.addEventListener('input', function () {
      localStorage.setItem(WA_TEMPLATE_STORAGE_KEY, waTemplateInput.value);
      if (waTemplateStatus) {
        waTemplateStatus.textContent = 'Guardado';
        clearTimeout(waTemplateStatusTimeout);
        waTemplateStatusTimeout = setTimeout(function () {
          waTemplateStatus.textContent = '';
        }, 1200);
      }
    });
  }

  function resetWaTemplate() {
    localStorage.removeItem(WA_TEMPLATE_STORAGE_KEY);
    if (waTemplateInput) waTemplateInput.value = WA_TEMPLATE_DEFAULT;
  }

  function sendWhatsapp(phone, name, link) {
    if (!phone) return;
    const message = renderWaMessage(name, link);
    const url = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
    window.open(url, '_blank');
  }

  function openAddModal() {
    document.getElementById('add-modal-backdrop').classList.add('open');
  }

  function openEditModal(guest) {
    const form = document.getElementById('edit-form');
    form.action = `/showclinic/admin/guests/${guest.id}`;
    document.getElementById('edit-name').value = guest.name || '';
    document.getElementById('edit-phone').value = guest.phone || '';
    document.getElementById('edit-allowed-companions').value = guest.allowed_companions ?? 0;
    document.getElementById('edit-modal-backdrop').classList.add('open');
  }

  function closeModal(id) {
    document.getElementById(id).classList.remove('open');
  }

  document.querySelectorAll('.modal-backdrop').forEach(function (backdrop) {
    backdrop.addEventListener('click', function (e) {
      if (e.target === backdrop) backdrop.classList.remove('open');
    });
  });
</script>

</body>
</html>
