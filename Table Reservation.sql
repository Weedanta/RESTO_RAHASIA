Tabel Reservasi

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    user_id INT NOT NULL, 
    name VARCHAR(100) NOT NULL, 
    email VARCHAR(100) NOT NULL, 
    phone VARCHAR(15) NOT NULL, 
    reservation_datetime DATETIME NOT NULL, 
    people INT NOT NULL, 
    room_type ENUM('Indoor', 'Outdoor', 'VIP') NOT NULL, 
    payment_method ENUM('BRI', 'BNI', 'Mandiri', 'BCA', 'OVO/Gopay/ShopeePay') NOT NULL, 
    additional_request TEXT, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

Ubah Default Value Timestamp

ALTER TABLE `reservations` MODIFY `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE reservations ADD CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;