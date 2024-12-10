function updateAccountNumber() {
    const accountNumbers = {
        "BRI": "057901009325531",
        "BNI": "0938746488",
        "Mandiri": "9283738829287",
        "BCA": "0192828982",
        "OVO/Gopay/ShopeePay": "081945448697"
    };
    const paymentMethod = document.getElementById("payment-method").value;
    document.getElementById("account-number").value = accountNumbers[paymentMethod];
}

document.querySelector('form').addEventListener('submit', function (event) {
    const reservationInput = document.querySelector('input[name="reservation_datetime"]');
    const reservationTime = new Date(reservationInput.value);
    const currentTime = new Date();

    // Cek apakah tanggal valid dan sesuai H-24
    if (!reservationInput.value || reservationTime <= currentTime || reservationTime - currentTime < 24 * 60 * 60 * 1000) {
        alert("Reservasi harus dilakukan minimal H-1 atau 24 jam sebelum waktu reservasi.");
        event.preventDefault();
    }
});

document.querySelector('input[name="payment_proof"]').addEventListener('change', function (event) {
    const file = event.target.files[0];
    if (!file) return;

    const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
    const maxSize = 2 * 1024 * 1024; // 2MB
    const fileExtension = file.name.split('.').pop().toLowerCase();

    if (!allowedExtensions.includes(fileExtension)) {
        alert('Format file tidak didukung. Harap unggah gambar atau dokumen.');
        event.target.value = '';
    } else if (file.size > maxSize) {
        alert('Ukuran file terlalu besar. Maksimal 2MB.');
        event.target.value = '';
    }
});

function calculateBookingPrice() {
    const roomType = document.getElementById("room-type").value;
    const people = parseInt(document.getElementById("people").value) || 0;
    let pricePerPerson = 0;

    if (roomType === "Outdoor") {
        pricePerPerson = 3000;
    } else if (roomType === "Indoor") {
        pricePerPerson = 5000;
    } else if (roomType === "VIP") {
        pricePerPerson = 7500;
    }

    if (people <= 0 || isNaN(people)) {
        document.getElementById("booking-price").value = "0";
        return;
    }    

    const totalPrice = people * pricePerPerson;
    document.getElementById("booking-price").value = totalPrice.toLocaleString("id-ID");
}

// Hapus Reservasi
document.querySelectorAll('.delete-button').forEach(button => {
    button.addEventListener('click', function () {
        const reservationId = this.getAttribute('data-id');
        if (confirm('Are you sure you want to delete this reservation?')) {
            fetch('delete_reservation.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: reservationId }),
            })
            .then(response => response.json())
            .then(data => {
                console.log(data); // Debugging
                if (data.success) {
                    alert('Reservation deleted successfully!');
                    location.reload();
                } else {
                    alert('Failed to delete reservation: ' + data.error);
                }
            })
            .catch(error => console.error('Error:', error)); // Debugging
        }
    });
});

// Re-Schedule Reservasi
document.querySelectorAll('.reschedule-button').forEach(button => {
    button.addEventListener('click', function () {
        const reservationId = this.getAttribute('data-id');
        const newDate = prompt('Enter new date and time (YYYY-MM-DD HH:MM):');
        const currentTime = new Date();

        if (newDate) {
            const newDateObj = new Date(newDate);

            if (newDateObj <= currentTime || (newDateObj - currentTime) < 24 * 60 * 60 * 1000) {
                alert("Reschedule harus dilakukan minimal H-1 atau 24 jam sebelum waktu reservasi baru.");
                return;
            }

            fetch('update_reservation.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: reservationId, new_date: newDate }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Reservation rescheduled successfully!');
                    location.reload();
                } else {
                    alert('Failed to reschedule reservation: ' + data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
});