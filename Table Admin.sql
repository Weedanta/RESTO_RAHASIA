CREATE TABLE admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    admin_email VARCHAR(255) UNIQUE NOT NULL,
    admin_password VARCHAR(255) NOT NULL,
    role ENUM('Admin') NOT NULL DEFAULT 'Admin'
);

-- Masukkan data admin
INSERT INTO admin (admin_email, admin_password, role) VALUES
('manager@admin.com', MD5('themanager'), 'Admin'),
('concierge1@admin.com', MD5('246810'), 'Admin'),
('concierge2@admin.com', MD5('13579'), 'Admin');