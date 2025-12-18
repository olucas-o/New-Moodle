document.addEventListener('celebra:courseProgressUpdated', function (e) {

  const { courseid, progress } = e.detail;

  // encontra o card do curso
  const courseCard = document.querySelector(
    `.celebra-poster[data-courseid="${courseid}"]`
  );

  if (!courseCard) return;

  // barra de progresso
  const bar = courseCard.querySelector('.celebra-progress span');
  const label = courseCard.querySelector('.celebra-progress-label');

  if (bar) {
    bar.style.width = progress + '%';
  }

  if (label) {
    label.textContent = `${progress}% concluído`;
  }

});
