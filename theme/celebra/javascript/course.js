document.addEventListener('DOMContentLoaded', () => {

  const modal = document.querySelector('.celebra-modal');
  const backdrop = document.querySelector('.celebra-modal-backdrop');
  const iframe = document.getElementById('celebra-vimeo-player');
  const titleEl = document.getElementById('celebra-modal-title');
  const descEl = document.getElementById('celebra-modal-description');
  const completeBtn = document.getElementById('celebra-complete-btn');

  let player = null;
  let cmid = null;
  let completed = false;

  document.querySelectorAll('.celebra-module-card').forEach(card => {
    card.addEventListener('click', () => {
      cmid = card.dataset.cmid;
      completed = false;

      titleEl.textContent = card.dataset.name;
      descEl.textContent = card.dataset.description;

      iframe.src = `https://player.vimeo.com/video/${card.dataset.vimeoid}?api=1&player_id=celebra-player`;

      modal.hidden = false;
      backdrop.hidden = false;

      completeBtn.disabled = true;
      completeBtn.textContent = 'Assista 90% para concluir';

      player = new Vimeo.Player(iframe);

      player.on('timeupdate', data => {
        if (!completed && data.percent >= 0.9) {
          completed = true;
          completeBtn.disabled = false;
          completeBtn.textContent = 'Marcar como concluído';
        }
      });
    });
  });

  completeBtn.addEventListener('click', () => {
    fetch(M.cfg.wwwroot + '/theme/celebra/ajax/complete.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({ cmid })
    }).then(() => {
      location.reload();
    });
  });

  document.querySelector('.celebra-modal-close').onclick = close;
  backdrop.onclick = close;

  function close() {
    modal.hidden = true;
    backdrop.hidden = true;
    iframe.src = '';
    if (player) player.unload();
  }
});
