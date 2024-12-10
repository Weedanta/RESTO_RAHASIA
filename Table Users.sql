CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,           -- Kolom ID sebagai primary key
    nama VARCHAR(100) NOT NULL,                  -- Kolom untuk nama pengguna
    jenis_kelamin ENUM('Laki-laki', 'Perempuan') NOT NULL,  -- Kolom jenis kelamin
    alamat TEXT NOT NULL,                        -- Kolom alamat
    deskripsi TEXT,                              -- Kolom deskripsi (opsional)
    email VARCHAR(100) NOT NULL UNIQUE,          -- Kolom email dengan constraint UNIQUE
    nomor_telepon VARCHAR(20),                   -- Kolom nomor telepon
    password VARCHAR(255) NOT NULL,              -- Kolom password yang sudah di-hash
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Kolom timestamp untuk mencatat waktu pembuatan
);