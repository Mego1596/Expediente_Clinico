INSERT INTO `clinica` (`id`, `nombre_clinica`, `direccion`, `telefono`, `email`, `creado_en`, `actualizado_en`) VALUES
(1, 'CLINICA 1', 'San Salvador', '2257-7777', 'gggg@gmail.com', NULL, NULL),
(2, 'CLINICA 2', 'San Salvador', '1111-1111', 'go14002@ues.edu.sv', NULL, NULL);


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
(101, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'GENERO', NULL, NULL),
(102, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'GENERO', NULL, NULL),
(103, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'GENERO', NULL, NULL),
(104, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'GENERO', NULL, NULL),
(105, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'GENERO', NULL, NULL),


(21, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'CLINICA', NULL, NULL),
(22, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'CLINICA', NULL, NULL),
(23, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'CLINICA', NULL, NULL),
(24, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'CLINICA', NULL, NULL),
(25, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'CLINICA', NULL, NULL),

(31, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'ESPECIALIDAD', NULL, NULL),
(32, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'ESPECIALIDAD', NULL, NULL),
(33, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'ESPECIALIDAD', NULL, NULL),
(34, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'ESPECIALIDAD', NULL, NULL),
(35, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'ESPECIALIDAD', NULL, NULL),

(86, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'EXPEDIENTE', NULL, NULL),
(87, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'EXPEDIENTE', NULL, NULL),
(88, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'EXPEDIENTE', NULL, NULL),
(89, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'EXPEDIENTE', NULL, NULL),
(90, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'EXPEDIENTE', NULL, NULL),

(131, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'PERMISOS_POR_ROL', NULL, NULL),
(132, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'PERMISOS_POR_ROL', NULL, NULL),
(133, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'PERMISOS_POR_ROL', NULL, NULL),
(134, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'PERMISOS_POR_ROL', NULL, NULL),
(135, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'PERMISOS_POR_ROL', NULL, NULL),

(141, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'ROL', NULL, NULL),
(142, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'ROL', NULL, NULL),
(143, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'ROL', NULL, NULL),
(144, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'ROL', NULL, NULL),
(145, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'ROL', NULL, NULL),

(166, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'USER', NULL, NULL),
(167, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'USER', NULL, NULL),
(168, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'USER', NULL, NULL),
(169, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'USER', NULL, NULL),
(170, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'USER', NULL, NULL),

(171, 'ROLE_PERMISSION_INDEX_', 'Permiso de Entrada', 'USUARIO_ESPECIALIDAD', NULL, NULL),
(172, 'ROLE_PERMISSION_NEW_', 'Permiso de Creacion', 'USUARIO_ESPECIALIDAD', NULL, NULL),
(173, 'ROLE_PERMISSION_SHOW_', 'Permiso de Ver ', 'USUARIO_ESPECIALIDAD', NULL, NULL),
(174, 'ROLE_PERMISSION_EDIT_', 'Permiso de Editar ', 'USUARIO_ESPECIALIDAD', NULL, NULL),
(175, 'ROLE_PERMISSION_DELETE_', 'Permiso de Eliminar', 'USUARIO_ESPECIALIDAD', NULL, NULL);


INSERT INTO `rol` (`id`, `nombre_rol`, `descripcion`, `creado_en`, `actualizado_en`) VALUES
(1, 'ROLE_SA', 'Rol de Super Administrador', NULL, NULL),
(2, 'ROLE_DOCTOR', 'Rol de Doctor', NULL, NULL),
(3, 'ROLE_ARCHIVISTA', 'Rol de Archivista', NULL, NULL);


INSERT INTO `user` (`id`, `rol_id`, `clinica_id`, `email`, `password`, `nombres`, `apellidos`) VALUES
(1, 1, NULL, 'usuario1@usuario.com', '$2y$12$ZS8r3085MtvYxtWNgfQkYenZqjLkp1rqo3zUD1YL5MMA98ALooXai', 'Usuario 1', 'Usuario 1'),
(2, 2, 1, 'usuario2@usuario.com', '$2y$10$IEVczcUKB2.4viorl9DD9.mp68nWB8wKj.P4gTnptpFwFoxru3HhS', 'Usuario 3', 'Usuario 2'),
(3, 3, 1, 'usuario3@usuario.com', '$2y$12$ZS8r3085MtvYxtWNgfQkYenZqjLkp1rqo3zUD1YL5MMA98ALooXai', 'Usuario 3', 'Usuario 3');

INSERT INTO `user_especialidad` (`user_id`, `especialidad_id`) VALUES
(2, 1),
(2, 3),
(2, 5);

INSERT INTO `permiso_rol` (`rol_id`, `permiso_id`) VALUES
(1, 101),
(1, 102),
(1, 103),
(1, 104),
(1, 105),


(1, 21),
(1, 22),
(1, 23),
(1, 24),
(1, 25),

(1, 31),
(1, 32),
(1, 33),
(1, 34),
(1, 35),

(1, 86),
(1, 87),
(1, 88),
(1, 89),
(1, 90),

(1, 131),
(1, 132),
(1, 133),
(1, 134),
(1, 135),

(1, 141),
(1, 142),
(1, 143),
(1, 144),
(1, 145),

(1, 166),
(1, 167),
(1, 168),
(1, 169),
(1, 170),

(1, 171),
(1, 172),
(1, 173),
(1, 174),
(1, 175);