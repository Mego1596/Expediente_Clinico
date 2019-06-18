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