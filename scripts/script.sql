-- DB engine: MySQL


-- En caso de bugiarse, runear uno por uno
create database ibi;
use ibi;
create table habitaciones(
id int primary key,
cc varchar(10),
estado varchar(30)
);
-- Ejecutar uno por uno (esto lo puedes runnear de putazo)
INSERT INTO Habitaciones (id, cc, estado) VALUES
(1, '1,1', 'Disponible'),
(2, '1,2', 'Disponible'),
(3, '1,3', 'Disponible'),
(4, '1,4', 'Disponible'),
(5, '1,5', 'Disponible'),
(6, '2,1', 'Disponible'),
(7, '2,2', 'Disponible'),
(8, '2,3', 'Disponible'),
(9, '2,4', 'Disponible'),
(10, '2,5', 'Disponible'),
(11, '3,1', 'Disponible'),
(12, '3,2', 'Disponible'),
(13, '3,3', 'Disponible'),
(14, '3,4', 'Disponible'),
(15, '3,5', 'Disponible'),
(16, '4,1', 'Disponible'),
(17, '4,2', 'Disponible'),
(18, '4,3', 'Disponible'),
(19, '4,4', 'Disponible'),
(20, '4,5', 'Disponible'),
(21, '5,1', 'Disponible'),
(22, '5,2', 'Disponible'),
(23, '5,3', 'Disponible'),
(24, '5,4', 'Disponible'),
(25, '5,5', 'Disponible'),
(26, '6,1', 'Disponible'),
(27, '6,2', 'Disponible'),
(28, '6,3', 'Disponible'),
(29, '6,4', 'Disponible'),
(30, '6,5', 'Disponible');