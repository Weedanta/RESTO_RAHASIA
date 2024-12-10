function updateProfile() {
    const password = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    if (password && password !== confirmPassword) {
        alert('Konfirmasi kata sandi tidak cocok!');
        return false;
    }
    return true;
}

document.querySelector('.delete-btn').addEventListener('click', (e) => {
    if (!confirm('Apakah Anda yakin ingin menghapus akun Anda? Tindakan ini tidak dapat dibatalkan.')) {
        e.preventDefault();
    }
});