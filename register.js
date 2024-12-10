// Fungsi untuk menampilkan pop-up error
function showErrorPopup(message) {
    const errorPopup = document.getElementById('error-popup');
    const errorMessage = document.querySelector('.popup-message');
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
if (urlParams.get('error') === 'password_mismatch') {
    showErrorPopup("The password does not match!");
}