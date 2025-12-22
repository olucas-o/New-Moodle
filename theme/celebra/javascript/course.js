document.addEventListener('DOMContentLoaded', () => {
  const modal = document.querySelector('.celebra-modal');
  const backdrop = document.querySelector('.celebra-modal-backdrop');
  const playerFrame = document.getElementById('celebra-vimeo-player');
  const titleEl = document.getElementById('celebra-modal-title');
  const descEl = document.getElementById('celebra-modal-description');
  const closeBtn = document.querySelector('.celebra-modal-close');

  function closeModal() {
    modal.hidden = true;
    backdrop.hidden = true;
    playerFrame.src = '';
  }

  document.querySelectorAll('.celebra-module-card').forEach(card => {
    card.addEventListener('click', () => {
      const vimeoId = card.dataset.vimeoid;
      titleEl.textContent = card.dataset.name;
      descEl.textContent = card.dataset.description || '';

      playerFrame.src = `https://player.vimeo.com/video/${vimeoId}?api=1`;

      modal.hidden = false;
      backdrop.hidden = false;
    });
  });

  closeBtn.addEventListener('click', closeModal);
  backdrop.addEventListener('click', closeModal);
});
