/**
 * ======================================================
 * COURSE PAGE — Instituto Celebra
 * ======================================================
 */

document.addEventListener('DOMContentLoaded', () => {

  /* =========================
     ELEMENTOS
     ========================= */

  const modal = document.querySelector('.celebra-modal');
  const backdrop = document.querySelector('.celebra-modal-backdrop');
  const modalTitle = document.querySelector('.celebra-modal-title');
  const videoContainer = document.querySelector('.celebra-modal-video');
  const completeBtn = document.querySelector('.celebra-complete-btn');

  let player = null;
  let watchedPercent = 0;
  let currentModuleId = null;

  /* =========================
     ABRIR MODAL
     ========================= */

  document.querySelectorAll('.celebra-module-card').forEach(card => {
    card.addEventListener('click', () => openModule(card));
  });

  function openModule(card) {
    currentModuleId = card.dataset.moduleid;

    const title = card.querySelector('strong')?.innerText || 'Conteúdo';
    const vimeoId = card.dataset.vimeoId;
    const duration = Number(card.dataset.duration || 0);

    modalTitle.textContent = title;
    completeBtn.disabled = true;
    watchedPercent = 0;

    openModal();
    loadVimeo(vimeoId, duration);
  }

  /* =========================
     MODAL OPEN / CLOSE
     ========================= */

  function openModal() {
    modal.style.display = 'flex';
    backdrop.style.display = 'block';
    document.body.style.overflow = 'hidden';
  }

  function closeModal() {
    modal.style.display = 'none';
    backdrop.style.display = 'none';
    document.body.style.overflow = '';
    destroyPlayer();
  }

  backdrop?.addEventListener('click', closeModal);
  document.querySelector('.celebra-modal-close')
    ?.addEventListener('click', closeModal);

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeModal();
  });

  /* =========================
     VIMEO PLAYER
     ========================= */

  function loadVimeo(vimeoId, duration) {
    videoContainer.innerHTML = `
      <iframe
        src="https://player.vimeo.com/video/${vimeoId}?autoplay=1&muted=0"
        allow="autoplay; fullscreen"
        allowfullscreen>
      </iframe>
    `;

    if (!window.Vimeo) {
      loadVimeoSDK(() => initPlayer(duration));
    } else {
      initPlayer(duration);
    }
  }

  function loadVimeoSDK(callback) {
    const script = document.createElement('script');
    script.src = 'https://player.vimeo.com/api/player.js';
    script.onload = callback;
    document.body.appendChild(script);
  }

  function initPlayer(duration) {
    const iframe = videoContainer.querySelector('iframe');
    player = new Vimeo.Player(iframe);

    player.on('timeupdate', data => {
      watchedPercent = Math.round((data.seconds / duration) * 100);
      checkCompletion();
    });
  }

  function destroyPlayer() {
    if (player) {
      player.unload();
      player = null;
    }
    videoContainer.innerHTML = '';
  }

  /* =========================
     REGRA 90%
     ========================= */

  function checkCompletion() {
    if (watchedPercent >= 90) {
      completeBtn.disabled = false;
      completeBtn.classList.add('is-unlocked');
    }
  }

  /* =========================
     CONCLUIR MÓDULO (FRONT)
     ========================= */

  completeBtn?.addEventListener('click', () => {
    if (completeBtn.disabled) return;

    markModuleAsCompleted(currentModuleId);
    closeModal();
  });

  function markModuleAsCompleted(moduleId) {

        const cmid = moduleId; // aqui moduleId = cmid do Moodle

        fetch(M.cfg.wwwroot + '/theme/celebra/ajax/complete_activity.php', {
            method: 'POST',
            headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
            cmid: cmid,
            sesskey: M.cfg.sesskey
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'ok') {
            updateUIAfterCompletion(cmid);
            }
        })
        .catch(err => console.error(err));
    }

    function updateUIAfterCompletion(cmid) {
        const card = document.querySelector(
            `.celebra-module-card[data-moduleid="${cmid}"]`
        );

        if (!card) return;

        card.classList.add('is-completed');

        const bar = card.querySelector('.celebra-module-progress span');
        if (bar) bar.style.width = '100%';
    }

});
