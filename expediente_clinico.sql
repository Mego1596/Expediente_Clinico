INSERT INTO `diagnostico`(`id`, `descripcion`, `creado_en`, `actualizado_en`, `codigo_categoria`) VALUES 
(1,'Ciertas enfermedades infecciosas y parasitarias',NULL,NULL,'A00-B99'),
(2,'Neoplasias',NULL,NULL,'C00-D48'),
(3,'Enfermedades de la sangre y de los órganos hematopoyéticos y otros trastornos que afectan el mecanismo de la inmunidad',NULL,NULL,'D50-D89'),
(4,'Enfermedades endocrinas, nutricionales y metabólicas',NULL,NULL,'E00-E90'),
(5,'Trastornos mentales y del comportamiento',NULL,NULL,'F00-F99'),
(6,'Enfermedades del sistema nervioso',NULL,NULL,'G00-G99'),
(7,'Enfermedades del ojo y sus anexos',NULL,NULL,'H00-H59'),
(8,'Enfermedades del oído y de la apófisis mastoides',NULL,NULL,'H60-H95'),
(9,'Enfermedades del sistema circulatorio',NULL,NULL,'I00-I99'),
(10,'Enfermedades del sistema respiratorio',NULL,NULL,'J00-J99'),
(11,'Enfermedades del aparato digestivo',NULL,NULL,'K00-K93'),
(12,'Enfermedades de la piel y el tejido subcutáneo',NULL,NULL,'L00-L99'),
(13,'Enfermedades del sistema osteomuscular y del tejido conectivo',NULL,NULL,'M00-M99'),
(14,'Enfermedades del aparato genitourinario',NULL,NULL,'N00-N99'),
(15,'Embarazo, parto y puerperio',NULL,NULL,'O00-O99'),
(16,'Ciertas afecciones originadas en el periodo perinatal',NULL,NULL,'P00-P96'),
(17,'Malformaciones congénitas, deformidades y anomalías cromosómicas',NULL,NULL,'Q00-Q99'),
(18,'Síntomas, signos y hallazgos anormales clínicos y de laboratorio, no clasificados en otra parte',NULL,NULL,'R00-R99'),
(19,'Traumatismos, envenenamientos y algunas otras consecuencias de causa externa',NULL,NULL,'S00-T98'),
(20,'Causas externas de morbilidad y de mortalidad',NULL,NULL,'V01-Y98'),
(21,'Factores que influyen en el estado de salud y contacto con los servicios de salud',NULL,NULL,'Z00-Z99'),
(22,'Códigos para situaciones especiales',NULL,NULL,'U00-U99');


INSERT INTO `clinica` (`id`, `nombre_clinica`, `direccion`, `telefono`, `email`, `creado_en`, `actualizado_en`) VALUES
(1, 'CLINICA 1', 'San Salvador', '2257-7777', 'gggg@gmail.com', NULL, NULL),
(2, 'CLINICA 2', 'San Salvador', '1111-1111', 'aaaa@gmail.com', NULL, NULL);


INSERT INTO `especialidad` (`id`, `nombre_especialidad`, `creado_en`, `actualizado_en`) VALUES
(1, 'Ortopedia', NULL, NULL),
(2, 'Neurocirugia', NULL, NULL),
(3, 'Psiquiatria', NULL, NULL),
(4, 'Pediatria', NULL, NULL),
(5, 'Cardiologia', NULL, NULL);

INSERT INTO `genero` (`id`, `descripcion`, `creado_en`, `actualizado_en`) VALUES
(1, 'MASCULINO', NULL, NULL),
(2, 'FEMENINO', NULL, NULL);


INSERT INTO `permiso` (`id`, `permiso`, `descripcion`, `nombre_tabla`, `creado_en`, `actualizado_en`) VALUES
(1, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'GENERO', NULL, NULL),
(2, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'GENERO', NULL, NULL),
(3, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'GENERO', NULL, NULL),
(4, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'GENERO', NULL, NULL),
(5, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'GENERO', NULL, NULL),


(6, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'CLINICA', NULL, NULL),
(7, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'CLINICA', NULL, NULL),
(8, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'CLINICA', NULL, NULL),
(9, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'CLINICA', NULL, NULL),
(10, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'CLINICA', NULL, NULL),

(11, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'ESPECIALIDAD', NULL, NULL),
(12, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'ESPECIALIDAD', NULL, NULL),
(13, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'ESPECIALIDAD', NULL, NULL),
(14, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'ESPECIALIDAD', NULL, NULL),
(15, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'ESPECIALIDAD', NULL, NULL),

(16, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'EXPEDIENTE', NULL, NULL),
(17, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'EXPEDIENTE', NULL, NULL),
(18, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'EXPEDIENTE', NULL, NULL),
(19, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'EXPEDIENTE', NULL, NULL),
(20, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'EXPEDIENTE', NULL, NULL),

(21, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'PERMISOS_POR_ROL', NULL, NULL),
(22, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'PERMISOS_POR_ROL', NULL, NULL),
(23, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'PERMISOS_POR_ROL', NULL, NULL),
(24, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'PERMISOS_POR_ROL', NULL, NULL),
(25, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'PERMISOS_POR_ROL', NULL, NULL),

(26, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'ROL', NULL, NULL),
(27, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'ROL', NULL, NULL),
(28, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'ROL', NULL, NULL),
(29, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'ROL', NULL, NULL),
(30, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'ROL', NULL, NULL),

(31, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'USER', NULL, NULL),
(32, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'USER', NULL, NULL),
(33, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'USER', NULL, NULL),
(34, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'USER', NULL, NULL),
(35, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'USER', NULL, NULL),

(36, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'CITA', NULL, NULL),
(37, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'CITA', NULL, NULL),
(38, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'CITA', NULL, NULL),
(39, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'CITA', NULL, NULL),
(40, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'CITA', NULL, NULL),

(41, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'SALA', NULL, NULL),
(42, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'SALA', NULL, NULL),
(43, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'SALA', NULL, NULL),
(44, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'SALA', NULL, NULL),
(45, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'SALA', NULL, NULL),

(46, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'TIPO_HABITACION', NULL, NULL),
(47, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'TIPO_HABITACION', NULL, NULL),
(48, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'TIPO_HABITACION', NULL, NULL),
(49, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'TIPO_HABITACION', NULL, NULL),
(50, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'TIPO_HABITACION', NULL, NULL),

(51, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'HABITACION', NULL, NULL),
(52, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'HABITACION', NULL, NULL),
(53, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'HABITACION', NULL, NULL),
(54, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'HABITACION', NULL, NULL),
(55, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'HABITACION', NULL, NULL),

(56, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'CAMILLA', NULL, NULL),
(57, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'CAMILLA', NULL, NULL),
(58, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'CAMILLA', NULL, NULL),
(59, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'CAMILLA', NULL, NULL),
(60, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'CAMILLA', NULL, NULL),

(61,'ROLE_PERMISSION_INDEX_','Permiso de Entrada','HISTORIAL_PROPIO',NULL,NULL),
(62,'ROLE_PERMISSION_NEW_','Permiso de Creacion','HISTORIAL_PROPIO',NULL,NULL),
(63,'ROLE_PERMISSION_SHOW_','Permiso de Ver ','HISTORIAL_PROPIO',NULL,NULL),
(64,'ROLE_PERMISSION_EDIT_','Permiso de Editar ','HISTORIAL_PROPIO',NULL,NULL),
(65,'ROLE_PERMISSION_DELETE_','Permiso de Eliminar','HISTORIAL_PROPIO',NULL,NULL),

(66,'ROLE_PERMISSION_INDEX_','Permiso de Entrada','FAMILIAR',NULL,NULL),
(67,'ROLE_PERMISSION_NEW_','Permiso de Creacion','FAMILIAR',NULL,NULL),
(68,'ROLE_PERMISSION_SHOW_','Permiso de Ver ','FAMILIAR',NULL,NULL),
(69,'ROLE_PERMISSION_EDIT_','Permiso de Editar ','FAMILIAR',NULL,NULL),
(70,'ROLE_PERMISSION_DELETE_','Permiso de Eliminar','FAMILIAR',NULL,NULL),

(71,'ROLE_PERMISSION_INDEX_','Permiso de Entrada','HISTORIAL_FAMILIAR',NULL,NULL),
(72,'ROLE_PERMISSION_NEW_','Permiso de Creacion','HISTORIAL_FAMILIAR',NULL,NULL),
(73,'ROLE_PERMISSION_SHOW_','Permiso de Ver ','HISTORIAL_FAMILIAR',NULL,NULL),
(74,'ROLE_PERMISSION_EDIT_','Permiso de Editar ','HISTORIAL_FAMILIAR',NULL,NULL),
(75,'ROLE_PERMISSION_DELETE_','Permiso de Eliminar','HISTORIAL_FAMILIAR',NULL,NULL),

(76,'ROLE_PERMISSION_INDEX_','Permiso de Entrada','SIGNO_VITAL',NULL,NULL),
(77,'ROLE_PERMISSION_NEW_','Permiso de Creacion','SIGNO_VITAL',NULL,NULL),
(78,'ROLE_PERMISSION_SHOW_','Permiso de Ver ','SIGNO_VITAL',NULL,NULL),
(79,'ROLE_PERMISSION_EDIT_','Permiso de Editar ','SIGNO_VITAL',NULL,NULL),
(80,'ROLE_PERMISSION_DELETE_','Permiso de Eliminar','SIGNO_VITAL',NULL,NULL),

(81,'ROLE_PERMISSION_INDEX_','Permiso de Entrada','INGRESADO',NULL,NULL),
(82,'ROLE_PERMISSION_NEW_','Permiso de Creacion','INGRESADO',NULL,NULL),
(83,'ROLE_PERMISSION_SHOW_','Permiso de Ver ','INGRESADO',NULL,NULL),
(84,'ROLE_PERMISSION_EDIT_','Permiso de Editar ','INGRESADO',NULL,NULL),
(85,'ROLE_PERMISSION_DELETE_','Permiso de Eliminar','INGRESADO',NULL,NULL),

(86,'ROLE_PERMISSION_INDEX_','Permiso de Entrada','HISTORIA_MEDICA',NULL,NULL),
(87,'ROLE_PERMISSION_NEW_','Permiso de Creacion','HISTORIA_MEDICA',NULL,NULL),
(88,'ROLE_PERMISSION_SHOW_','Permiso de Ver ','HISTORIA_MEDICA',NULL,NULL),
(89,'ROLE_PERMISSION_EDIT_','Permiso de Editar ','HISTORIA_MEDICA',NULL,NULL),
(90,'ROLE_PERMISSION_DELETE_','Permiso de Eliminar','HISTORIA_MEDICA',NULL,NULL),

(91,'ROLE_PERMISSION_INDEX_','Permiso de Entrada','PLAN_TRATAMIENTO',NULL,NULL),
(92,'ROLE_PERMISSION_NEW_','Permiso de Creacion','PLAN_TRATAMIENTO',NULL,NULL),
(93,'ROLE_PERMISSION_SHOW_','Permiso de Ver ','PLAN_TRATAMIENTO',NULL,NULL),
(94,'ROLE_PERMISSION_EDIT_','Permiso de Editar ','PLAN_TRATAMIENTO',NULL,NULL),
(95,'ROLE_PERMISSION_DELETE_','Permiso de Eliminar','PLAN_TRATAMIENTO',NULL,NULL),

(96,'ROLE_PERMISSION_INDEX_','Permiso de Entrada','EXAMEN_SOLICITADO',NULL,NULL),
(97,'ROLE_PERMISSION_NEW_','Permiso de Creacion','EXAMEN_SOLICITADO',NULL,NULL),
(98,'ROLE_PERMISSION_SHOW_','Permiso de Ver ','EXAMEN_SOLICITADO',NULL,NULL),
(99,'ROLE_PERMISSION_EDIT_','Permiso de Editar ','EXAMEN_SOLICITADO',NULL,NULL),
(100,'ROLE_PERMISSION_DELETE_','Permiso de Eliminar','EXAMEN_SOLICITADO',NULL,NULL),

(101,'ROLE_PERMISSION_INDEX_','Permiso de Entrada','EXAMENES',NULL,NULL),
(102,'ROLE_PERMISSION_NEW_','Permiso de Creacion','EXAMENES',NULL,NULL),
(103,'ROLE_PERMISSION_SHOW_','Permiso de Ver ','EXAMENES',NULL,NULL),
(104,'ROLE_PERMISSION_EDIT_','Permiso de Editar ','EXAMENES',NULL,NULL),
(105,'ROLE_PERMISSION_DELETE_','Permiso de Eliminar','EXAMENES',NULL,NULL),

(106,'ROLE_PERMISSION_INDEX_','Permiso de Entrada','ANEXO',NULL,NULL),
(107,'ROLE_PERMISSION_NEW_','Permiso de Creacion','ANEXO',NULL,NULL),
(108,'ROLE_PERMISSION_SHOW_','Permiso de Ver ','ANEXO',NULL,NULL),
(109,'ROLE_PERMISSION_EDIT_','Permiso de Editar ','ANEXO',NULL,NULL),
(110,'ROLE_PERMISSION_DELETE_','Permiso de Eliminar','ANEXO',NULL,NULL);

INSERT INTO `rol` (`id`, `nombre_rol`, `descripcion`, `creado_en`, `actualizado_en`) VALUES
(1, 'ROLE_SA', 'Rol de Super Administrador', NULL, NULL),
(2, 'ROLE_DOCTOR', 'Rol de Doctor', NULL, NULL),
(3, 'ROLE_ARCHIVISTA', 'Rol de Archivista', NULL, NULL),
(4, 'ROLE_PACIENTE','Rol de paciente',NULL,NULL);


INSERT INTO `user` (`id`, `rol_id`, `clinica_id`, `email`, `password`, `nombres`, `apellidos`,`emergencia`,`planta`) VALUES
(1, 1, NULL, 'usuario1@usuario.com', '$2y$12$ZS8r3085MtvYxtWNgfQkYenZqjLkp1rqo3zUD1YL5MMA98ALooXai', 'Usuario 1', 'Usuario 1',0, 0),
(2, 2, 1, 'usuario2@usuario.com', '$2y$12$ZS8r3085MtvYxtWNgfQkYenZqjLkp1rqo3zUD1YL5MMA98ALooXai', 'Usuario 2', 'Usuario 2',0, 0),
(3, 3, 1, 'usuario3@usuario.com', '$2y$12$ZS8r3085MtvYxtWNgfQkYenZqjLkp1rqo3zUD1YL5MMA98ALooXai', 'Usuario 3', 'Usuario 3',0, 0),
(4, 4, 1, 'usuario4@usuario.com', '$2y$12$lQlW4LjkrLpCs2MlX77zxO80xF/.uNnPAnRtEXpVEEq7TMO1emtsS', 'Usuario 4', 'Usuario 4',0, 0),
(5, 4, 1, 'usuario5@usuario.com', '$2y$12$lQlW4LjkrLpCs2MlX77zxO80xF/.uNnPAnRtEXpVEEq7TMO1emtsS', 'Usuario 5', 'Usuario 5',0, 0);

/*INSERT INTO `user_especialidad` (`user_id`, `especialidad_id`) VALUES
(2, 1),
(2, 3),
(2, 5);*/

INSERT INTO `expediente`(`id`, `usuario_id`, `genero_id`, `numero_expediente`, `fecha_nacimiento`, `direccion`, `telefono`, `apellido_casada`, `estado_civil`, `habilitado`) VALUES
(1,4,1,'U0001-2019','1996-05-01 00:00:00', 'San Salvador','2257-7777','-','Soltero', 1),
(2,5,1,'U0002-2019','1994-09-03 00:00:00', 'San Salvador','2257-7777','-','Soltero', 1);

INSERT INTO `cita`(`id`,`usuario_id`, `expediente_id`, `fecha_reservacion`, `consulta_por`, `fecha_fin`) VALUES
(1,1,1,'2019-05-08 08:00:00','Motivo 1', '2019-05-08 08:30:00'),
(2,1,2,'2019-05-08 08:30:00','Motivo 2', '2019-05-08 09:00:00');

INSERT INTO `permiso_rol` (`rol_id`, `permiso_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),


(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),

(1, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15),

(1, 16),
(1, 17),
(1, 18),
(1, 19),
(1, 20),

(1, 21),
(1, 22),
(1, 23),
(1, 24),
(1, 25),

(1, 26),
(1, 27),
(1, 28),
(1, 29),
(1, 30),

(1, 31),
(1, 32),
(1, 33),
(1, 34),
(1, 35),

(1, 36),
(1, 37),
(1, 38),
(1, 39),
(1, 40),

(1, 41),
(1, 42),
(1, 43),
(1, 44),
(1, 45),

(1, 46),
(1, 47),
(1, 48),
(1, 49),
(1, 50),

(1, 51),
(1, 52),
(1, 53),
(1, 54),
(1, 55),

(1, 56),
(1, 57),
(1, 58),
(1, 59),
(1, 60),

(1, 61),
(1, 62),
(1, 63),
(1, 64),
(1, 65),

(1, 66),
(1, 67),
(1, 68),
(1, 69),
(1, 70),

(1, 71),
(1, 72),
(1, 73),
(1, 74),
(1, 75),

(1, 76),
(1, 77),
(1, 78),
(1, 79),
(1, 80),

(1, 81),
(1, 82), 
(1, 83), 
(1, 84), 
(1, 85),

(1, 86),
(1, 87),
(1, 88),
(1, 89),
(1, 90),

(1, 91),
(1, 92), 
(1, 93), 
(1, 94), 
(1, 95),

(1, 96),
(1, 97),
(1, 98),
(1, 99),
(1, 100),

(1, 101),
(1, 102), 
(1, 103), 
(1, 104), 
(1, 105),

(1, 106),
(1, 107),
(1, 108),
(1, 109),
(1, 110);