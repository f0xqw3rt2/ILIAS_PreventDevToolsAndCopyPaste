/**
 * prevent.js
 * Blockiert Rechtsklick, Copy&Paste (Strg/Cmd + C, V, X), DevTools-Shortcuts (F12, Ctrl+Shift+I/J/C),
 * sowie das Markieren von Text.
 */
document.addEventListener('contextmenu', function (e) {
    e.preventDefault();
}, false);

document.addEventListener('keydown', function (e) {
    var isMac = navigator.platform.toUpperCase().indexOf("MAC") !== -1;
    var ctrlOrCmd = isMac ? e.metaKey : e.ctrlKey;

    // Strg/Cmd + C, V, X verhindern
    if (ctrlOrCmd && ["c", "v", "x"].includes(e.key.toLowerCase())) {
        e.preventDefault();
    }

    // DevTools Shortcuts verhindern
    if (e.key === "F12") {
        e.preventDefault();
    }

    if (ctrlOrCmd && e.shiftKey && ["i", "j", "c"].includes(e.key.toLowerCase())) {
        e.preventDefault();
    }
}, false);

// Verhindert das Markieren von Text per Mausziehen:
document.addEventListener('selectstart', function (e) {
    e.preventDefault();
}, false);

// Deaktiviert Doppelklick- und Dreifachklick-Markierung:
document.addEventListener('mousedown', function (e) {
    if (e.detail > 1) {
        e.preventDefault();
    }
}, false);
