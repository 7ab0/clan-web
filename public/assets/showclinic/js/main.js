/**
 * SHOWCLINIC ANIVERSARIO — LÓGICA PRINCIPAL
 * ==========================================
 * - Pantalla de invitación personalizada por código
 * - Countdown hasta el evento
 * - Navegación suave
 */

// ========== CONFIGURACIÓN ==========

const EVENT_DATE = new Date('2026-08-22T19:00:00').getTime();
const GATE_EL = document.getElementById('gate');
const MAIN_EL = document.getElementById('main');
const GATE_NAME = document.getElementById('gateName');
const GATE_ENTER = document.getElementById('gateEnter');

// ========== LEER CÓDIGO DE INVITADO ==========

function getGuestFromURL() {
  const params = new URLSearchParams(window.location.search);
  const code = params.get('inv');
  
  if (!code) {
    return null;
  }

  const guest = GUEST_LIST.find(g => g.code.toUpperCase() === code.toUpperCase());
  return guest || null;
}

// ========== INICIALIZAR PANTALLA DE INVITACIÓN ==========

function initGate() {
  const guest = getGuestFromURL();

  if (guest) {
    GATE_NAME.textContent = `Bienvenido/a, ${guest.name}`;
  }

  GATE_ENTER.addEventListener('click', () => {
    GATE_EL.style.display = 'none';
    MAIN_EL.hidden = false;
    
    // Trigger animations
    document.querySelector('.nav').style.animation = 'fadeIn 0.6s ease-out';
    document.querySelector('.hero').style.animation = 'fadeIn 0.6s ease-out';
  });
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
  initGate();
});

// ========== ACCESIBILIDAD ==========

if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
  document.documentElement.style.scrollBehavior = 'auto';
  document.querySelectorAll('[style*="animation"]').forEach(el => {
    el.style.animation = 'none';
  });
}

console.log('ShowClinic Aniversario — Landing Page Ready');
