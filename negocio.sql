-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-09-2024 a las 18:34:02
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `negocio`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_cat` int(4) NOT NULL,
  `nombrec` varchar(25) NOT NULL,
  `descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_cat`, `nombrec`, `descripcion`) VALUES
(3, 'MTB', 'Bicicleta rigida de montaña'),
(6, 'Ruta', 'Tipo de bicicleta para ciclismo de ruta'),
(7, 'Downhill', 'Bicicleta con doble suspension');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `ID_Producto` int(5) NOT NULL,
  `Nombre` varchar(20) NOT NULL,
  `Marca` varchar(20) NOT NULL,
  `Rodada` varchar(5) NOT NULL,
  `Cantidad` int(6) NOT NULL,
  `Foto` varchar(300) NOT NULL,
  `id_cat` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`ID_Producto`, `Nombre`, `Marca`, `Rodada`, `Cantidad`, `Foto`, `id_cat`) VALUES
(17, 'Specialized Tarmac S', 'Specialized ', '27', 4, 'imagenesc/Venta1.webp', 6),
(18, 'Cannondale SuperSix ', 'Cannondale ', '29', 10, 'imagenesc/Venta2.jpg', 3),
(19, 'Santa Cruz V10', 'Santa Cruz', '26', 6, 'imagenesc/Venta3.jpg', 7),
(20, 'Trek Émonda SLR 9', 'Trek ', '29', 6, 'imagenesc/Venta4.jpg', 6),
(21, 'Giant TCR Advanced P', 'Giant ', '29', 6, 'imagenesc/Venta5.webp', 7),
(22, 'Scott Addict RC', 'Scott ', '29', 3, 'imagenesc/Venta6', 7),
(23, 'Pinarello Dogma F12', 'Pinarello ', '27', 5, 'imagenesc/Venta7.jpg', 6),
(24, 'Canyon Aeroad CF SLX', 'Canyon ', '26', 5, 'imagenesc/Venta8.jpg', 3),
(25, 'Orbea Orca OMX', 'Orbea ', '26', 2, 'imagenesc/Venta9.jfif', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipousuarios`
--

CREATE TABLE `tipousuarios` (
  `idtipo` int(4) NOT NULL,
  `tipousu` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `tipousuarios`
--

INSERT INTO `tipousuarios` (`idtipo`, `tipousu`) VALUES
(1, 'Administrador'),
(2, 'Gerente'),
(3, 'Emplado'),
(4, 'Cliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idusuario` int(4) NOT NULL,
  `NomUsu` varchar(30) NOT NULL,
  `ApaUsu` varchar(20) NOT NULL,
  `AmaUsu` varchar(20) NOT NULL,
  `Correo` varchar(100) NOT NULL,
  `Contra` varchar(18) NOT NULL,
  `telefono` varchar(10) NOT NULL,
  `ruta` varchar(100) NOT NULL,
  `idtipo` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idusuario`, `NomUsu`, `ApaUsu`, `AmaUsu`, `Correo`, `Contra`, `telefono`, `ruta`, `idtipo`) VALUES
(17, 'santi', 'ALONSO', 'REYES', 'h@gmail.com', '34567', '345', '992a6d18b2a148cf20d9014c3524aa11', 1),
(19, 'Arath', 'Saavedra', 'Cabrera', 'saav@gmail.com', '123', '2481557389', '202cb962ac59075b964b07152d234b70', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_cat`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`ID_Producto`),
  ADD KEY `id_cat` (`id_cat`);

--
-- Indices de la tabla `tipousuarios`
--
ALTER TABLE `tipousuarios`
  ADD PRIMARY KEY (`idtipo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idusuario`),
  ADD KEY `idtipo` (`idtipo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_cat` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `ID_Producto` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `tipousuarios`
--
ALTER TABLE `tipousuarios`
  MODIFY `idtipo` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idusuario` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_cat`) REFERENCES `categorias` (`id_cat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`idtipo`) REFERENCES `tipousuarios` (`idtipo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
