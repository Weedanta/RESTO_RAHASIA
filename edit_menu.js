// Menampilkan form untuk menambah menu baru
function showAddForm() {
    const formModal = document.getElementById('form-modal');
    document.getElementById('menu-form').reset();
    document.getElementById('menu-action').value = 'add';
    document.getElementById('menu-id').value = '';
    formModal.classList.remove('hidden'); // Pastikan class "hidden" dihapus
    formModal.style.display = 'flex'; // Tampilkan modal
}

// Menampilkan form untuk mengedit menu yang dipilih
function showEditForm(id) {
    const formModal = document.getElementById('form-modal');
    const row = document.querySelector(`tr[data-id="${id}"]`);
    
    // Isi nilai form dengan data menu yang dipilih
    document.getElementById('menu-action').value = 'edit';
    document.getElementById('menu-id').value = id;
    document.getElementById('menu-category').value = row.querySelector('.category').textContent.trim();
    document.getElementById('menu-name').value = row.querySelector('.name').textContent.trim();
    document.getElementById('menu-price').value = row.querySelector('.price').textContent.replace(/\./g, '').trim();
    document.getElementById('menu-description').value = row.querySelector('.description').textContent.trim();

    formModal.classList.remove('hidden'); // Pastikan class "hidden" dihapus
    formModal.style.display = 'flex'; // Tampilkan modal
}

// Menyembunyikan form
function hideForm() {
    const formModal = document.getElementById('form-modal');
    formModal.classList.add('hidden'); // Tambahkan kembali class "hidden"
    formModal.style.display = 'none'; // Sembunyikan modal
}

// Hapus menu dari database
function deleteMenu(id) {
    if (confirm('Are you sure you want to delete this menu?')) {
        fetch('manage_menu.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=delete&id=${id}`
        }).then(() => location.reload());
    }
}