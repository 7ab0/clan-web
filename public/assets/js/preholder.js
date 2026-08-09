/**
 * PRE-HOLDER CLAN — "Estamos atizando nuestros fogones"
 * ========================================================
 * Musica de fondo en loop, arrancando en mute (autoplay con sonido
 * esta bloqueado por los navegadores) con un toggle simple.
 */

document.addEventListener('DOMContentLoaded', () => {
  const audio = document.getElementById('preholderMusic');
  const toggle = document.getElementById('preholderAudioToggle');

  if (!audio || !toggle) return;

  audio.play().catch(() => {});

  toggle.addEventListener('click', () => {
    audio.muted = !audio.muted;
    if (!audio.muted) {
      audio.play().catch(() => {});
    }
    toggle.classList.toggle('is-unmuted', !audio.muted);
    toggle.setAttribute('aria-pressed', String(!audio.muted));
    toggle.setAttribute('aria-label', audio.muted ? 'Activar música' : 'Silenciar música');
  });
});
