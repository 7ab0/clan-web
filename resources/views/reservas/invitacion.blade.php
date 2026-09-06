<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — Generar invitación VIP</title>
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
  .btn:disabled { opacity: 0.5; cursor: not-allowed; }

  .panel {
    background: #fff;
    border: 1px solid #e8e4dd;
    border-radius: 6px;
    padding: 1.5rem;
    max-width: 480px;
    margin: 0 auto 1.5rem;
  }
  .panel label { display: block; font-size: 0.8rem; color: #555; margin-bottom: 0.4rem; }
  .panel select {
    width: 100%;
    padding: 0.65rem 0.75rem;
    border: 1px solid #d8d2c4;
    border-radius: 4px;
    font-size: 0.9rem;
    font-family: inherit;
    margin-bottom: 1rem;
    background: #fff;
  }
  .panel .no-date { color: #b8860b; font-size: 0.78rem; margin-top: -0.5rem; margin-bottom: 1rem; }

  .invite-card-wrap {
    max-width: 320px;
    margin: 0 auto;
    border: 1px solid #e8e4dd;
    border-radius: 6px;
    overflow: hidden;
    background: #1A1410;
  }
  .invite-card-wrap canvas { display: block; width: 100%; height: auto; }

  .invite-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
    margin-top: 1.25rem;
    flex-wrap: wrap;
  }

  .hint { text-align: center; font-size: 0.8rem; color: #7a7365; margin-top: 0.9rem; }
</style>
</head>
<body>

  <div class="topbar">
    <div>
      <h1>Generar invitación VIP</h1>
      <p class="sub">Imagen personalizada por invitado, con la fecha real a la que fue invitado</p>
    </div>
    <div style="display:flex;gap:0.75rem;align-items:center;">
      <a href="{{ route('reservas.admin.guests') }}" class="btn btn-outline">← Volver a invitados</a>
      <form method="POST" action="{{ route('reservas.admin.logout') }}">
        @csrf
        <button type="submit" class="btn btn-dark">Cerrar sesión</button>
      </form>
    </div>
  </div>

  <div class="panel">
    <label for="guest-select">Invitado</label>
    <select id="guest-select">
      <option value="">Selecciona un invitado</option>
      @foreach ($guests as $guest)
        <option value="{{ $guest->id }}"
          data-name="{{ $guest->name }}"
          data-token="{{ $guest->token }}"
          data-date="{{ $guest->dateLabel() }}"
          data-time="{{ $guest->timeLabelForImage() }}"
          data-has-date="{{ $guest->event_schedule_id ? '1' : '0' }}">
          {{ $guest->name }}
        </option>
      @endforeach
    </select>
    <p class="no-date" id="no-date-warning" style="display:none;">
      Este invitado todavía no tiene fecha asignada — asígnasela desde el listado de invitados antes de generar su imagen, para no mostrar una fecha genérica.
    </p>
    <button type="button" id="generate-btn" class="btn btn-accent" style="width:100%;justify-content:center;">Generar</button>
  </div>

  <div id="canvas-panel" style="display:none;">
    <div class="invite-card-wrap">
      <canvas id="inviteCanvas" width="1080" height="1920"></canvas>
    </div>
    <div class="invite-actions">
      <button type="button" id="download-btn" class="btn btn-accent">Descargar imagen</button>
      <button type="button" id="share-btn" class="btn btn-outline" style="display:none;">Compartir</button>
    </div>
    <p class="hint">La invitación se manda por WhatsApp junto con el link personalizado del invitado — esta imagen es un extra visual.</p>
  </div>

<style>
  @font-face { font-family: 'Canela'; src: url('{{ asset('assets/fonts/canela/Canela-Medium.ttf') }}') format('truetype'); font-weight: 500; font-style: normal; font-display: swap; }
  @font-face { font-family: 'Inter'; src: url('{{ asset('assets/fonts/inter/Inter-Variable.ttf') }}') format('truetype'); font-weight: 100 900; font-style: normal; font-display: swap; }
  @font-face { font-family: 'Inter'; src: url('{{ asset('assets/fonts/inter/Inter-Italic-Variable.ttf') }}') format('truetype'); font-weight: 100 900; font-style: italic; font-display: swap; }
</style>

<script>
(function () {
  var canvas = document.getElementById('inviteCanvas');
  var ctx = canvas.getContext('2d');

  var bg = new Image();
  var bgLoaded = new Promise(function (resolve) { bg.onload = resolve; });
  bg.src = '{{ asset('assets/img/fermento/story-bg.jpg') }}';

  var fontsReady = Promise.all([
    document.fonts.load('600 26px Inter'),
    document.fonts.load('500 100px Canela'),
    document.fonts.load('500 24px Inter'),
    document.fonts.load('600 28px Inter'),
    document.fonts.ready,
    bgLoaded,
  ]);

  function drawTracked(text, centerX, y, spacing) {
    var chars = text.split('');
    var widths = chars.map(function (c) { return ctx.measureText(c).width; });
    var total = widths.reduce(function (a, b) { return a + b; }, 0) + spacing * (chars.length - 1);
    var x = centerX - total / 2;
    var prevAlign = ctx.textAlign;
    ctx.textAlign = 'left';
    chars.forEach(function (c, i) {
      ctx.fillText(c, x, y);
      x += widths[i] + spacing;
    });
    ctx.textAlign = prevAlign;
  }

  function drawPill(text, centerX, centerY, font, textColor, fillColor) {
    ctx.font = font;
    var textWidth = ctx.measureText(text).width;
    var paddingX = 56;
    var height = 84;
    var width = textWidth + paddingX * 2;
    var x = centerX - width / 2;
    var y = centerY - height / 2;
    var r = height / 2;

    ctx.fillStyle = fillColor;
    ctx.beginPath();
    ctx.moveTo(x + r, y);
    ctx.arcTo(x + width, y, x + width, y + height, r);
    ctx.arcTo(x + width, y + height, x, y + height, r);
    ctx.arcTo(x, y + height, x, y, r);
    ctx.arcTo(x, y, x + width, y, r);
    ctx.closePath();
    ctx.fill();

    ctx.fillStyle = textColor;
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText(text, centerX, centerY + 2);
    ctx.textBaseline = 'alphabetic';
  }

  function draw(name, dateLabel, timeLabel) {
    return fontsReady.then(function () {
      var W = canvas.width, H = canvas.height;
      ctx.clearRect(0, 0, W, H);

      var scale = Math.max(W / bg.naturalWidth, H / bg.naturalHeight);
      var sw = bg.naturalWidth * scale, sh = bg.naturalHeight * scale;
      ctx.drawImage(bg, (W - sw) / 2, H - sh, sw, sh);

      ctx.strokeStyle = 'rgba(251,177,47,0.35)';
      ctx.lineWidth = 2;
      ctx.strokeRect(48, 48, 984, 1824);

      ctx.textAlign = 'center';
      ctx.textBaseline = 'alphabetic';

      ctx.fillStyle = '#A7792A';
      ctx.font = '600 26px Inter';
      drawTracked('MOLTO × FORNO', 540, 300, 5);

      ctx.fillStyle = '#F6EEE1';
      ctx.font = '500 100px Canela';
      drawTracked('FERMENTO', 540, 420, 20);

      ctx.fillStyle = '#CCBFA8';
      ctx.font = '500 24px Inter';
      ctx.fillText('Invitación VIP personal para', 540, 480);

      ctx.fillStyle = '#F6EEE1';
      ctx.font = '500 34px Inter';
      ctx.fillText(name, 540, 528);

      ctx.fillStyle = '#A7792A';
      ctx.font = '500 24px Inter';
      drawTracked(dateLabel.toUpperCase() + ' · ' + timeLabel, 540, 1646, 2);

      ctx.fillStyle = '#7D7361';
      ctx.font = '400 22px Inter';
      ctx.fillText('Psj. Violín 101 F, San Lázaro — Arequipa', 540, 1690);

      drawPill('RESERVA TU MESA', 540, 1760, '600 28px Inter', '#1A1410', '#FBB12F');
    });
  }

  function slugify(text) {
    return text.toLowerCase()
      .normalize('NFD').replace(new RegExp('[̀-ͯ]', 'g'), '')
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/(^-|-$)/g, '');
  }

  function checkShareSupport() {
    var shareBtn = document.getElementById('share-btn');
    try {
      var testFile = new File(['x'], 'test.png', { type: 'image/png' });
      if (navigator.canShare && navigator.canShare({ files: [testFile] })) {
        shareBtn.style.display = 'inline-flex';
        return;
      }
    } catch (e) {}
    shareBtn.style.display = 'none';
  }

  var select = document.getElementById('guest-select');
  var noDateWarning = document.getElementById('no-date-warning');

  select.addEventListener('change', function () {
    var option = select.options[select.selectedIndex];
    noDateWarning.style.display = (option && option.value && option.dataset.hasDate === '0') ? 'block' : 'none';
  });

  document.getElementById('generate-btn').addEventListener('click', function () {
    var option = select.options[select.selectedIndex];
    if (! option || ! option.value) {
      alert('Elige un invitado primero.');
      return;
    }
    draw(option.dataset.name, option.dataset.date, option.dataset.time).then(function () {
      document.getElementById('canvas-panel').style.display = 'block';
      checkShareSupport();
    });
  });

  document.getElementById('download-btn').addEventListener('click', function () {
    var option = select.options[select.selectedIndex];
    var link = document.createElement('a');
    link.download = 'fermento-vip-' + slugify(option.dataset.name) + '.png';
    link.href = canvas.toDataURL('image/png');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  });

  document.getElementById('share-btn').addEventListener('click', function () {
    var option = select.options[select.selectedIndex];
    canvas.toBlob(function (blob) {
      var file = new File([blob], 'fermento-vip-' + slugify(option.dataset.name) + '.png', { type: 'image/png' });
      navigator.share({
        files: [file],
        title: 'Fermento — Invitación VIP',
        text: 'Invitación personal a Fermento',
      }).catch(function () {});
    }, 'image/png');
  });
})();
</script>

</body>
</html>
