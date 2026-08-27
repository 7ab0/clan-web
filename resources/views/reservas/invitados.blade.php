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
</style>
</head>
<body>

  <div class="topbar">
    <div>
      <h1>Invitados — Fermento</h1>
      <p class="sub">Envío de WhatsApp por etapas: intriga → aceptó → invitación con link personalizado.</p>
    </div>
    <div style="display:flex;gap:0.75rem;align-items:center;">
      <a href="{{ route('reservas.admin.index') }}" class="btn btn-outline">Reservas</a>
      <a href="{{ route('reservas.admin.clientes') }}" class="btn btn-outline">Clientes</a>
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

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Celular</th>
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
            <td>{{ $guest->name }}</td>
            <td>{{ $guest->phone ?: '—' }}</td>
            <td>
              @if ($guest->opened_at)
                <span class="ok" title="{{ $guest->opened_at->format('d/m/Y H:i') }}">✓ {{ $guest->opened_at->format('d/m/Y') }}</span>
              @else
                <span class="muted">—</span>
              @endif
            </td>
            <td>
              @if ($guest->whatsapp_sent_at)
                <span class="ok" title="{{ $guest->whatsapp_sent_at->format('d/m/Y H:i') }}">✓ {{ $guest->whatsapp_sent_at->format('d/m/Y') }}</span>
              @elseif ($guest->phone)
                <a href="{{ $guest->waLinkIntriga() }}" target="_blank" rel="noopener" class="btn btn-sm btn-whatsapp"
                   onclick="markSent('{{ route('reservas.admin.guests.mensaje1', $guest) }}')">Enviar intriga</a>
              @else
                <span class="missing-phone">Falta celular</span>
              @endif
            </td>
            <td>
              <form method="POST" action="{{ route('reservas.admin.guests.aceptar', $guest) }}">
                @csrf
                <input type="checkbox" onchange="this.form.submit()" @checked($guest->interest_confirmed_at)>
              </form>
            </td>
            <td>
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
            <td>
              @if ($guest->reservation)
                <span class="ok">✓</span>
              @else
                <span class="muted">—</span>
              @endif
            </td>
            <td>
              @if ($guest->reservation && $guest->reservation->status === 'confirmed')
                <span class="ok">✓</span>
              @else
                <span class="muted">—</span>
              @endif
            </td>
            <td class="actions-cell">
              <button type="button" class="btn btn-sm btn-outline"
                      onclick="openEditModal({{ Js::from(['id' => $guest->id, 'name' => $guest->name, 'phone' => $guest->phone]) }})">Editar</button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="9" class="empty">Todavía no hay invitados cargados.</td>
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
        <div class="modal-actions">
          <button type="button" class="btn btn-outline" onclick="closeModal()">Cancelar</button>
          <button type="submit" class="btn btn-accent">Guardar cambios</button>
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
