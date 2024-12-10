-- Tabel untuk menyimpan approved reservations
CREATE TABLE approved_reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    reservation_datetime DATETIME NOT NULL,
    people INT NOT NULL,
    room_type VARCHAR(50),
    payment_method VARCHAR(50),
    payment_proof VARCHAR(255),
    additional_request TEXT
);

-- Tabel untuk menyimpan rejected reservations
CREATE TABLE rejected_reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    reservation_datetime DATETIME NOT NULL,
    people INT NOT NULL,
    room_type VARCHAR(50),
    payment_method VARCHAR(50),
    payment_proof VARCHAR(255),
    additional_request TEXT
);