<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CLAN — En mantenimiento</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background: #1f1f1f;
    color: #f5f1e4;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
  }
  .box {
    width: 100%;
    max-width: 360px;
    background: #2a2a2a;
    border: 1px solid #444;
    border-radius: 8px;
    padding: 2.5rem 2rem;
  }
  h1 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.35rem;
  }
  p.sub {
    font-size: 0.85rem;
    color: #b9ac91;
    margin-bottom: 1.75rem;
  }
  label {
    display: block;
    font-size: 0.8rem;
    color: #ccc;
    margin-bottom: 0.4rem;
  }
  input[type="password"] {
    width: 100%;
    padding: 0.7rem 0.85rem;
    border-radius: 4px;
    border: 1px solid #555;
    background: #222;
    color: #fff;
    font-size: 0.95rem;
    margin-bottom: 1rem;
  }
  input[type="password"]:focus {
    outline: none;
    border-color: #ba9a63;
  }
  button {
    width: 100%;
    padding: 0.75rem;
    border: none;
    border-radius: 4px;
    background: #ba9a63;
    color: #1f1f1f;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
  }
  button:hover {
    background: #a3691f;
    color: #fff;
  }
  .error {
    background: rgba(200, 60, 60, 0.15);
    border: 1px solid rgba(200, 60, 60, 0.4);
    color: #ff9d9d;
    font-size: 0.85rem;
    padding: 0.6rem 0.8rem;
    border-radius: 4px;
    margin-bottom: 1rem;
  }
</style>
</head>
<body>
  <div class="box">
    <h1>Estamos en mantenimiento</h1>
    <p class="sub">Volvé en un rato — o entrá con la palabra clave.</p>

    @if ($errors->any())
      <div class="error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('maintenance.unlock') }}">
      @csrf
      <label for="keyword">Palabra clave</label>
      <input type="password" id="keyword" name="keyword" autofocus required>
      <button type="submit">Entrar</button>
    </form>
  </div>
</body>
</html>
