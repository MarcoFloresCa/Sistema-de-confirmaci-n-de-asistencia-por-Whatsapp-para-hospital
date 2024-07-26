-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-07-2024 a las 07:16:44
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
-- Base de datos: `somecloud`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_incompletos`
--

CREATE TABLE `datos_incompletos` (
  `ID` int(11) NOT NULL,
  `rut` varchar(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `tipo_error` varchar(50) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `envio_mensaje`
--

CREATE TABLE `envio_mensaje` (
  `ID` int(11) NOT NULL,
  `ID_Usuario` int(11) DEFAULT NULL,
  `Hora_carga` datetime DEFAULT NULL,
  `Fecha_envio` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hora`
--

CREATE TABLE `hora` (
  `ID` int(11) NOT NULL,
  `Rut_Paciente` varchar(10) DEFAULT NULL,
  `Profesional` varchar(50) DEFAULT NULL,
  `Tipo_Atencion` varchar(30) DEFAULT NULL,
  `Especialidad` varchar(50) DEFAULT NULL,
  `Dia` varchar(10) DEFAULT NULL,
  `Hora_Agandada` varchar(6) DEFAULT NULL,
  `Asistencia` varchar(20) DEFAULT NULL,
  `Fecha_envio` datetime DEFAULT NULL,
  `ID_Envio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paciente`
--

CREATE TABLE `paciente` (
  `Rut` varchar(11) NOT NULL,
  `Nombre` varchar(50) DEFAULT NULL,
  `Telefono` varchar(11) DEFAULT NULL,
  `Corre_electronico` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `datos_incompletos`
--
ALTER TABLE `datos_incompletos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `rut` (`rut`);

--
-- Indices de la tabla `envio_mensaje`
--
ALTER TABLE `envio_mensaje`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `hora`
--
ALTER TABLE `hora`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Rut_Paciente` (`Rut_Paciente`),
  ADD KEY `ID_Envio` (`ID_Envio`);

--
-- Indices de la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`Rut`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `datos_incompletos`
--
ALTER TABLE `datos_incompletos`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=378;

--
-- AUTO_INCREMENT de la tabla `hora`
--
ALTER TABLE `hora`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4944;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `hora`
--
ALTER TABLE `hora`
  ADD CONSTRAINT `hora_ibfk_1` FOREIGN KEY (`Rut_Paciente`) REFERENCES `paciente` (`Rut`),
  ADD CONSTRAINT `hora_ibfk_2` FOREIGN KEY (`ID_Envio`) REFERENCES `envio_mensaje` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
