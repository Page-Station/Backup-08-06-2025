// screen-protection.js

// 1. Mencegah klik kanan
document.addEventListener('contextmenu', e => {
    e.preventDefault();
    alert("Klik kanan dinonaktifkan.");
});

// 2. Mencegah shortcut screenshot & dev tools
document.addEventListener('keydown', e => {
    // Cegah Print Screen
    if (e.key === "PrintScreen") {
        navigator.clipboard.writeText("Screenshot dinonaktifkan!");
        alert("Screenshot dinonaktifkan.");
        return false;
    }

    // Ctrl+S / Ctrl+U / Ctrl+Shift+I / F12
    if (
        (e.ctrlKey && (e.key === 's' || e.key === 'u')) || // Ctrl+S atau Ctrl+U
        (e.ctrlKey && e.shiftKey && e.key.toLowerCase() === 'i') || // Ctrl+Shift+I
        (e.key === 'F12')
    ) {
        e.preventDefault();
        alert("Akses ini dibatasi!");
        return false;
    }
});

// 3. Loop untuk mendeteksi screen recorder (deteksi aktif PrintScreen buffer)
setInterval(() => {
    const input = document.createElement("input");
    input.setAttribute("value", "");
    input.style.position = "absolute";
    input.style.opacity = "0";
    document.body.appendChild(input);
    input.select();
    document.execCommand("copy");

    navigator.clipboard.readText().then(text => {
        if (text !== "") {
            console.log("Screenshot mungkin dilakukan");
            navigator.clipboard.writeText("Screenshot dicegah!");
        }
    });

    document.body.removeChild(input);
}, 3000);
