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