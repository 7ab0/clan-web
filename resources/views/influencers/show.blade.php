<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — {{ $influencer->name }}</title>
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
  .flash.error { background: #fbe4e1; border-color: #f3c6bf; color: #c0392b; }

  .grid {
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 1.5rem;
    align-items: start;
  }

  .panel {
    background: #fff;
    border: 1px solid #e8e4dd;
    border-radius: 6px;
    padding: 1.5rem;
  }
  .panel h2 { font-size: 1rem; margin-bottom: 1rem; }

  label { display: block; font-size: 0.8rem; color: #555; margin-bottom: 0.35rem; margin-top: 0.9rem; }
  label:first-of-type { margin-top: 0; }
  input, select, textarea {
    width: 100%;
    padding: 0.6rem 0.75rem;
    border: 1px solid #d8d2c4;
    border-radius: 4px;
    font-size: 0.9rem;
    font-family: inherit;
  }
  .row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
  .form-actions { margin-top: 1.25rem; }

  .link-box {
    background: #faf8f3;
    border: 1px solid #e8e4dd;
    border-radius: 4px;
    padding: 0.75rem;
    font-family: 'Courier New', monospace;
    font-size: 0.8rem;
    word-break: break-all;
    margin-bottom: 0.75rem;
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

  .meta-row { font-size: 0.82rem; color: #7a7365; margin-top: 0.4rem; }
  .muted { color: #7a7365; font-weight: 400; }

  .table-wrap {
    background: #fff;
    border: 1px solid #e8e4dd;
    border-radius: 6px;
    overflow-x: auto;
    margin-top: 1.5rem;
  }
  table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
  th, td { text-align: left; padding: 0.6rem 0.85rem; border-bottom: 1px solid #eee; white-space: nowrap; }
  th {
    background: #faf8f3;
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #7a7365;
  }
  .empty { padding: 2rem 1rem; text-align: center; color: #7a7365; }
  .wrap { white-space: normal; max-width: 220px; }

  @media (max-width: 900px) {
    .grid { grid-template-columns: 1fr; }
  }
</style>
</head>
<body>

  <div class="topbar">
    <div>
      <h1>{{ $influencer->name }}</h1>
      <p class="sub">
        <span class="badge {{ $influencer->status }}">{{ ucfirst($influencer->status) }}</span>
        @if ($influencer->is_test) <span class="muted">· prueba</span> @endif
      </p>
    </div>
    <div style="display:flex;gap:0.75rem;align-items:center;">
      <a href="{{ route('influencers.admin.index') }}" class="btn btn-outline">← Volver</a>
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
    <div class="flash error">{{ $errors->first() }}</div>
  @endif

  <div class="grid">
    <div>
      <div class="panel">
        <h2>Link personalizado</h2>
        <div class="link-box">{{ route('fermento', $influencer->token) }}</div>
        <div class="meta-row">
          @if ($influencer->opened_at) Abrió el link el {{ $influencer->opened_at->format('d/m/Y H:i') }}<br>@endif
          @if ($influencer->confirmed_at) Confirmó el {{ $influencer->confirmed_at->format('d/m/Y H:i') }}<br>@endif
          @if ($influencer->attended_at) Check-in el {{ $influencer->attended_at->format('d/m/Y H:i') }}@endif
        </div>
      </div>

      <div class="ak-height-20" style="height:1.5rem;"></div>

      <div class="panel">
        <h2>Editar datos</h2>
        <form method="POST" action="{{ route('influencers.admin.update', $influencer) }}">
          @csrf
          @method('PATCH')
          <label for="edit-name">Nombre</label>
          <input type="text" id="edit-name" name="name" value="{{ old('name', $influencer->name) }}" required>
          <div class="row2">
            <div>
              <label for="edit-instagram">Instagram</label>
              <input type="text" id="edit-instagram" name="instagram_handle" value="{{ old('instagram_handle', $influencer->instagram_handle) }}">
            </div>
            <div>
              <label for="edit-tiktok">TikTok</label>
              <input type="text" id="edit-tiktok" name="tiktok_handle" value="{{ old('tiktok_handle', $influencer->tiktok_handle) }}">
            </div>
          </div>
          <div class="row2">
            <div>
              <label for="edit-phone">Celular</label>
              <input type="text" id="edit-phone" name="phone" value="{{ old('phone', $influencer->phone) }}">
            </div>
            <div>
              <label for="edit-followers">Seguidores (aprox.)</label>
              <input type="number" id="edit-followers" name="followers_count" min="0" value="{{ old('followers_count', $influencer->followers_count) }}">
            </div>
          </div>
          <label for="edit-status">Estado</label>
          <select id="edit-status" name="status">
            <option value="invitado" @selected($influencer->status === 'invitado')>Invitado</option>
            <option value="confirmado" @selected($influencer->status === 'confirmado')>Confirmado</option>
            <option value="declinado" @selected($influencer->status === 'declinado')>Declinado</option>
            <option value="asistio" @selected($influencer->status === 'asistio')>Asistió (check-in)</option>
          </select>
          <label for="edit-notes">Notas</label>
          <textarea id="edit-notes" name="notes" rows="4">{{ old('notes', $influencer->notes) }}</textarea>
          <div class="form-actions">
            <button type="submit" class="btn btn-accent">Guardar cambios</button>
          </div>
        </form>
      </div>
    </div>

    <div>
      <div class="panel">
        <h2>Agregar post / story / reel / video</h2>
        <form method="POST" action="{{ route('influencers.admin.posts.store', $influencer) }}" enctype="multipart/form-data">
          @csrf
          <div class="row2">
            <div>
              <label for="post-type">Tipo</label>
              <select id="post-type" name="type" required>
                <option value="post">Post</option>
                <option value="story">Story</option>
                <option value="reel">Reel</option>
                <option value="video">Video</option>
              </select>
            </div>
            <div>
              <label for="post-published">Fecha de publicación</label>
              <input type="date" id="post-published" name="published_at" required>
            </div>
          </div>
          <label for="post-url">Link</label>
          <input type="text" id="post-url" name="url" placeholder="https://instagram.com/...">
          <label for="post-screenshot">Captura (opcional)</label>
          <input type="file" id="post-screenshot" name="screenshot" accept="image/*">
          <div class="row2">
            <div>
              <label for="post-views">Vistas</label>
              <input type="number" id="post-views" name="views" min="0">
            </div>
            <div>
              <label for="post-likes">Likes</label>
              <input type="number" id="post-likes" name="likes" min="0">
            </div>
          </div>
          <div class="row2">
            <div>
              <label for="post-shares">Compartidos</label>
              <input type="number" id="post-shares" name="shares" min="0">
            </div>
            <div>
              <label for="post-comments">Comentarios</label>
              <input type="number" id="post-comments" name="comments" min="0">
            </div>
          </div>
          <label for="post-notes">Notas</label>
          <textarea id="post-notes" name="notes" rows="2"></textarea>
          <div class="form-actions">
            <button type="submit" class="btn btn-accent">Agregar post</button>
          </div>
        </form>
      </div>

      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Tipo</th>
              <th>Fecha</th>
              <th>Link</th>
              <th>Captura</th>
              <th>Vistas</th>
              <th>Likes</th>
              <th>Comp.</th>
              <th>Coment.</th>
              <th>Notas</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($influencer->posts as $post)
              <tr>
                <td>{{ ucfirst($post->type) }}</td>
                <td>{{ $post->published_at->format('d/m/Y') }}</td>
                <td>@if ($post->url)<a href="{{ $post->url }}" target="_blank" rel="noopener">Ver</a>@else — @endif</td>
                <td>@if ($post->screenshot_path)<a href="{{ asset('storage/' . $post->screenshot_path) }}" target="_blank" rel="noopener">Ver</a>@else — @endif</td>
                <td>{{ $post->views !== null ? number_format($post->views) : '—' }}</td>
                <td>{{ $post->likes !== null ? number_format($post->likes) : '—' }}</td>
                <td>{{ $post->shares !== null ? number_format($post->shares) : '—' }}</td>
                <td>{{ $post->comments !== null ? number_format($post->comments) : '—' }}</td>
                <td class="wrap">{{ $post->notes ?: '—' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="9" class="empty">Todavía no hay posts cargados.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</body>
</html>
