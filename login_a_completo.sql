-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-06-2025 a las 17:21:44
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `login_a`
-- CREATE DATABASE: login_a;
-- USE login_a;
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contraseña` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`id_admin`, `nombre`, `usuario`, `contraseña`) VALUES
(1, 'nadi', 'admin', '$2y$10$wz/hF2jp2FvAgUF3pZPgnOTNDHIekAfYi788iU8cIyfjcdG3n6wzC'),
(2, 'admin', 'nadi', '$2y$10$JOTyzvuDPlZW4oqrNXMf7uT3lKhe4ynpRuUY/yRO5SWmwYvTapFUG'),
(3, 'nadi', 'jhon', '$2y$10$jm.d0w7cTU3gPk7p5CUHpuhxTEnRJ7JCi/2fG3dHCdFiQP6LCfUIy'),
(4, 'admin', 'deyvid', '$2y$10$aSrv7ljeF/BAjcNgVegk7eC5fvWqQnaTb3gemkRZZmbUvHdrJVx96'),
(7, 'admin1', 'jhon2', '$2y$10$Ao1Au4odxNUoheVsOeXim.l43NWkEV.XdzXeyYi2gz3YODBUuO/Ee');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id_curso` int(11) NOT NULL,
  `nombre_curso` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `usuarios` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id_curso`, `nombre_curso`, `descripcion`, `usuarios`) VALUES
(1, 'Introducción a la Ingeniería de Software con IA', 'Curso básico que explora los fundamentos de la ingeniería de software e inteligencia artificial en el contexto de SENATI Puno.', NULL),
(2, 'Programación en Python para IA', 'Aprende a programar en Python con enfoque en aplicaciones de inteligencia artificial.', NULL),
(3, 'Fundamentos de Aprendizaje Automático', 'Estudio de los algoritmos esenciales del aprendizaje automático, aplicados al desarrollo de software.', NULL),
(4, 'Ingeniería de Requisitos con IA', 'Aplicación de técnicas de IA para la recolección y análisis de requisitos de software.', NULL),
(5, 'Desarrollo de Software Inteligente', 'Diseño y desarrollo de sistemas inteligentes aplicando metodologías ágiles.', NULL),
(6, 'Minería de Datos para Software Inteligente', 'Técnicas de minería de datos aplicadas al análisis de grandes volúmenes de información en proyectos de software.', NULL),
(7, 'Pruebas de Software Asistidas por IA', 'Uso de herramientas y técnicas de inteligencia artificial para automatizar pruebas de software.', NULL),
(8, 'Procesamiento de Lenguaje Natural', 'Curso orientado al desarrollo de software que entiende y genera lenguaje humano.', NULL),
(9, 'Integración de Sistemas con IA', 'Metodologías para integrar soluciones de IA en sistemas de software existentes.', NULL),
(10, 'Proyecto Final de Ingeniería de Software con IA', 'Desarrollo de un proyecto completo aplicando conocimientos adquiridos en IA y desarrollo de software en SENATI Puno.', NULL),
(11, 'Ingeniería de Software con Inteligencia Artificial', 'Este curso integra fundamentos de ingeniería de software con técnicas modernas de inteligencia artificial. Los estudiantes aprenderán a diseñar y desarrollar software inteligente aplicando machine learning', NULL),
(13, 'prueba', 'prueba de que el curso se agrego correctamente', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos_usuario`
--

CREATE TABLE `cursos_usuario` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `id_curso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos_usuario`
--

INSERT INTO `cursos_usuario` (`id`, `usuario`, `id_curso`) VALUES
(139, 'usuario', 1),
(140, 'usuario', 2),
(141, 'usuario', 3),
(142, 'usuario', 4),
(143, 'usuario', 5),
(144, 'usuario', 6),
(145, 'usuario', 7),
(146, 'usuario', 8),
(147, 'usuario', 9),
(148, 'usuario', 10),
(155, 'prueba', 11),
(159, 'user', 13),
(160, 'never', 10),
(161, 'never', 11),
(162, 'never', 13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `id` int(11) NOT NULL,
  `id_curso` int(11) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tareas`
--

INSERT INTO `tareas` (`id`, `id_curso`, `titulo`, `descripcion`, `fecha_entrega`) VALUES
(1, 1, 'Tarea 1 - Introducción a la Ingeniería de Software con IA', 'Descripción de la tarea 1 del curso \"Introducción a la Ingeniería de Software con IA\"', '2025-06-10'),
(2, 1, 'Tarea 2 - Introducción a la Ingeniería de Software con IA', 'Descripción de la tarea 2 del curso \"Introducción a la Ingeniería de Software con IA\"', '2025-06-11'),
(3, 1, 'Tarea 3 - Introducción a la Ingeniería de Software con IA', 'Descripción de la tarea 3 del curso \"Introducción a la Ingeniería de Software con IA\"', '2025-06-12'),
(4, 1, 'Tarea 4 - Introducción a la Ingeniería de Software con IA', 'Descripción de la tarea 4 del curso \"Introducción a la Ingeniería de Software con IA\"', '2025-06-13'),
(5, 1, 'Tarea 5 - Introducción a la Ingeniería de Software con IA', 'Descripción de la tarea 5 del curso \"Introducción a la Ingeniería de Software con IA\"', '2025-06-14'),
(6, 2, 'Tarea 1 - Programación en Python para IA', 'Descripción de la tarea 1 del curso \"Programación en Python para IA\"', '2025-06-10'),
(7, 2, 'Tarea 2 - Programación en Python para IA', 'Descripción de la tarea 2 del curso \"Programación en Python para IA\"', '2025-06-11'),
(8, 2, 'Tarea 3 - Programación en Python para IA', 'Descripción de la tarea 3 del curso \"Programación en Python para IA\"', '2025-06-12'),
(9, 2, 'Tarea 4 - Programación en Python para IA', 'Descripción de la tarea 4 del curso \"Programación en Python para IA\"', '2025-06-13'),
(10, 2, 'Tarea 5 - Programación en Python para IA', 'Descripción de la tarea 5 del curso \"Programación en Python para IA\"', '2025-06-14'),
(11, 3, 'Tarea 1 - Fundamentos de Aprendizaje Automático', 'Descripción de la tarea 1 del curso \"Fundamentos de Aprendizaje Automático\"', '2025-06-10'),
(12, 3, 'Tarea 2 - Fundamentos de Aprendizaje Automático', 'Descripción de la tarea 2 del curso \"Fundamentos de Aprendizaje Automático\"', '2025-06-11'),
(13, 3, 'Tarea 3 - Fundamentos de Aprendizaje Automático', 'Descripción de la tarea 3 del curso \"Fundamentos de Aprendizaje Automático\"', '2025-06-12'),
(14, 3, 'Tarea 4 - Fundamentos de Aprendizaje Automático', 'Descripción de la tarea 4 del curso \"Fundamentos de Aprendizaje Automático\"', '2025-06-13'),
(15, 3, 'Tarea 5 - Fundamentos de Aprendizaje Automático', 'Descripción de la tarea 5 del curso \"Fundamentos de Aprendizaje Automático\"', '2025-06-14'),
(16, 4, 'Tarea 1 - Ingeniería de Requisitos con IA', 'Descripción de la tarea 1 del curso \"Ingeniería de Requisitos con IA\"', '2025-06-10'),
(17, 4, 'Tarea 2 - Ingeniería de Requisitos con IA', 'Descripción de la tarea 2 del curso \"Ingeniería de Requisitos con IA\"', '2025-06-11'),
(18, 4, 'Tarea 3 - Ingeniería de Requisitos con IA', 'Descripción de la tarea 3 del curso \"Ingeniería de Requisitos con IA\"', '2025-06-12'),
(19, 4, 'Tarea 4 - Ingeniería de Requisitos con IA', 'Descripción de la tarea 4 del curso \"Ingeniería de Requisitos con IA\"', '2025-06-13'),
(20, 4, 'Tarea 5 - Ingeniería de Requisitos con IA', 'Descripción de la tarea 5 del curso \"Ingeniería de Requisitos con IA\"', '2025-06-14'),
(21, 5, 'Tarea 1 - Desarrollo de Software Inteligente', 'Descripción de la tarea 1 del curso \"Desarrollo de Software Inteligente\"', '2025-06-10'),
(22, 5, 'Tarea 2 - Desarrollo de Software Inteligente', 'Descripción de la tarea 2 del curso \"Desarrollo de Software Inteligente\"', '2025-06-11'),
(23, 5, 'Tarea 3 - Desarrollo de Software Inteligente', 'Descripción de la tarea 3 del curso \"Desarrollo de Software Inteligente\"', '2025-06-12'),
(24, 5, 'Tarea 4 - Desarrollo de Software Inteligente', 'Descripción de la tarea 4 del curso \"Desarrollo de Software Inteligente\"', '2025-06-13'),
(25, 5, 'Tarea 5 - Desarrollo de Software Inteligente', 'Descripción de la tarea 5 del curso \"Desarrollo de Software Inteligente\"', '2025-06-14'),
(26, 6, 'Tarea 1 - Minería de Datos para Software Inteligente', 'Descripción de la tarea 1 del curso \"Minería de Datos para Software Inteligente\"', '2025-06-10'),
(27, 6, 'Tarea 2 - Minería de Datos para Software Inteligente', 'Descripción de la tarea 2 del curso \"Minería de Datos para Software Inteligente\"', '2025-06-11'),
(28, 6, 'Tarea 3 - Minería de Datos para Software Inteligente', 'Descripción de la tarea 3 del curso \"Minería de Datos para Software Inteligente\"', '2025-06-12'),
(29, 6, 'Tarea 4 - Minería de Datos para Software Inteligente', 'Descripción de la tarea 4 del curso \"Minería de Datos para Software Inteligente\"', '2025-06-13'),
(30, 6, 'Tarea 5 - Minería de Datos para Software Inteligente', 'Descripción de la tarea 5 del curso \"Minería de Datos para Software Inteligente\"', '2025-06-14'),
(31, 7, 'Tarea 1 - Pruebas de Software Asistidas por IA', 'Descripción de la tarea 1 del curso \"Pruebas de Software Asistidas por IA\"', '2025-06-10'),
(32, 7, 'Tarea 2 - Pruebas de Software Asistidas por IA', 'Descripción de la tarea 2 del curso \"Pruebas de Software Asistidas por IA\"', '2025-06-11'),
(33, 7, 'Tarea 3 - Pruebas de Software Asistidas por IA', 'Descripción de la tarea 3 del curso \"Pruebas de Software Asistidas por IA\"', '2025-06-12'),
(34, 7, 'Tarea 4 - Pruebas de Software Asistidas por IA', 'Descripción de la tarea 4 del curso \"Pruebas de Software Asistidas por IA\"', '2025-06-13'),
(35, 7, 'Tarea 5 - Pruebas de Software Asistidas por IA', 'Descripción de la tarea 5 del curso \"Pruebas de Software Asistidas por IA\"', '2025-06-14'),
(36, 8, 'Tarea 1 - Procesamiento de Lenguaje Natural', 'Descripción de la tarea 1 del curso \"Procesamiento de Lenguaje Natural\"', '2025-06-10'),
(37, 8, 'Tarea 2 - Procesamiento de Lenguaje Natural', 'Descripción de la tarea 2 del curso \"Procesamiento de Lenguaje Natural\"', '2025-06-11'),
(38, 8, 'Tarea 3 - Procesamiento de Lenguaje Natural', 'Descripción de la tarea 3 del curso \"Procesamiento de Lenguaje Natural\"', '2025-06-12'),
(39, 8, 'Tarea 4 - Procesamiento de Lenguaje Natural', 'Descripción de la tarea 4 del curso \"Procesamiento de Lenguaje Natural\"', '2025-06-13'),
(40, 8, 'Tarea 5 - Procesamiento de Lenguaje Natural', 'Descripción de la tarea 5 del curso \"Procesamiento de Lenguaje Natural\"', '2025-06-14'),
(41, 9, 'Tarea 1 - Integración de Sistemas con IA', 'Descripción de la tarea 1 del curso \"Integración de Sistemas con IA\"', '2025-06-10'),
(42, 9, 'Tarea 2 - Integración de Sistemas con IA', 'Descripción de la tarea 2 del curso \"Integración de Sistemas con IA\"', '2025-06-11'),
(43, 9, 'Tarea 3 - Integración de Sistemas con IA', 'Descripción de la tarea 3 del curso \"Integración de Sistemas con IA\"', '2025-06-12'),
(44, 9, 'Tarea 4 - Integración de Sistemas con IA', 'Descripción de la tarea 4 del curso \"Integración de Sistemas con IA\"', '2025-06-13'),
(45, 9, 'Tarea 5 - Integración de Sistemas con IA', 'Descripción de la tarea 5 del curso \"Integración de Sistemas con IA\"', '2025-06-14'),
(46, 10, 'Tarea 1 - Proyecto Final de Ingeniería de Software con IA', 'Descripción de la tarea 1 del curso \"Proyecto Final de Ingeniería de Software con IA\"', '2025-06-10'),
(47, 10, 'Tarea 2 - Proyecto Final de Ingeniería de Software con IA', 'Descripción de la tarea 2 del curso \"Proyecto Final de Ingeniería de Software con IA\"', '2025-06-11'),
(48, 10, 'Tarea 3 - Proyecto Final de Ingeniería de Software con IA', 'Descripción de la tarea 3 del curso \"Proyecto Final de Ingeniería de Software con IA\"', '2025-06-12'),
(49, 10, 'Tarea 4 - Proyecto Final de Ingeniería de Software con IA', 'Descripción de la tarea 4 del curso \"Proyecto Final de Ingeniería de Software con IA\"', '2025-06-13'),
(50, 10, 'Tarea 5 - Proyecto Final de Ingeniería de Software con IA', 'Descripción de la tarea 5 del curso \"Proyecto Final de Ingeniería de Software con IA\"', '2025-06-14'),
(51, 11, 'Tarea 1 - Ingeniería de Software con Inteligencia Artificial', 'Descripción de la tarea 1 del curso \"Ingeniería de Software con Inteligencia Artificial\"', '2025-06-10'),
(52, 11, 'Tarea 2 - Ingeniería de Software con Inteligencia Artificial', 'Descripción de la tarea 2 del curso \"Ingeniería de Software con Inteligencia Artificial\"', '2025-06-11'),
(53, 11, 'Tarea 3 - Ingeniería de Software con Inteligencia Artificial', 'Descripción de la tarea 3 del curso \"Ingeniería de Software con Inteligencia Artificial\"', '2025-06-12'),
(54, 11, 'Tarea 4 - Ingeniería de Software con Inteligencia Artificial', 'Descripción de la tarea 4 del curso \"Ingeniería de Software con Inteligencia Artificial\"', '2025-06-13'),
(55, 11, 'Tarea 5 - Ingeniería de Software con Inteligencia Artificial', 'Descripción de la tarea 5 del curso \"Ingeniería de Software con Inteligencia Artificial\"', '2025-06-14'),
(56, 11, 'Diseño de una Arquitectura de Software Inteligente', 'preuba final del curso', '2025-06-10'),
(57, 11, 'Diseño de una Arquitectura de Software Inteligente', 'preuba final del curso', '2025-06-10'),
(58, 11, 'preuba', 'hola prueba', '2025-06-10'),
(59, 13, 'diseño de php', 'preuba', '2025-06-30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas_terminadas`
--

CREATE TABLE `tareas_terminadas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `id_tarea` int(11) DEFAULT NULL,
  `fecha_marcada` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tareas_terminadas`
--

INSERT INTO `tareas_terminadas` (`id`, `usuario_id`, `id_tarea`, `fecha_marcada`) VALUES
(2, 2, 1, '2025-05-31 17:44:33'),
(3, 3, 1, '2025-06-01 15:32:16'),
(4, 3, 15, '2025-06-01 15:32:18'),
(5, 4, 1, '2025-06-01 15:50:45'),
(6, 4, 59, '2025-06-01 15:52:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contraseña` varchar(255) DEFAULT NULL,
  `estado` enum('pendiente','activo','bloqueado') DEFAULT 'pendiente',
  `token` varchar(255) DEFAULT NULL,
  `token_creado` datetime NOT NULL DEFAULT current_timestamp(),
  `token_activacion` varchar(255) DEFAULT NULL,
  `token_expira` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `usuario`, `contraseña`, `estado`, `token`, `token_creado`, `token_activacion`, `token_expira`) VALUES
(1, 'jhon', '', 'jhon', '$2y$10$F.y6CvI2L0DhChPltKi41uKWrBwiBihvRb5Jd.NxBwYlasS2rXbSO', 'pendiente', NULL, '0000-00-00 00:00:00', NULL, NULL),
(2, 'nadi', '', 'usuario', '$2y$10$VAQIUQrtrrEShX403fJRaeLoOl2tMJx3ZCn7dDED6kW93Zrzq2KxO', 'pendiente', NULL, '0000-00-00 00:00:00', NULL, NULL),
(3, 'luz', '', 'prueba', '$2y$10$0JNeeOZKa7l5A6lZFaSZVelDBY/vaOEsyFWWB3BzACSjtYjePHi4e', 'pendiente', NULL, '0000-00-00 00:00:00', NULL, NULL),
(4, 'sara', '', 'user', '$2y$10$8I384.RilcBYr7jL3KYrHuNRQ2g2suyP2rmf0xvYFn.01bivRDUYq', 'pendiente', NULL, '0000-00-00 00:00:00', NULL, NULL),
(5, 'prueba2', 'fjhonf3@gmail.com', 'never', '$2y$10$BxOeumPwIYxgTV87V.k5BuwB4ITc39N9u18AMAe6rQlZbg7zP7T0a', 'bloqueado', NULL, '0000-00-00 00:00:00', '3e23fa0f58064b4b3842d67af04a022aecb7746722d8d5b8e22cab6dad2c3863', '2025-06-05 18:20:38'),
(7, 'teamonadi', 'jhonfernandogomezquispe@gmail.com', 'nadi', '$2y$10$TLq8RIMN3UZ6D93EiY14oOAVFQwp3MYVT.6xrxjO9veBPTEAFS9Ga', 'activo', NULL, '0000-00-00 00:00:00', NULL, NULL),
(23, 'jhon61', 'verificacion467@gmail.com', 'nadi1', '$2y$10$NL9El69sLGIzXXvVEGkfmuwsnSushg9CZ.3jc0Y6wZ9BUCuhIOz7G', 'activo', NULL, '0000-00-00 00:00:00', NULL, NULL),
(25, 'jhon1242', 'pruebalgenshin1@gmail.com', 'jhon612132', '$2y$10$1awp0YLi3E.jYgLiG4gDqeEoPtWrk2m2b5ZGSqcf4g8.dpo6LNFkm', 'activo', NULL, '0000-00-00 00:00:00', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id_curso`),
  ADD KEY `fk_usuario` (`usuarios`);

--
-- Indices de la tabla `cursos_usuario`
--
ALTER TABLE `cursos_usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario` (`usuario`),
  ADD KEY `id_curso` (`id_curso`);

--
-- Indices de la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_curso` (`id_curso`);

--
-- Indices de la tabla `tareas_terminadas`
--
ALTER TABLE `tareas_terminadas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `id_tarea` (`id_tarea`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `cursos_usuario`
--
ALTER TABLE `cursos_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT de la tabla `tareas_terminadas`
--
ALTER TABLE `tareas_terminadas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`usuarios`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `cursos_usuario`
--
ALTER TABLE `cursos_usuario`
  ADD CONSTRAINT `cursos_usuario_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`usuario`),
  ADD CONSTRAINT `cursos_usuario_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`);

--
-- Filtros para la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD CONSTRAINT `tareas_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`);

--
-- Filtros para la tabla `tareas_terminadas`
--
ALTER TABLE `tareas_terminadas`
  ADD CONSTRAINT `tareas_terminadas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `tareas_terminadas_ibfk_2` FOREIGN KEY (`id_tarea`) REFERENCES `tareas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
