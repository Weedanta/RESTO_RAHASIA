// Fungsi untuk menampilkan pop-up error
function showErrorPopup(message) {
    const errorPopup = document.getElementById('error-popup');
    const errorMessage = document.getElementById('error-message');
    errorMessage.textContent = message;
    errorPopup.style.display = 'block';
}

// Fungsi untuk menutup pop-up error
function closeErrorPopup() {
    const errorPopup = document.getElementById('error-popup');
    errorPopup.style.display = 'none';
}

// Cek URL parameter
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('error') === 'user_not_found') {
    showErrorPopup("Email not found. Please check again or register.");
} else if (urlParams.get('error') === 'wrong_password') {
    showErrorPopup("Incorrect password. Please try again.");
}