CREATE TABLE usuario (
    idusuario INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    usuario VARCHAR(100) NOT NULL,
    clave VARCHAR(255) NOT NULL,
    perfil VARCHAR(255) NOT NULL,
    ubicacionfoto VARCHAR(255) NOT NULL,
    fechaalta DATE NOT NULL,
    fechabaja DATE
);
