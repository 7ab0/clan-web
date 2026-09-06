<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Admin — Influencers</title>
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

  .summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
  }
  .card {
    background: #fff;
    border: 1px solid #e8e4dd;
    border-radius: 6px;
    padding: 1rem 1.15rem;
  }
  .card .num { font-size: 1.6rem; font-weight: 700; }
  .card .lbl { font-size: 0.78rem; color: #7a7365; text-transform: uppercase; letter-spacing: 0.03em; }

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
  .muted { color: #7a7365; }
  .link-copy {
    display: inline-flex; align-items: center; gap: 0.4rem;
    font-family: 'Courier New', monospace; font-size: 0.78rem; color: #555;
  }
  .badge {
    display: inline-block;
    padding: 0.25rem 0.65rem;
    border-radius: 100px;
    font-size: 0.78rem;
    font-weight: 600;
  }
  .badge.invitado { background: #e3edfb; color: #1c4e91; }
  .badge.confirmado { background: #e5f4e6; color: #2e7d32; }
  .badge.declinado { background: #fbe4e1; color: #c0392b; }
  .badge.asistio { background: #f7e6c4; color: #8a5819; }

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
  .modal { background: #fff; border-radius: 8px; width: 100%; max-width: 440px; padding: 1.75rem; max-height: 90vh; overflow-y: auto; }
  .modal h2 { font-size: 1.1rem; margin-bottom: 1.25rem; }
  .modal label { display: block; font-size: 0.8rem; color: #555; margin-bottom: 0.35rem; margin-top: 0.9rem; }
  .modal input, .modal textarea {
    width: 100%;
    padding: 0.6rem 0.75rem;
    border: 1px solid #d8d2c4;
    border-radius: 4px;
    font-size: 0.9rem;
    font-family: inherit;
  }
  .modal .row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
  .modal-actions { display: flex; justify-content: flex-end; gap: 0.6rem; margin-top: 1.5rem; }

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
    tbody td.actions-cell { justify-content: flex-start; flex-wrap: wrap; }
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
      <h1>Influencers — Pre-Cóctel Fermento</h1>
      <p class="sub">Martes 1 de septiembre 2026 · 7:00 p. m. · Psj. Violín 101 F, San Lázaro — Arequipa</p>
    </div>
    <div style="display:flex;gap:0.75rem;align-items:center;">
      <button type="button" class="btn btn-accent" onclick="openCreateModal()">+ Agregar influencer</button>
      <a href="{{ route('influencers.admin.invitacion') }}" class="btn btn-outline">Generar invitación</a>
      <form method="POST" action="{{ route('influencers.admin.logout') }}">
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
    <div class="card"><div class="num">{{ $summary['total'] }}</div><div class="lbl">Total</div></div>
    <div class="card"><div class="num">{{ $summary['invitado'] }}</div><div class="lbl">Invitados</div></div>
    <div class="card"><div class="num">{{ $summary['confirmado'] }}</div><div class="lbl">Confirmados</div></div>
    <div class="card"><div class="num">{{ $summary['asistio'] }}</div><div class="lbl">Asistieron</div></div>
    <div class="card"><div class="num">{{ $summary['declinado'] }}</div><div class="lbl">Declinaron</div></div>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Instagram</th>
          <th>TikTok</th>
          <th>Seguidores</th>
          <th>Estado</th>
          <th>Link</th>
          <th>Posts / Vistas</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($influencers as $influencer)
          <tr>
            <td data-label="Nombre">{{ $influencer->name }}</td>
            <td data-label="Instagram">{{ $influencer->instagram_handle ?: '—' }}</td>
            <td data-label="TikTok">{{ $influencer->tiktok_handle ?: '—' }}</td>
            <td data-label="Seguidores">{{ $influencer->followers_count !== null ? number_format($influencer->followers_count) : '—' }}</td>
            <td data-label="Estado"><span class="badge {{ $influencer->status }}">{{ ucfirst($influencer->status) }}</span></td>
            <td data-label="Link">
              <span class="link-copy">
                <code id="link-{{ $influencer->id }}">{{ route('fermento', $influencer->token) }}</code>
                <button type="button" class="btn btn-sm btn-outline" onclick="copyLink('{{ $influencer->id }}')">Copiar</button>
              </span>
            </td>
            <td data-label="Posts / Vistas">{{ $influencer->posts_count }} posts · {{ number_format($influencer->posts_sum_views ?? 0) }} vistas</td>
            <td class="actions-cell">
              <a href="{{ route('influencers.admin.show', $influencer) }}" class="btn btn-sm btn-outline">Ver detalle</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="empty">Todavía no hay influencers cargados.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="modal-backdrop" id="create-modal-backdrop">
    <div class="modal">
      <h2>Agregar influencer</h2>
      <form method="POST" action="{{ route('influencers.admin.store') }}">
        @csrf
        <label for="create-name">Nombre</label>
        <input type="text" id="create-name" name="name" required>
        <div class="row2">
          <div>
            <label for="create-instagram">Instagram</label>
            <input type="text" id="create-instagram" name="instagram_handle" placeholder="@usuario">
          </div>
          <div>
            <label for="create-tiktok">TikTok</label>
            <input type="text" id="create-tiktok" name="tiktok_handle" placeholder="@usuario">
          </div>
        </div>
        <div class="row2">
          <div>
            <label for="create-phone">Celular</label>
            <input type="text" id="create-phone" name="phone" placeholder="987654321">
          </div>
          <div>
            <label for="create-followers">Seguidores (aprox.)</label>
            <input type="number" id="create-followers" name="followers_count" min="0">
          </div>
        </div>
        <label for="create-notes">Notas</label>
        <textarea id="create-notes" name="notes" rows="3"></textarea>
        <label style="display:flex;align-items:center;gap:0.5rem;margin-top:0.9rem;">
          <input type="checkbox" name="is_test" value="1" style="width:auto;">
          <span style="font-size:0.82rem;color:#555;">Es de prueba</span>
        </label>
        <div class="modal-actions">
          <button type="button" class="btn btn-outline" onclick="closeCreateModal()">Cancelar</button>
          <button type="submit" class="btn btn-accent">Agregar</button>
        </div>
      </form>
    </div>
  </div>

<script>
  function openCreateModal() {
    document.getElementById('create-modal-backdrop').classList.add('open');
  }
  function closeCreateModal() {
    document.getElementById('create-modal-backdrop').classList.remove('open');
  }
  document.getElementById('create-modal-backdrop').addEventListener('click', function (e) {
    if (e.target === this) closeCreateModal();
  });

  function copyLink(id) {
    var text = document.getElementById('link-' + id).textContent;
    navigator.clipboard.writeText(text).catch(function () {});
  }

  @if ($errors->any())
    openCreateModal();
  @endif
</script>

</body>
</html>
