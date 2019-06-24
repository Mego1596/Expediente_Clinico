
-------------------------------------------PROCEDIMIENTOS-----------------------------------------------------------

--- Procedimiento almacenado para obtener usuarios del sistema
DELIMITER //
CREATE procedure obtener_lista_usuarios ( IN ID_USUARIO_I INT, IN ID_CLINICA_I INT )
BEGIN
    IF (ID_USUARIO_I = -1) AND (ID_CLINICA_I = -1) THEN
        SELECT  u.id as id,
                CONCAT(    p.primer_nombre," "
                          ,IFNULL(p.segundo_nombre," ")
                          ," ",p.primer_apellido
                          ," ",IFNULL(p.segundo_apellido," ")
                     ) as nombre_completo,
                u.email as email, 
                c.nombre_clinica as clinica, 
                r.descripcion as rol, 
                u.is_active as activo
        FROM user as u,
             rol as r,
             clinica as c, 
             persona as p
        WHERE u.persona_id = p.id 
              AND u.clinica_id = c.id 
              AND u.rol_id = r.id 
              AND r.nombre_rol != "ROLE_PACIENTE";
    ELSE
        SELECT u.id as id,
               CONCAT(    p.primer_nombre," "
                          ,IFNULL(p.segundo_nombre," ")
                          ," ",p.primer_apellido
                          ," ",IFNULL(p.segundo_apellido," ")
                     ) as nombre_completo,
                u.email as email, 
                c.nombre_clinica as clinica, 
                r.descripcion as rol, 
                u.is_active as activo 
        FROM user as u,rol as r,
             clinica as c, 
             persona as p
        WHERE u.persona_id = p.id 
              AND u.clinica_id = c.id 
              AND u.rol_id = r.id 
              AND r.nombre_rol != "ROLE_SA" 
              AND r.nombre_rol != "ROLE_PACIENTE" 
              AND c.id= ID_CLINICA_I
              AND u.id != ID_USUARIO_I;
    END IF; 
END; //
DELIMITER ;

--- Obtener datos de usuario
DELIMITER //
CREATE procedure obtener_datos_usuario ( IN ID_USUARIO_I INT )
BEGIN
    SELECT 
        CONCAT(    p.primer_nombre,
                " " ,
                IFNULL(p.segundo_nombre," "),
                " " ,
                p.primer_apellido,
                " ",
                IFNULL(p.segundo_apellido," ")
            ) as nombre_completo, 
        r.descripcion as nombre_rol, 
        c.nombre_clinica as nombre_clinica
    FROM 
        user as u, 
        persona as p,  
        rol as r, 
        clinica as c  
    WHERE
        u.persona_id = p.id            
        AND u.rol_id = r.id
        AND u.clinica_id = c.id
        AND u.id = ID_USUARIO_I;
END; //
DELIMITER ;

--- Obtener lista de salas 
DELIMITER //
CREATE procedure obtener_lista_salas_clinica ( IN ID_CLINICA_I INT )
BEGIN
    SELECT * FROM sala WHERE clinica_id = ID_CLINICA_I;
END; //
DELIMITER ;

--- Obtener cantidad de habitaciones bajo esa sala
DELIMITER //
CREATE procedure cantidad_habitaciones_sala ( IN ID_SALA_I INT )
BEGIN
    SELECT 
        COUNT(*) as cuenta
    FROM 
        habitacion as h, 
        sala as s 
    WHERE 
        h.sala_id = s.id 
        AND s.id = ID_SALA_I;
END; //
DELIMITER ;

--- Procedimiento almacenado para obtener usuarios del sistema
DELIMITER //
CREATE procedure obtener_lista_expedientes ( IN ID_CLINICA_I INT )
BEGIN
    IF ID_CLINICA_I = -1 THEN
        SELECT 
            CONCAT(    p.primer_nombre,
                    " ",
                    IFNULL(p.segundo_nombre," "),
                    " ",
                    p.primer_apellido,
                    " ",IFNULL(p.segundo_apellido," ")
                ) as nombre_completo, 
            e.numero_expediente as expediente,
            e.id as id,
            e.habilitado,
            c.nombre_clinica 
        FROM 
            user as u,
            expediente as e,
            clinica c, 
            persona as p 
        WHERE 
            u.id = e.usuario_id 
            AND u.clinica_id = c.id 
            AND p.id = u.persona_id;
    ELSE
        SELECT 
            CONCAT(    p.primer_nombre,
                    " ",
                    IFNULL(p.segundo_nombre," "),
                    " ",
                    p.primer_apellido,
                    " ",
                    IFNULL(p.segundo_apellido," ")
                ) as nombre_completo, 
            e.numero_expediente as expediente,
            e.id as id,
            e.habilitado,
            c.nombre_clinica 
        FROM 
            user as u,
            expediente as e,
            clinica as c, 
            persona as p 
        WHERE 
            u.id = e.usuario_id 
            AND p.id = u.persona_id 
            AND u.clinica_id = c.id 
            AND clinica_id = ID_CLINICA_I;
    END IF; 
END; //
DELIMITER ;

--- Procedimiento para obtener el expediente maximo para una letra y anio
DELIMITER //
CREATE procedure obtener_ultimo_num_expediente ( IN ID_CLINICA_I INT, IN INICIO_I VARCHAR(200))
BEGIN
    SET @query = '
        SELECT 
            e.numero_expediente as expediente 
        FROM 
            expediente as e 
        WHERE 
            e.id 
            IN (
                SELECT 
                    MAX(exp.id) 
                FROM 
                    expediente as exp, 
                    user as u 
                WHERE 
                    u.id = exp.usuario_id 
        ';

    SET @query = CONCAT(@query, " AND u.clinica_id = ", ID_CLINICA_I);
    SET @query = CONCAT(@query, " AND exp.numero_expediente LIKE '", INICIO_I, "');");

    PREPARE stmt1 FROM @query;
    EXECUTE stmt1;
END; //
DELIMITER ;

--- Procedimiento para comprobar si el expediente ya existe o no (como metodo de corroboracion)
DELIMITER //
CREATE procedure comprobar_num_expediente ( IN ID_CLINICA_I INT, IN NUM_EXPEDIENTE VARCHAR(200))
BEGIN
    SELECT 
        e.numero_expediente 
    FROM 
        expediente as e,
        user as u 
    WHERE 
        e.usuario_id = u.id 
        AND u.clinica_id = ID_CLINICA_I 
        AND numero_expediente = NUM_EXPEDIENTE;
END; //
DELIMITER ;

--- Procedimiento para crear vista de paciente
DELIMITER //
CREATE procedure crear_vista_expediente ( IN ID_EXPEDIENTE_I INT)
BEGIN
    SET @query = CONCAT('CREATE VIEW expediente_paciente_', ID_EXPEDIENTE_I, ' AS ');
    SET @query = CONCAT(@query, ' SELECT ex.id as id_expediente,DATE(ex.fecha_nacimiento) as fecha_nacimiento_expediente, ( SELECT getEdad(', ID_EXPEDIENTE_I ,') ) as edad_expediente, ex.direccion as direccion_expediente,ex.estado_civil as estado_civil_expediete,ex.apellido_casada as apellido_casada_expediente, ex.creado_en as creado, ex.actualizado_en as actualizado, ex.numero_expediente as numero_expediente,ex.telefono as telefono_expediente, gen.descripcion as genero, u.email as correo_electronico, CONCAT(p.primer_nombre," ",IFNULL(p.segundo_nombre," ")," ",p.primer_apellido," ",IFNULL(p.segundo_apellido, " ") ) as nombre_completo');
    SET @query = CONCAT(@query, ' FROM expediente as ex,genero as gen, user as u, persona as p');
    SET @query = CONCAT(@query, ' WHERE ex.genero_id = gen.id AND ex.usuario_id = u.id  AND u.persona_id = p.id AND ex.id=', ID_EXPEDIENTE_I);
    
    PREPARE stmt1 FROM @query;
    EXECUTE stmt1;
END; //
DELIMITER ;

--- Procedimiento para obtener vista de paciente
DELIMITER //
CREATE procedure obtener_vista_expediente ( IN ID_EXPEDIENTE_I INT)
BEGIN
    SET @query = CONCAT('SELECT * FROM expediente_paciente_', ID_EXPEDIENTE_I);

    PREPARE stmt1 FROM @query;
    EXECUTE stmt1;
END; //
DELIMITER ;

--- Procedimiento para obtener el ingreso del paciente
DELIMITER //
CREATE procedure obtener_ingreso_clinica_paciente ( IN ID_EXPEDIENTE_I INT)
BEGIN
    SELECT * FROM ingresado WHERE fecha_salida IS NULL AND expediente_id = ID_EXPEDIENTE_I;
END; //
DELIMITER ;


--- Procedimiento para obtener historial de ingreso
DELIMITER //
CREATE procedure obtener_historial_ingreso ( IN ID_EXPEDIENTE_I INT)
BEGIN
    SELECT 
        CONCAT(    p.primer_nombre,
                   " ",
                   IFNULL(p.segundo_nombre," "),
                   " ",
                   p.primer_apellido,
                   " ",
                   IFNULL(p.segundo_apellido," ")
              ) as nombre_completo, 
        h_i.fecha_entrada as fechaEntrada, 
        h_i.fecha_salida as fechaSalida 
    FROM 
        historial_ingresado as h_i, 
        expediente as e,
        user as u, persona as p 
    WHERE
        h_i.expediente_id = e.id    AND
        h_i.usuario_id = u.id       AND
        u.persona_id = p.id         AND
        e.id = ID_EXPEDIENTE_I;
END; //
DELIMITER ;

--- Procedimiento para obtener los participantes (doctor y paciente) de una cita
DELIMITER //
CREATE procedure obtener_participantes_cita ( IN ID_CITA_I INT)
BEGIN
    SELECT 
        CONCAT(     p.primer_nombre,
                    " " ,
                    IFNULL(p.segundo_nombre," "),
                    " " ,
                    p.primer_apellido,
                    " ",
                    IFNULL(p.segundo_apellido," ")
            ) as nombre_completoD, 
        CONCAT(     p2.primer_nombre,
                    " " ,
                    IFNULL(p2.segundo_nombre," "),
                    " " ,
                    p2.primer_apellido,
                    " ",
                    IFNULL(p2.segundo_apellido," ")
            ) as nombre_completoP
    FROM 
        cita as c, 
        expediente as e, 
        user as u, 
        user as u2, 
        persona as p, 
        persona as p2 
    WHERE
        c.id = ID_CITA_I 
        AND c.usuario_id=u.id 
        AND u.persona_id=p.id
        AND c.expediente_id=e.id
        AND e.usuario_id=u2.id 
        AND u2.persona_id=p2.id;
END; //
DELIMITER ;

--- Procedimiento para obtener la lista de habitaciones disponibles de la sala
DELIMITER //
CREATE procedure obtener_habitaciones_disponibles ( IN ID_CLINICA_I INT, IN ID_SALA_I INT)
BEGIN
    SELECT 
        DISTINCT habitacion.* 
    FROM 
        habitacion,
        clinica,
        sala, 
        camilla 
    WHERE
        camilla.habitacion_id = habitacion.id           
        AND habitacion.sala_id = sala.id                 
        AND sala.clinica_id = clinica.id              
        AND clinica.id = ID_CLINICA_I   
        AND sala.id = ID_SALA_I      
        AND camilla.id NOT IN (
            SELECT camilla_id FROM ingresado WHERE fecha_salida IS NULL
        );
END; //
DELIMITER ;

--- Procedimiento para obtener las citas del paciente y si esta tiene historial medico
DELIMITER //
CREATE procedure obtener_citas_historial ( IN ID_EXPEDIENTE_I INT)
BEGIN
    SELECT 
        c.id,c.consulta_por as consultaPor,c.fecha_reservacion as fechaReservacion,
        c.fecha_fin as fechaFin, COUNT(h.id) as tieneHistoria 
    FROM 
        cita as c 
                LEFT OUTER JOIN 
                            historia_medica as h 
                ON c.id = h.cita_id 
    WHERE
        c.expediente_id= ID_EXPEDIENTE_I
    GROUP BY c.id;
END; //
DELIMITER ;

---------------------------------------------FUNCIONES---------------------------------------------------------------

--- Funcion para obtener si el email se encuentra duplicado
DELIMITER //
CREATE FUNCTION email_duplicado(ID_USUARIO_I INT, EMAIL_I VARCHAR(500)) RETURNS INT
BEGIN
  DECLARE id_usuario INT;
  
  SELECT id INTO id_usuario FROM user WHERE id != ID_USUARIO_I AND  email = EMAIL_I;
  IF id_usuario IS NULL THEN
      RETURN 0;
  END IF;

  RETURN 1;
END; //
DELIMITER ;

--- Funcion para obtener si la clinica tiene una historia medica
DELIMITER //
CREATE FUNCTION cita_tiene_historia_medica(ID_CITA_I INT) RETURNS INT
BEGIN
    DECLARE historia_id INT;
  
    SELECT 
        h.id 
    INTO
        historia_id
    FROM 
        historia_medica as h 
    WHERE
        h.cita_id = ID_CITA_I;

  IF historia_id IS NULL THEN
      RETURN 0;
  END IF;

  RETURN 1;
END; //
DELIMITER;

---------------------------------------------TRIGGERS---------------------------------------------------------------

--- Trigger para registrar la ultima fecha de actualizacion cada vez que se actualiza
CREATE TRIGGER `actualizarCita` 
BEFORE UPDATE 
ON `cita` FOR EACH ROW 
BEGIN 
    SET NEW.actualizado_en = NOW(); 
END

--- Trigger para registrar la fecha de creacion cada vez que se crea
CREATE TRIGGER `llenarCita` 
BEFORE INSERT 
ON `cita` FOR EACH ROW 
BEGIN 
    SET NEW.creado_en = NOW(); 
    SET NEW.actualizado_en = NOW(); 
END

--- Trigger para eliminar el registro de ingresado y almacenarlo en el historial de ingresados
CREATE TRIGGER `llenadoHistorial` 
AFTER DELETE 
ON `ingresado` FOR EACH ROW 
BEGIN
    INSERT INTO `historial_ingresado`(expediente_id, usuario_id, fecha_entrada, fecha_salida) 
    VALUES(OLD.expediente_id, OLD.usuario_id, OLD.`fecha_ingreso`, NOW()); 
END