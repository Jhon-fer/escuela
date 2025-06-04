-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-06-2025 a las 23:00:02
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
-- Base de datos: `login_a` ********************************************************************************************************
-- CREATE DATABASE lohin_a;
-- USE lohin_a;
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

INSERT INTO `admin` VALUES(1, 'nadi', 'admin', '$2y$10$wz/hF2jp2FvAgUF3pZPgnOTNDHIekAfYi788iU8cIyfjcdG3n6wzC');
INSERT INTO `admin` VALUES(2, 'admin', 'nadi', '$2y$10$JOTyzvuDPlZW4oqrNXMf7uT3lKhe4ynpRuUY/yRO5SWmwYvTapFUG');
INSERT INTO `admin` VALUES(3, 'nadi', 'jhon', '$2y$10$jm.d0w7cTU3gPk7p5CUHpuhxTEnRJ7JCi/2fG3dHCdFiQP6LCfUIy');
INSERT INTO `admin` VALUES(4, 'admin', 'deyvid', '$2y$10$aSrv7ljeF/BAjcNgVegk7eC5fvWqQnaTb3gemkRZZmbUvHdrJVx96');

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

INSERT INTO `cursos` VALUES(1, 'Introducción a la Ingeniería de Software con IA', 'Curso básico que explora los fundamentos de la ingeniería de software e inteligencia artificial en el contexto de SENATI Puno.', NULL);
INSERT INTO `cursos` VALUES(2, 'Programación en Python para IA', 'Aprende a programar en Python con enfoque en aplicaciones de inteligencia artificial.', NULL);
INSERT INTO `cursos` VALUES(3, 'Fundamentos de Aprendizaje Automático', 'Estudio de los algoritmos esenciales del aprendizaje automático, aplicados al desarrollo de software.', NULL);
INSERT INTO `cursos` VALUES(4, 'Ingeniería de Requisitos con IA', 'Aplicación de técnicas de IA para la recolección y análisis de requisitos de software.', NULL);
INSERT INTO `cursos` VALUES(5, 'Desarrollo de Software Inteligente', 'Diseño y desarrollo de sistemas inteligentes aplicando metodologías ágiles.', NULL);
INSERT INTO `cursos` VALUES(6, 'Minería de Datos para Software Inteligente', 'Técnicas de minería de datos aplicadas al análisis de grandes volúmenes de información en proyectos de software.', NULL);
INSERT INTO `cursos` VALUES(7, 'Pruebas de Software Asistidas por IA', 'Uso de herramientas y técnicas de inteligencia artificial para automatizar pruebas de software.', NULL);
INSERT INTO `cursos` VALUES(8, 'Procesamiento de Lenguaje Natural', 'Curso orientado al desarrollo de software que entiende y genera lenguaje humano.', NULL);
INSERT INTO `cursos` VALUES(9, 'Integración de Sistemas con IA', 'Metodologías para integrar soluciones de IA en sistemas de software existentes.', NULL);
INSERT INTO `cursos` VALUES(10, 'Proyecto Final de Ingeniería de Software con IA', 'Desarrollo de un proyecto completo aplicando conocimientos adquiridos en IA y desarrollo de software en SENATI Puno.', NULL);
INSERT INTO `cursos` VALUES(11, 'Ingeniería de Software con Inteligencia Artificial', 'Este curso integra fundamentos de ingeniería de software con técnicas modernas de inteligencia artificial. Los estudiantes aprenderán a diseñar y desarrollar software inteligente aplicando machine learning', NULL);
INSERT INTO `cursos` VALUES(13, 'prueba', 'prueba de que el curso se agrego correctamente', NULL);

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

INSERT INTO `cursos_usuario` VALUES(139, 'usuario', 1);
INSERT INTO `cursos_usuario` VALUES(140, 'usuario', 2);
INSERT INTO `cursos_usuario` VALUES(141, 'usuario', 3);
INSERT INTO `cursos_usuario` VALUES(142, 'usuario', 4);
INSERT INTO `cursos_usuario` VALUES(143, 'usuario', 5);
INSERT INTO `cursos_usuario` VALUES(144, 'usuario', 6);
INSERT INTO `cursos_usuario` VALUES(145, 'usuario', 7);
INSERT INTO `cursos_usuario` VALUES(146, 'usuario', 8);
INSERT INTO `cursos_usuario` VALUES(147, 'usuario', 9);
INSERT INTO `cursos_usuario` VALUES(148, 'usuario', 10);
INSERT INTO `cursos_usuario` VALUES(155, 'prueba', 11);
INSERT INTO `cursos_usuario` VALUES(159, 'user', 13);

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

INSERT INTO `tareas` VALUES(1, 1, 'Tarea 1 - Introducción a la Ingeniería de Software con IA', 'Descripción de la tarea 1 del curso \"Introducción a la Ingeniería de Software con IA\"', '2025-06-10');
INSERT INTO `tareas` VALUES(2, 1, 'Tarea 2 - Introducción a la Ingeniería de Software con IA', 'Descripción de la tarea 2 del curso \"Introducción a la Ingeniería de Software con IA\"', '2025-06-11');
INSERT INTO `tareas` VALUES(3, 1, 'Tarea 3 - Introducción a la Ingeniería de Software con IA', 'Descripción de la tarea 3 del curso \"Introducción a la Ingeniería de Software con IA\"', '2025-06-12');
INSERT INTO `tareas` VALUES(4, 1, 'Tarea 4 - Introducción a la Ingeniería de Software con IA', 'Descripción de la tarea 4 del curso \"Introducción a la Ingeniería de Software con IA\"', '2025-06-13');
INSERT INTO `tareas` VALUES(5, 1, 'Tarea 5 - Introducción a la Ingeniería de Software con IA', 'Descripción de la tarea 5 del curso \"Introducción a la Ingeniería de Software con IA\"', '2025-06-14');
INSERT INTO `tareas` VALUES(6, 2, 'Tarea 1 - Programación en Python para IA', 'Descripción de la tarea 1 del curso \"Programación en Python para IA\"', '2025-06-10');
INSERT INTO `tareas` VALUES(7, 2, 'Tarea 2 - Programación en Python para IA', 'Descripción de la tarea 2 del curso \"Programación en Python para IA\"', '2025-06-11');
INSERT INTO `tareas` VALUES(8, 2, 'Tarea 3 - Programación en Python para IA', 'Descripción de la tarea 3 del curso \"Programación en Python para IA\"', '2025-06-12');
INSERT INTO `tareas` VALUES(9, 2, 'Tarea 4 - Programación en Python para IA', 'Descripción de la tarea 4 del curso \"Programación en Python para IA\"', '2025-06-13');
INSERT INTO `tareas` VALUES(10, 2, 'Tarea 5 - Programación en Python para IA', 'Descripción de la tarea 5 del curso \"Programación en Python para IA\"', '2025-06-14');
INSERT INTO `tareas` VALUES(11, 3, 'Tarea 1 - Fundamentos de Aprendizaje Automático', 'Descripción de la tarea 1 del curso \"Fundamentos de Aprendizaje Automático\"', '2025-06-10');
INSERT INTO `tareas` VALUES(12, 3, 'Tarea 2 - Fundamentos de Aprendizaje Automático', 'Descripción de la tarea 2 del curso \"Fundamentos de Aprendizaje Automático\"', '2025-06-11');
INSERT INTO `tareas` VALUES(13, 3, 'Tarea 3 - Fundamentos de Aprendizaje Automático', 'Descripción de la tarea 3 del curso \"Fundamentos de Aprendizaje Automático\"', '2025-06-12');
INSERT INTO `tareas` VALUES(14, 3, 'Tarea 4 - Fundamentos de Aprendizaje Automático', 'Descripción de la tarea 4 del curso \"Fundamentos de Aprendizaje Automático\"', '2025-06-13');
INSERT INTO `tareas` VALUES(15, 3, 'Tarea 5 - Fundamentos de Aprendizaje Automático', 'Descripción de la tarea 5 del curso \"Fundamentos de Aprendizaje Automático\"', '2025-06-14');
INSERT INTO `tareas` VALUES(16, 4, 'Tarea 1 - Ingeniería de Requisitos con IA', 'Descripción de la tarea 1 del curso \"Ingeniería de Requisitos con IA\"', '2025-06-10');
INSERT INTO `tareas` VALUES(17, 4, 'Tarea 2 - Ingeniería de Requisitos con IA', 'Descripción de la tarea 2 del curso \"Ingeniería de Requisitos con IA\"', '2025-06-11');
INSERT INTO `tareas` VALUES(18, 4, 'Tarea 3 - Ingeniería de Requisitos con IA', 'Descripción de la tarea 3 del curso \"Ingeniería de Requisitos con IA\"', '2025-06-12');
INSERT INTO `tareas` VALUES(19, 4, 'Tarea 4 - Ingeniería de Requisitos con IA', 'Descripción de la tarea 4 del curso \"Ingeniería de Requisitos con IA\"', '2025-06-13');
INSERT INTO `tareas` VALUES(20, 4, 'Tarea 5 - Ingeniería de Requisitos con IA', 'Descripción de la tarea 5 del curso \"Ingeniería de Requisitos con IA\"', '2025-06-14');
INSERT INTO `tareas` VALUES(21, 5, 'Tarea 1 - Desarrollo de Software Inteligente', 'Descripción de la tarea 1 del curso \"Desarrollo de Software Inteligente\"', '2025-06-10');
INSERT INTO `tareas` VALUES(22, 5, 'Tarea 2 - Desarrollo de Software Inteligente', 'Descripción de la tarea 2 del curso \"Desarrollo de Software Inteligente\"', '2025-06-11');
INSERT INTO `tareas` VALUES(23, 5, 'Tarea 3 - Desarrollo de Software Inteligente', 'Descripción de la tarea 3 del curso \"Desarrollo de Software Inteligente\"', '2025-06-12');
INSERT INTO `tareas` VALUES(24, 5, 'Tarea 4 - Desarrollo de Software Inteligente', 'Descripción de la tarea 4 del curso \"Desarrollo de Software Inteligente\"', '2025-06-13');
INSERT INTO `tareas` VALUES(25, 5, 'Tarea 5 - Desarrollo de Software Inteligente', 'Descripción de la tarea 5 del curso \"Desarrollo de Software Inteligente\"', '2025-06-14');
INSERT INTO `tareas` VALUES(26, 6, 'Tarea 1 - Minería de Datos para Software Inteligente', 'Descripción de la tarea 1 del curso \"Minería de Datos para Software Inteligente\"', '2025-06-10');
INSERT INTO `tareas` VALUES(27, 6, 'Tarea 2 - Minería de Datos para Software Inteligente', 'Descripción de la tarea 2 del curso \"Minería de Datos para Software Inteligente\"', '2025-06-11');
INSERT INTO `tareas` VALUES(28, 6, 'Tarea 3 - Minería de Datos para Software Inteligente', 'Descripción de la tarea 3 del curso \"Minería de Datos para Software Inteligente\"', '2025-06-12');
INSERT INTO `tareas` VALUES(29, 6, 'Tarea 4 - Minería de Datos para Software Inteligente', 'Descripción de la tarea 4 del curso \"Minería de Datos para Software Inteligente\"', '2025-06-13');
INSERT INTO `tareas` VALUES(30, 6, 'Tarea 5 - Minería de Datos para Software Inteligente', 'Descripción de la tarea 5 del curso \"Minería de Datos para Software Inteligente\"', '2025-06-14');
INSERT INTO `tareas` VALUES(31, 7, 'Tarea 1 - Pruebas de Software Asistidas por IA', 'Descripción de la tarea 1 del curso \"Pruebas de Software Asistidas por IA\"', '2025-06-10');
INSERT INTO `tareas` VALUES(32, 7, 'Tarea 2 - Pruebas de Software Asistidas por IA', 'Descripción de la tarea 2 del curso \"Pruebas de Software Asistidas por IA\"', '2025-06-11');
INSERT INTO `tareas` VALUES(33, 7, 'Tarea 3 - Pruebas de Software Asistidas por IA', 'Descripción de la tarea 3 del curso \"Pruebas de Software Asistidas por IA\"', '2025-06-12');
INSERT INTO `tareas` VALUES(34, 7, 'Tarea 4 - Pruebas de Software Asistidas por IA', 'Descripción de la tarea 4 del curso \"Pruebas de Software Asistidas por IA\"', '2025-06-13');
INSERT INTO `tareas` VALUES(35, 7, 'Tarea 5 - Pruebas de Software Asistidas por IA', 'Descripción de la tarea 5 del curso \"Pruebas de Software Asistidas por IA\"', '2025-06-14');
INSERT INTO `tareas` VALUES(36, 8, 'Tarea 1 - Procesamiento de Lenguaje Natural', 'Descripción de la tarea 1 del curso \"Procesamiento de Lenguaje Natural\"', '2025-06-10');
INSERT INTO `tareas` VALUES(37, 8, 'Tarea 2 - Procesamiento de Lenguaje Natural', 'Descripción de la tarea 2 del curso \"Procesamiento de Lenguaje Natural\"', '2025-06-11');
INSERT INTO `tareas` VALUES(38, 8, 'Tarea 3 - Procesamiento de Lenguaje Natural', 'Descripción de la tarea 3 del curso \"Procesamiento de Lenguaje Natural\"', '2025-06-12');
INSERT INTO `tareas` VALUES(39, 8, 'Tarea 4 - Procesamiento de Lenguaje Natural', 'Descripción de la tarea 4 del curso \"Procesamiento de Lenguaje Natural\"', '2025-06-13');
INSERT INTO `tareas` VALUES(40, 8, 'Tarea 5 - Procesamiento de Lenguaje Natural', 'Descripción de la tarea 5 del curso \"Procesamiento de Lenguaje Natural\"', '2025-06-14');
INSERT INTO `tareas` VALUES(41, 9, 'Tarea 1 - Integración de Sistemas con IA', 'Descripción de la tarea 1 del curso \"Integración de Sistemas con IA\"', '2025-06-10');
INSERT INTO `tareas` VALUES(42, 9, 'Tarea 2 - Integración de Sistemas con IA', 'Descripción de la tarea 2 del curso \"Integración de Sistemas con IA\"', '2025-06-11');
INSERT INTO `tareas` VALUES(43, 9, 'Tarea 3 - Integración de Sistemas con IA', 'Descripción de la tarea 3 del curso \"Integración de Sistemas con IA\"', '2025-06-12');
INSERT INTO `tareas` VALUES(44, 9, 'Tarea 4 - Integración de Sistemas con IA', 'Descripción de la tarea 4 del curso \"Integración de Sistemas con IA\"', '2025-06-13');
INSERT INTO `tareas` VALUES(45, 9, 'Tarea 5 - Integración de Sistemas con IA', 'Descripción de la tarea 5 del curso \"Integración de Sistemas con IA\"', '2025-06-14');
INSERT INTO `tareas` VALUES(46, 10, 'Tarea 1 - Proyecto Final de Ingeniería de Software con IA', 'Descripción de la tarea 1 del curso \"Proyecto Final de Ingeniería de Software con IA\"', '2025-06-10');
INSERT INTO `tareas` VALUES(47, 10, 'Tarea 2 - Proyecto Final de Ingeniería de Software con IA', 'Descripción de la tarea 2 del curso \"Proyecto Final de Ingeniería de Software con IA\"', '2025-06-11');
INSERT INTO `tareas` VALUES(48, 10, 'Tarea 3 - Proyecto Final de Ingeniería de Software con IA', 'Descripción de la tarea 3 del curso \"Proyecto Final de Ingeniería de Software con IA\"', '2025-06-12');
INSERT INTO `tareas` VALUES(49, 10, 'Tarea 4 - Proyecto Final de Ingeniería de Software con IA', 'Descripción de la tarea 4 del curso \"Proyecto Final de Ingeniería de Software con IA\"', '2025-06-13');
INSERT INTO `tareas` VALUES(50, 10, 'Tarea 5 - Proyecto Final de Ingeniería de Software con IA', 'Descripción de la tarea 5 del curso \"Proyecto Final de Ingeniería de Software con IA\"', '2025-06-14');
INSERT INTO `tareas` VALUES(51, 11, 'Tarea 1 - Ingeniería de Software con Inteligencia Artificial', 'Descripción de la tarea 1 del curso \"Ingeniería de Software con Inteligencia Artificial\"', '2025-06-10');
INSERT INTO `tareas` VALUES(52, 11, 'Tarea 2 - Ingeniería de Software con Inteligencia Artificial', 'Descripción de la tarea 2 del curso \"Ingeniería de Software con Inteligencia Artificial\"', '2025-06-11');
INSERT INTO `tareas` VALUES(53, 11, 'Tarea 3 - Ingeniería de Software con Inteligencia Artificial', 'Descripción de la tarea 3 del curso \"Ingeniería de Software con Inteligencia Artificial\"', '2025-06-12');
INSERT INTO `tareas` VALUES(54, 11, 'Tarea 4 - Ingeniería de Software con Inteligencia Artificial', 'Descripción de la tarea 4 del curso \"Ingeniería de Software con Inteligencia Artificial\"', '2025-06-13');
INSERT INTO `tareas` VALUES(55, 11, 'Tarea 5 - Ingeniería de Software con Inteligencia Artificial', 'Descripción de la tarea 5 del curso \"Ingeniería de Software con Inteligencia Artificial\"', '2025-06-14');
INSERT INTO `tareas` VALUES(56, 11, 'Diseño de una Arquitectura de Software Inteligente', 'preuba final del curso', '2025-06-10');
INSERT INTO `tareas` VALUES(57, 11, 'Diseño de una Arquitectura de Software Inteligente', 'preuba final del curso', '2025-06-10');
INSERT INTO `tareas` VALUES(58, 11, 'preuba', 'hola prueba', '2025-06-10');
INSERT INTO `tareas` VALUES(59, 13, 'diseño de php', 'preuba', '2025-06-30');

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

INSERT INTO `tareas_terminadas` VALUES(2, 2, 1, '2025-05-31 17:44:33');
INSERT INTO `tareas_terminadas` VALUES(3, 3, 1, '2025-06-01 15:32:16');
INSERT INTO `tareas_terminadas` VALUES(4, 3, 15, '2025-06-01 15:32:18');
INSERT INTO `tareas_terminadas` VALUES(5, 4, 1, '2025-06-01 15:50:45');
INSERT INTO `tareas_terminadas` VALUES(6, 4, 59, '2025-06-01 15:52:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contraseña` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` VALUES(1, 'jhon', 'jhon', '$2y$10$F.y6CvI2L0DhChPltKi41uKWrBwiBihvRb5Jd.NxBwYlasS2rXbSO');
INSERT INTO `usuarios` VALUES(2, 'nadi', 'usuario', '$2y$10$VAQIUQrtrrEShX403fJRaeLoOl2tMJx3ZCn7dDED6kW93Zrzq2KxO');
INSERT INTO `usuarios` VALUES(3, 'luz', 'prueba', '$2y$10$0JNeeOZKa7l5A6lZFaSZVelDBY/vaOEsyFWWB3BzACSjtYjePHi4e');
INSERT INTO `usuarios` VALUES(4, 'sara', 'user', '$2y$10$8I384.RilcBYr7jL3KYrHuNRQ2g2suyP2rmf0xvYFn.01bivRDUYq');

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
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `cursos_usuario`
--
ALTER TABLE `cursos_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT de la tabla `tareas_terminadas`
--
ALTER TABLE `tareas_terminadas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
