-- Tabla para las editoriales
CREATE TABLE Editorial (
    id_editorial INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL
);

-- Tabla para los géneros literarios
CREATE TABLE Genero (
    id_genero INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL
);

-- Tabla para los usuarios
CREATE TABLE Usuario (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    contraseña VARCHAR(255) NOT NULL
);

-- Tabla para los préstamos de libros
CREATE TABLE Prestamo (
    id_prestamo INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    id_libro INT,
    Titulo varchar (100),
    fecha_prestamo DATE NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario),
    FOREIGN KEY (id_libro) REFERENCES Libro(id_libro)
);

-- Tabla para registrar devoluciones
CREATE TABLE Devolucion (
    id_devolucion INT PRIMARY KEY AUTO_INCREMENT,
    id_prestamo INT,
    fecha_devolucion DATE NOT NULL,
    FOREIGN KEY (id_prestamo) REFERENCES Prestamo(id_prestamo)
);

CREATE TABLE Autor (
    ID_Autor INT PRIMARY KEY,
    Nombre VARCHAR(50),
    Apellido VARCHAR(50)
);

CREATE TABLE Libro (
    ID_Libro INT PRIMARY KEY,
    Titulo VARCHAR(100),
    Año_publicacion INT,
    ID_Genero VARCHAR(50),
    ID_Registro VARCHAR(50)
);

CREATE TABLE autor_libro (
    ID_Autor INT,
    ID_Libro INT,
    ISBN varchar(50),
    PRIMARY KEY (ID_Autor, ID_Libro),
    FOREIGN KEY (ID_Autor) REFERENCES Autor(ID_Autor),
    FOREIGN KEY (ID_Libro) REFERENCES Libro(ID_Libro)
);

CREATE TABLE Miembro (
    ID_Miembro INT PRIMARY KEY,
    Nombre VARCHAR(50),
    Apellido VARCHAR(50),
    Fecha_Registro DATE,
    correo_electronico VARCHAR(30),
    telefono VARCHAR(20)
);