/**
 * SHOWCLINIC ANIVERSARIO — LÓGICA PRINCIPAL
 * ==========================================
 * - Pantalla de invitación (el nombre del invitado ya viene renderizado
 *   desde el servidor, resuelto por código contra la base de datos)
 * - Countdown hasta el evento
 * - Navegación suave
 * - Formulario de confirmación de asistencia
 */

// ========== CONFIGURACIÓN ==========

const EVENT_DATE = new Date('2026-08-22T19:00:00').getTime();
const PREHOLDER_EL = document.getElementById('preholder');
const MAIN_EL = document.getElementById('main');
const BG_MUSIC = document.getElementById('bgMusic');
const AUDIO_TOGGLE = document.getElementById('audioToggle');

// ========== ENTRADA AL SITIO (SIN GATE INTERMEDIO) ==========

function revealMain() {
  MAIN_EL.hidden = false;
  document.querySelector('.nav').style.animation = 'fadeIn 0.6s ease-out';
  document.querySelector('.hero').style.animation = 'fadeIn 0.6s ease-out';
}

const BG_MUSIC_VOLUME = 0.35; // volumen tenue cuando el invitado activa el sonido

function startBackgroundAudio() {
  // Arranca de una al cargar, en mute (autoplay con sonido esta
  // bloqueado por los navegadores). El boton de audio ya es visible
  // desde el primer render, sin esperar ninguna interaccion previa.
  // El volumen queda bajo desde el inicio: cuando el invitado la activa
  // con el boton, empieza tenue en vez de a todo volumen.
  if (BG_MUSIC) {
    BG_MUSIC.volume = BG_MUSIC_VOLUME;
    BG_MUSIC.play().catch(() => {});
  }
}

// ========== PRE-HOLDER (HISTORIA DE 3 PANTALLAS) ==========

function initPreholder() {
  if (!PREHOLDER_EL) return;

  const screens = Array.from(PREHOLDER_EL.querySelectorAll('.preholder-screen'));
  const prevZone = document.getElementById('preholderPrev');
  const nextZone = document.getElementById('preholderNext');
  let index = 0;

  function render() {
    screens.forEach((screen, i) => screen.classList.toggle('is-active', i === index));
  }

  function next() {
    if (index >= screens.length - 1) {
      PREHOLDER_EL.hidden = true;
      revealMain();
      return;
    }
    index += 1;
    render();
  }

  function prev() {
    if (index === 0) return;
    index -= 1;
    render();
  }

  nextZone.addEventListener('click', next);
  prevZone.addEventListener('click', prev);
  render();
}

// ========== AUDIO DE FONDO ==========

function initAudioToggle() {
  if (!BG_MUSIC || !AUDIO_TOGGLE) return;

  AUDIO_TOGGLE.addEventListener('click', () => {
    BG_MUSIC.muted = !BG_MUSIC.muted;
    if (!BG_MUSIC.muted) {
      BG_MUSIC.play().catch(() => {});
    }
    AUDIO_TOGGLE.classList.toggle('is-unmuted', !BG_MUSIC.muted);
    AUDIO_TOGGLE.setAttribute('aria-pressed', String(!BG_MUSIC.muted));
    AUDIO_TOGGLE.setAttribute('aria-label', BG_MUSIC.muted ? 'Activar música' : 'Silenciar música');
  });
}

// ========== CONFIRMACIÓN DE ASISTENCIA ==========

function initConfirmForm() {
  const confirmFormWrap = document.getElementById('confirmForm');
  if (!confirmFormWrap) return;

  const confirmChoice = document.querySelector('.confirm-choice');
  const choiceYes = document.getElementById('choiceYes');
  const choiceNo = document.getElementById('choiceNo');
  const confirmDetails = document.getElementById('confirmDetails');
  const declineForm = document.getElementById('declineForm');
  const plusOneToggle = document.getElementById('plusOneToggle');
  const companionField = document.getElementById('companionField');
  const updateBtn = document.getElementById('updateResponseBtn');
  const confirmSummary = document.getElementById('confirmSummary');

  if (choiceYes) {
    choiceYes.addEventListener('click', () => {
      confirmChoice.hidden = true;
      confirmDetails.hidden = false;
    });
  }

  if (choiceNo) {
    choiceNo.addEventListener('click', () => {
      declineForm.submit();
    });
  }

  if (plusOneToggle) {
    plusOneToggle.addEventListener('change', () => {
      companionField.hidden = !plusOneToggle.checked;
    });
  }

  if (updateBtn) {
    updateBtn.addEventListener('click', () => {
      confirmSummary.hidden = true;
      confirmFormWrap.hidden = false;
    });
  }
}

// ========== COUNTDOWN ==========

function updateCountdown() {
  const now = new Date().getTime();
  const distance = EVENT_DATE - now;

  if (distance < 0) {
    document.getElementById('countdown').innerHTML = `
      <div class="cd-box" style="grid-column: 1/-1; text-align: center;">
        <p style="font-size: 1.2rem; color: var(--primary);">¡El evento ya comenzó!</p>
      </div>
    `;
    return;
  }

  const days = Math.floor(distance / (1000 * 60 * 60 * 24));
  const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  const seconds = Math.floor((distance % (1000 * 60)) / 1000);

  document.getElementById('cdDays').textContent = String(days).padStart(2, '0');
  document.getElementById('cdHours').textContent = String(hours).padStart(2, '0');
  document.getElementById('cdMin').textContent = String(minutes).padStart(2, '0');
  document.getElementById('cdSec').textContent = String(seconds).padStart(2, '0');
}

setInterval(updateCountdown, 1000);
updateCountdown();

// ========== CARRUSEL DE FOTOS ==========

function initPhotoCarousel() {
  document.querySelectorAll('.photo-carousel').forEach(carousel => {
    const track = carousel.querySelector('.carousel-track');
    const prevBtn = carousel.querySelector('.carousel-prev');
    const nextBtn = carousel.querySelector('.carousel-next');
    if (!track) return;

    function scrollByOne(direction) {
      const slide = track.querySelector('.carousel-slide');
      const gap = 24; // 1.5rem
      const amount = (slide ? slide.getBoundingClientRect().width : 400) + gap;
      track.scrollBy({ left: amount * direction, behavior: 'smooth' });
    }

    if (prevBtn) prevBtn.addEventListener('click', () => scrollByOne(-1));
    if (nextBtn) nextBtn.addEventListener('click', () => scrollByOne(1));
  });
}

// ========== SMOOTH SCROLL NAV LINKS ==========

document.querySelectorAll('.nav-links a').forEach(link => {
  link.addEventListener('click', (e) => {
    e.preventDefault();
    const target = document.querySelector(link.getAttribute('href'));
    if (target) {
      target.scrollIntoView({ behavior: 'smooth' });
    }
  });
});

// ========== LAZY LOAD IMAGES ==========

if ('IntersectionObserver' in window) {
  const imageObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const img = entry.target;
        img.src = img.dataset.src || img.src;
        img.classList.add('loaded');
        observer.unobserve(img);
      }
    });
  });

  document.querySelectorAll('img[data-src]').forEach(img => {
    imageObserver.observe(img);
  });
}

// ========== PARTÍCULAS DORADAS ==========

function initParticles() {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  document.querySelectorAll('.particles').forEach(container => {
    const count = 14;
    for (let i = 0; i < count; i++) {
      const particle = document.createElement('span');
      particle.className = 'particle';
      particle.style.left = `${Math.random() * 100}%`;
      particle.style.setProperty('--drift', `${(Math.random() * 60 - 30).toFixed(0)}px`);
      particle.style.animationDuration = `${(10 + Math.random() * 8).toFixed(1)}s`;
      particle.style.animationDelay = `${(Math.random() * 10).toFixed(1)}s`;
      container.appendChild(particle);
    }
  });
}

// ========== STAGGER ANIMATIONS ==========

function observeReveals() {
  if ('IntersectionObserver' in window) {
    const revealObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.animationPlayState = 'running';
          revealObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });

    document.querySelectorAll('.reveal').forEach(el => {
      el.style.animationPlayState = 'paused';
      revealObserver.observe(el);
    });
  }
}

document.addEventListener('DOMContentLoaded', () => {
  observeReveals();
  startBackgroundAudio();
  initPreholder();
  initAudioToggle();
  initParticles();
  initConfirmForm();
  initPhotoCarousel();

  // Sin invitado (sin ?inv= valido) no hay pre-holder que mostrar:
  // se entra directo al sitio principal.
  if (!PREHOLDER_EL) {
    revealMain();
  }
});

// ========== ACCESIBILIDAD ==========

if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
  document.documentElement.style.scrollBehavior = 'auto';
  document.querySelectorAll('[style*="animation"]').forEach(el => {
    el.style.animation = 'none';
  });
}

console.log('ShowClinic Aniversario — Landing Page Ready');
