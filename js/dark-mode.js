// Ambil elemen mode-toggle (moon icon)
const modeToggle = document.querySelector('.mode-toggle');

// Menambahkan link CSS dark mode secara dinamis
const darkModeLink = document.createElement('link');
darkModeLink.rel = 'stylesheet';
darkModeLink.href = '../css/dark-mode.css';  // path ke file dark-mode.css

modeToggle.addEventListener('click', function() {
  // Toggle kelas untuk dark mode
  document.body.classList.toggle('dark-mode');

  // Memuat/menghapus file CSS dark-mode
  if (document.body.classList.contains('dark-mode')) {
    document.head.appendChild(darkModeLink);
  } else {
    document.head.removeChild(darkModeLink);
  }
});


document.addEventListener("DOMContentLoaded", function() {
    const modeToggle = document.querySelector('.mode-toggle');
    modeToggle.addEventListener('click', function() {
      document.body.classList.toggle('dark-mode');
    });
  });