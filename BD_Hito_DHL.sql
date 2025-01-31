drop database if exists hito1_progr;
create database hito1_progr;
use hito1_progr;
create table usuarios (
    nombre varchar(50),
    correo varchar(30) primary key,
    edad int,
    plan ENUM('Básico', 'Estándar', 'Premium'),
    paquete text,
    duracion ENUM('Mensual', 'Anual'),
    precio FLOAT (10.2)
); 

INSERT INTO usuarios (nombre, correo, edad, plan, paquete, duracion, precio) 
VALUES 
('David Hilario', 'david.hilario@gmail.com', 25, 'estandar', 'deporte,cine', 'anual', 28.97),
('María Gómez', 'maria.gomez@gmail.com', 17, 'basico', 'infantil', 'mensual', 14.98),
('Nicolas Otamendi', 'nicootamendi@gmail.com', 37, 'premium', 'deporte,cine,infantil', 'anual', 37.96);

SELECT * FROM usuarios;