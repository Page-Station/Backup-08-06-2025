// script.js
const modeToggle = document.querySelector('.mode-toggle');
const body = document.body;

modeToggle.addEventListener('click', () => {
    body.classList.toggle('dark-mode');

    // Save user preference to local storage
    if (body.classList.contains('dark-mode')) {
        localStorage.setItem('darkMode', 'enabled');
    } else {
        localStorage.setItem('darkMode', 'disabled');
    }
});

// Load user preference on page load
document.addEventListener('DOMContentLoaded', () => {
    const darkMode = localStorage.getItem('darkMode');
    if (darkMode === 'enabled') {
        body.classList.add('dark-mode');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('searchInput');
    const suggestionBox = document.getElementById('suggestionBox');

    input.addEventListener('input', function() {
        const query = this.value.trim();

        if (query.length < 3) {
            suggestionBox.style.display = 'none';
            suggestionBox.innerHTML = '';
            return;
        }

        fetch('php/search_suggestion.php?q=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    suggestionBox.style.display = 'none';
                    suggestionBox.innerHTML = '';
                    return;
                }

                let html = '';

                if (data.books.length > 0) {
                    data.books.forEach(book => {
                        html += `
                            <div class="suggestion-item" data-id="${book.id}">
                                <div class="suggestion-title">${book.title}</div>
                                <div class="suggestion-author">${book.author}</div>
                                <div class="suggestion-description">${book.description.substring(0, 60)}...</div>
                            </div>
                        `;
                    });
                }

                if (data.authors.length > 0) {
                    html += '<div class="suggestion-section">Penulis</div>';
                    data.authors.forEach(author => {
                        html += `
                            <div class="suggestion-author-item">
                                <div>${author}</div>
                            </div>
                        `;
                    });
                }

                suggestionBox.innerHTML = html;
                suggestionBox.style.display = 'block';

                // Klik pada item buku akan langsung ke detail
                suggestionBox.querySelectorAll('.suggestion-item').forEach(item => {
                    item.addEventListener('click', () => {
                        const id = item.getAttribute('data-id');
                        window.location.href = 'php/kategori/detail-buku.php?id=' + encodeURIComponent(id);
                    });
                });

                // Klik pada penulis bisa arahkan ke halaman pencarian dengan nama penulis
                suggestionBox.querySelectorAll('.suggestion-author-item').forEach(item => {
                    item.addEventListener('click', () => {
                        const authorName = item.textContent.trim();
                        window.location.href = 'index.php?q=' + encodeURIComponent(authorName);
                    });
                });
            })
            .catch(err => {
                console.error(err);
                suggestionBox.style.display = 'none';
                suggestionBox.innerHTML = '';
            });
    });

    // Sembunyikan suggestion saat klik di luar
    document.addEventListener('click', function(e) {
        if (!suggestionBox.contains(e.target) && e.target !== input) {
            suggestionBox.style.display = 'none';
        }
    });
});

