<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190607030751 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE anexo (id INT AUTO_INCREMENT NOT NULL, examen_solicitado_id INT NOT NULL, nombre_archivo LONGTEXT NOT NULL, ruta LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, INDEX IDX_EXAMEN_SOLICITADO_1 (examen_solicitado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE camilla (id INT AUTO_INCREMENT NOT NULL, habitacion_id INT DEFAULT NULL, numero_camilla INT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, INDEX IDX_HABITACION_1 (habitacion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE cita (id INT AUTO_INCREMENT NOT NULL, usuario_id INT DEFAULT NULL, expediente_id INT NOT NULL, fecha_reservacion DATETIME NOT NULL, consulta_por LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, fecha_fin DATETIME DEFAULT NULL, INDEX IDX_USUARIO_1 (usuario_id), INDEX IDX_EXPEDIENTE_1 (expediente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE clinica (id INT AUTO_INCREMENT NOT NULL, nombre_clinica LONGTEXT NOT NULL, direccion LONGTEXT NOT NULL, telefono LONGTEXT NOT NULL, email LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE diagnostico (id INT AUTO_INCREMENT NOT NULL, descripcion LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, codigo_categoria VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE especialidad (id INT AUTO_INCREMENT NOT NULL, nombre_especialidad LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE examen_heces_macroscopico (id INT AUTO_INCREMENT NOT NULL, examen_solicitado_id INT NOT NULL, aspecto LONGTEXT NOT NULL, consistencia LONGTEXT NOT NULL, color LONGTEXT NOT NULL, olor LONGTEXT NOT NULL, presencia_de_sangre LONGTEXT NOT NULL, restos_alimenticios LONGTEXT NOT NULL, presencia_moco LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_EXAMEN_SOLICITADO_2 (examen_solicitado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE examen_heces_microscopico (id INT AUTO_INCREMENT NOT NULL, examen_solicitado_id INT NOT NULL, hematies LONGTEXT NOT NULL, leucocito LONGTEXT NOT NULL, flora_bacteriana LONGTEXT NOT NULL, levadura LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_EXAMEN_SOLICITADO_3 (examen_solicitado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE examen_heces_quimico (id INT AUTO_INCREMENT NOT NULL, examen_solicitado_id INT NOT NULL, ph DOUBLE PRECISION NOT NULL, azucares_reductores LONGTEXT NOT NULL, sangre_oculta LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_EXAMEN_SOLICITADO_4 (examen_solicitado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE examen_hematologico (id INT AUTO_INCREMENT NOT NULL, examen_solicitado_id INT NOT NULL, tipo_serie LONGTEXT NOT NULL, unidad LONGTEXT NOT NULL, valor_referencia LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, INDEX IDX_EXAMEN_SOLICITADO_5 (examen_solicitado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE examen_orina_cristaluria (id INT AUTO_INCREMENT NOT NULL, examen_solicitado_id INT NOT NULL, uratos_amorfos LONGTEXT NOT NULL, acido_urico LONGTEXT NOT NULL, oxalatos_calcicos LONGTEXT NOT NULL, fosfatos_amorfos LONGTEXT NOT NULL, fosfatos_calcicos LONGTEXT NOT NULL, fosfatos_amonicos LONGTEXT NOT NULL, riesgo_litogenico LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_EXAMEN_SOLICITADO_6 (examen_solicitado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE examen_orina_macroscopico (id INT AUTO_INCREMENT NOT NULL, examen_solicitado_id INT NOT NULL, color LONGTEXT NOT NULL, aspecto LONGTEXT NOT NULL, sedimento LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_EXAMEN_SOLICITADO_7 (examen_solicitado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE examen_orina_microscopico (id INT AUTO_INCREMENT NOT NULL, examen_solicitado_id INT NOT NULL, uretral LONGTEXT NOT NULL, urotelio LONGTEXT NOT NULL, renal LONGTEXT NOT NULL, leucocitos LONGTEXT NOT NULL, piocitos LONGTEXT NOT NULL, eritrocitos LONGTEXT NOT NULL, bacteria LONGTEXT NOT NULL, parasitos LONGTEXT NOT NULL, funguria LONGTEXT NOT NULL, filamento_de_mucina LONGTEXT NOT NULL, proteina_uromocoide LONGTEXT NOT NULL, cilindros LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_EXAMEN_SOLICITADO_8 (examen_solicitado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE examen_orina_quimico (id INT AUTO_INCREMENT NOT NULL, examen_solicitado_id INT NOT NULL, densidad DOUBLE PRECISION NOT NULL, ph DOUBLE PRECISION NOT NULL, glucosa LONGTEXT NOT NULL, proteinas LONGTEXT NOT NULL, hemoglobina LONGTEXT NOT NULL, cuerpo_cetonico LONGTEXT NOT NULL, pigmento_biliar LONGTEXT NOT NULL, urobilinogeno LONGTEXT NOT NULL, nitritos LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_EXAMEN_SOLICITADO_9 (examen_solicitado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE examen_quimica_sanguinea (id INT AUTO_INCREMENT NOT NULL, examen_solicitado_id INT NOT NULL, parametro LONGTEXT NOT NULL, resultado DOUBLE PRECISION NOT NULL, comentario LONGTEXT NOT NULL, unidades LONGTEXT NOT NULL, rango LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, INDEX IDX_EXAMEN_SOLICITADO_10 (examen_solicitado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE examen_solicitado (id INT AUTO_INCREMENT NOT NULL, cita_id INT DEFAULT NULL, tipo_examen LONGTEXT NOT NULL, categoria LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, INDEX IDX_CITA_1 (cita_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE expediente (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, genero_id INT NOT NULL, numero_expediente LONGTEXT NOT NULL, fecha_nacimiento DATETIME NOT NULL, direccion LONGTEXT NOT NULL, telefono LONGTEXT NOT NULL, apellido_casada LONGTEXT DEFAULT NULL, estado_civil LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, habilitado TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_USUARIO_2 (usuario_id), INDEX IDX_GENERO_1 (genero_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE familiar (id INT AUTO_INCREMENT NOT NULL, primer_nombre VARCHAR(255) NOT NULL,  segundo_nombre VARCHAR(255) DEFAULT NULL, primer_apellido VARCHAR(255) NOT NULL, segundo_apellido VARCHAR(255) DEFAULT NULL, fecha_nacimiento DATETIME NOT NULL, telefono LONGTEXT NOT NULL, descripcion LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE familiares_expediente (id INT AUTO_INCREMENT NOT NULL, familiar_id INT NOT NULL, expediente_id INT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, responsable TINYINT(1) NOT NULL, INDEX IDX_FAMILIAR_1 (familiar_id), INDEX IDX_EXPEDIENTE_2 (expediente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE genero (id INT AUTO_INCREMENT NOT NULL, descripcion LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE habitacion (id INT AUTO_INCREMENT NOT NULL, sala_id INT NOT NULL, tipo_habitacion_id INT DEFAULT NULL, numero_habitacion INT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, INDEX IDX_SALA_1 (sala_id), INDEX IDX_TIPO_HABITACION_1 (tipo_habitacion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE historial_familiar (id INT AUTO_INCREMENT NOT NULL, familiar_id INT NOT NULL, descripcion LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, INDEX IDX_FAMILIAR_2 (familiar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE historial_propio (id INT AUTO_INCREMENT NOT NULL, expediente_id INT NOT NULL, descripcion LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, INDEX IDX_EXPEDIENTE_3 (expediente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE historia_medica (id INT AUTO_INCREMENT NOT NULL, cita_id INT NOT NULL, diagnostico_id INT NOT NULL, consulta_por LONGTEXT NOT NULL, signos LONGTEXT NOT NULL, sintomas LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, codigo_especifico VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_CITA_2 (cita_id), UNIQUE INDEX UNIQ_DIAGNOSTICO_1 (diagnostico_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE ingresado (id INT AUTO_INCREMENT NOT NULL, camilla_id INT NOT NULL, expediente_id INT NOT NULL, usuario_id INT NOT NULL, fecha_ingreso DATETIME NOT NULL, fecha_salida DATETIME DEFAULT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, INDEX IDX_CAMILLA_1 (camilla_id), INDEX IDX_EXPEDIENTE_4 (expediente_id), INDEX IDX_USUARIO_3 (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE permiso (id INT AUTO_INCREMENT NOT NULL, permiso VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL, nombre_tabla VARCHAR(255) NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE permiso_rol (permiso_id INT NOT NULL, rol_id INT NOT NULL, INDEX IDX_PERMISO_1 (permiso_id), INDEX IDX_ROL_1 (rol_id), PRIMARY KEY(permiso_id, rol_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE persona (id INT AUTO_INCREMENT NOT NULL, primer_nombre VARCHAR(255) NOT NULL, segundo_nombre VARCHAR(255) DEFAULT NULL, primer_apellido VARCHAR(255) NOT NULL, segundo_apellido VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE plan_tratamiento (id INT AUTO_INCREMENT NOT NULL, historia_medica_id INT NOT NULL, dosis LONGTEXT NOT NULL, medicamento LONGTEXT NOT NULL, frecuencia LONGTEXT NOT NULL, tipo_medicamento LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, INDEX IDX_HISTORIA_MEDICA_1 (historia_medica_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE rol (id INT AUTO_INCREMENT NOT NULL, nombre_rol VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE sala (id INT AUTO_INCREMENT NOT NULL, clinica_id INT NOT NULL, nombre_sala LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, INDEX IDX_CLINICA_1 (clinica_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE signo_vital (id INT AUTO_INCREMENT NOT NULL, cita_id INT NOT NULL, peso DOUBLE PRECISION NOT NULL, temperatura DOUBLE PRECISION NOT NULL, estatura DOUBLE PRECISION NOT NULL, presion_arterial DOUBLE PRECISION NOT NULL, ritmo_cardiaco DOUBLE PRECISION NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_CITA_3 (cita_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE tipo_habitacion (id INT AUTO_INCREMENT NOT NULL, tipo_habitacion LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, rol_id INT NOT NULL, clinica_id INT DEFAULT NULL, usuario_especialidades_id INT DEFAULT NULL, persona_id INT NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, emergencia TINYINT(1) DEFAULT NULL, planta TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_EMAIL_1 (email), INDEX IDX_ROL_2 (rol_id), INDEX IDX_CLINICA_2 (clinica_id), INDEX IDX_ESPECIALIDAD_1 (usuario_especialidades_id), UNIQUE INDEX UNIQ_PERSONA_1 (persona_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('ALTER TABLE anexo ADD CONSTRAINT FK_EXAMEN_SOLICITADO_1 FOREIGN KEY (examen_solicitado_id) REFERENCES examen_solicitado (id)');

        $this->addSql('ALTER TABLE examen_heces_macroscopico ADD CONSTRAINT FK_EXAMEN_SOLICITADO_2 FOREIGN KEY (examen_solicitado_id) REFERENCES examen_solicitado (id)');
        
        $this->addSql('ALTER TABLE examen_heces_microscopico ADD CONSTRAINT FK_EXAMEN_SOLICITADO_3 FOREIGN KEY (examen_solicitado_id) REFERENCES examen_solicitado (id)');
        
        $this->addSql('ALTER TABLE examen_heces_quimico ADD CONSTRAINT FK_EXAMEN_SOLICITADO_4 FOREIGN KEY (examen_solicitado_id) REFERENCES examen_solicitado (id)');
        
        $this->addSql('ALTER TABLE examen_hematologico ADD CONSTRAINT FK_EXAMEN_SOLICITADO_5 FOREIGN KEY (examen_solicitado_id) REFERENCES examen_solicitado (id) ON DELETE CASCADE');
        
        $this->addSql('ALTER TABLE examen_orina_cristaluria ADD CONSTRAINT FK_EXAMEN_SOLICITADO_6 FOREIGN KEY (examen_solicitado_id) REFERENCES examen_solicitado (id)');
        
        $this->addSql('ALTER TABLE examen_orina_macroscopico ADD CONSTRAINT FK_EXAMEN_SOLICITADO_7 FOREIGN KEY (examen_solicitado_id) REFERENCES examen_solicitado (id)');
        
        $this->addSql('ALTER TABLE examen_orina_microscopico ADD CONSTRAINT FK_EXAMEN_SOLICITADO_8 FOREIGN KEY (examen_solicitado_id) REFERENCES examen_solicitado (id)');
        
        $this->addSql('ALTER TABLE examen_orina_quimico ADD CONSTRAINT FK_EXAMEN_SOLICITADO_9 FOREIGN KEY (examen_solicitado_id) REFERENCES examen_solicitado (id)');
        
        $this->addSql('ALTER TABLE examen_quimica_sanguinea ADD CONSTRAINT FK_EXAMEN_SOLICITADO_10 FOREIGN KEY (examen_solicitado_id) REFERENCES examen_solicitado (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE camilla ADD CONSTRAINT FK_HABITACION_1 FOREIGN KEY (habitacion_id) REFERENCES habitacion (id)');

        $this->addSql('ALTER TABLE historia_medica ADD CONSTRAINT FK_DIAGNOSTICO_1 FOREIGN KEY (diagnostico_id) REFERENCES diagnostico (id)');

        $this->addSql('ALTER TABLE habitacion ADD CONSTRAINT FK_SALA_1 FOREIGN KEY (sala_id) REFERENCES sala (id)');

        $this->addSql('ALTER TABLE habitacion ADD CONSTRAINT FK_TIPO_HABITACION_1 FOREIGN KEY (tipo_habitacion_id) REFERENCES tipo_habitacion (id)');

        $this->addSql('ALTER TABLE expediente ADD CONSTRAINT FK_GENERO_1 FOREIGN KEY (genero_id) REFERENCES genero (id)');

        $this->addSql('ALTER TABLE ingresado ADD CONSTRAINT FK_CAMILLA_1 FOREIGN KEY (camilla_id) REFERENCES camilla (id)');

        $this->addSql('ALTER TABLE permiso_rol ADD CONSTRAINT FK_PERMISO_1 FOREIGN KEY (permiso_id) REFERENCES permiso (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE cita ADD CONSTRAINT FK_USUARIO_1 FOREIGN KEY (usuario_id) REFERENCES user (id)');

        $this->addSql('ALTER TABLE expediente ADD CONSTRAINT FK_USUARIO_2 FOREIGN KEY (usuario_id) REFERENCES user (id)');

        $this->addSql('ALTER TABLE ingresado ADD CONSTRAINT FK_USUARIO_3 FOREIGN KEY (usuario_id) REFERENCES user (id)');

        $this->addSql('ALTER TABLE cita ADD CONSTRAINT FK_EXPEDIENTE_1 FOREIGN KEY (expediente_id) REFERENCES expediente (id)');

        $this->addSql('ALTER TABLE familiares_expediente ADD CONSTRAINT FK_EXPEDIENTE_2 FOREIGN KEY (expediente_id) REFERENCES expediente (id)');

        $this->addSql('ALTER TABLE historial_propio ADD CONSTRAINT FK_EXPEDIENTE_3 FOREIGN KEY (expediente_id) REFERENCES expediente (id)');
        $this->addSql('ALTER TABLE ingresado ADD CONSTRAINT FK_EXPEDIENTE_4 FOREIGN KEY (expediente_id) REFERENCES expediente (id)');

        $this->addSql('ALTER TABLE examen_solicitado ADD CONSTRAINT FK_CITA_1 FOREIGN KEY (cita_id) REFERENCES cita (id) ON DELETE CASCADE');
        
        $this->addSql('ALTER TABLE historia_medica ADD CONSTRAINT FK_CITA_2 FOREIGN KEY (cita_id) REFERENCES cita (id)');

        $this->addSql('ALTER TABLE signo_vital ADD CONSTRAINT FK_CITA_3 FOREIGN KEY (cita_id) REFERENCES cita (id)');
        
        $this->addSql('ALTER TABLE familiares_expediente ADD CONSTRAINT FK_FAMILIAR_1 FOREIGN KEY (familiar_id) REFERENCES familiar (id)');
        
        $this->addSql('ALTER TABLE historial_familiar ADD CONSTRAINT FK_FAMILIAR_2 FOREIGN KEY (familiar_id) REFERENCES familiar (id)');

        $this->addSql('ALTER TABLE permiso_rol ADD CONSTRAINT FK_ROL_1 FOREIGN KEY (rol_id) REFERENCES rol (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_ROL_2 FOREIGN KEY (rol_id) REFERENCES rol (id)');

        $this->addSql('ALTER TABLE plan_tratamiento ADD CONSTRAINT FK_HISTORIA_MEDICA_1 FOREIGN KEY (historia_medica_id) REFERENCES historia_medica (id)');

        $this->addSql('ALTER TABLE sala ADD CONSTRAINT FK_CLINICA_1 FOREIGN KEY (clinica_id) REFERENCES clinica (id)');
        
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_CLINICA_2 FOREIGN KEY (clinica_id) REFERENCES clinica (id)');
        
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_ESPECIALIDAD_1 FOREIGN KEY (usuario_especialidades_id) REFERENCES especialidad (id)');
        
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_PERSONA_1 FOREIGN KEY (persona_id) REFERENCES persona (id)');


        //FUNCTION GET EDAD

        $this->addSql('CREATE DEFINER=`root`@`localhost` FUNCTION `getEdad`(`paciente` INT UNSIGNED) RETURNS INT(3) DETERMINISTIC NO SQL SQL SECURITY DEFINER BEGIN DECLARE salida INT DEFAULT 0; SET salida =(SELECT TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) AS edad from expediente WHERE id = paciente) ; RETURN salida; END');

        //CREACION DE DISPARADORES
        //CLINICA
        $this->addSql('CREATE TRIGGER `llenarClinica` BEFORE INSERT ON `clinica` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarClinica` BEFORE UPDATE ON `clinica` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');
        
        //SALA
        $this->addSql('CREATE TRIGGER `llenarSala` BEFORE INSERT ON `sala` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarSala` BEFORE UPDATE ON `sala` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //TIPO_HABITACION
        $this->addSql('CREATE TRIGGER `llenarTipoHabitacion` BEFORE INSERT ON `tipo_habitacion` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarTipoHabitacion` BEFORE UPDATE ON `tipo_habitacion` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //HABITACION
        $this->addSql('CREATE TRIGGER `llenarHabitacion` BEFORE INSERT ON `habitacion` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarHabitacion` BEFORE UPDATE ON `habitacion` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //CAMILLA
        $this->addSql('CREATE TRIGGER `llenarCamilla` BEFORE INSERT ON `camilla` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarCamilla` BEFORE UPDATE ON `camilla` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //INGRESADO
        $this->addSql('CREATE TRIGGER `llenarIngresado` BEFORE INSERT ON `ingresado` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarIngresado` BEFORE UPDATE ON `ingresado` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //ROL
        $this->addSql('CREATE TRIGGER `llenarRol` BEFORE INSERT ON `rol` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarRol` BEFORE UPDATE ON `rol` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //PERMISO
        $this->addSql('CREATE TRIGGER `llenarPermiso` BEFORE INSERT ON `permiso` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarPermiso` BEFORE UPDATE ON `permiso` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //ESPECIALIDAD
        $this->addSql('CREATE TRIGGER `llenarEspecialidad` BEFORE INSERT ON `especialidad` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarEspecialidad` BEFORE UPDATE ON `especialidad` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //EXPEDIENTE
        $this->addSql('CREATE TRIGGER `llenarExpediente` BEFORE INSERT ON `expediente` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarExpediente` BEFORE UPDATE ON `expediente` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //HISTORIAL_PROPIO
        $this->addSql('CREATE TRIGGER `llenarHistorialPropio` BEFORE INSERT ON `historial_propio` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarHistorialPropio` BEFORE UPDATE ON `historial_propio` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //FAMILIARES_EXPEDIENTE
        $this->addSql('CREATE TRIGGER `llenarFamiliaresExpediente` BEFORE INSERT ON `familiares_expediente` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarFamiliaresExpediente` BEFORE UPDATE ON `familiares_expediente` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //FAMILIAR
        $this->addSql('CREATE TRIGGER `llenarFamiliar` BEFORE INSERT ON `familiar` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarFamiliar` BEFORE UPDATE ON `familiar` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //HISTORIAL_FAMILIAR
        $this->addSql('CREATE TRIGGER `llenarHistorialFamiliar` BEFORE INSERT ON `historial_familiar` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarHistorialFamiliar` BEFORE UPDATE ON `historial_familiar` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //CITA
        $this->addSql('CREATE TRIGGER `llenarCita` BEFORE INSERT ON `cita` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarCita` BEFORE UPDATE ON `cita` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //SIGNO_VITAL
        $this->addSql('CREATE TRIGGER `llenarSignoVital` BEFORE INSERT ON `signo_vital` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarSignoVital` BEFORE UPDATE ON `signo_vital` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //HISTORIA_MEDICA
        $this->addSql('CREATE TRIGGER `llenarHistoriaMedica` BEFORE INSERT ON `historia_medica` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarHistoriaMedica` BEFORE UPDATE ON `historia_medica` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //PLAN_TRATAMIENTO
        $this->addSql('CREATE TRIGGER `llenarPlanTratamiento` BEFORE INSERT ON `plan_tratamiento` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarPlanTratamiento` BEFORE UPDATE ON `plan_tratamiento` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //GENERO
        $this->addSql('CREATE TRIGGER `llenarGenero` BEFORE INSERT ON `genero` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarGenero` BEFORE UPDATE ON `genero` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //DIAGNOSTICO
        $this->addSql('CREATE TRIGGER `llenarDiagnostico` BEFORE INSERT ON `diagnostico` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarDiagnostico` BEFORE UPDATE ON `diagnostico` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //EXAMEN_SOLICITADO
        $this->addSql('CREATE TRIGGER `llenarExamenSolicitado` BEFORE INSERT ON `examen_solicitado` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarExamenSolicitado` BEFORE UPDATE ON `examen_solicitado` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //ANEXO
        $this->addSql('CREATE TRIGGER `llenarAnexo` BEFORE INSERT ON `anexo` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarAnexo` BEFORE UPDATE ON `anexo` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //EXAMEN_HEMATOLOGICO
        $this->addSql('CREATE TRIGGER `llenarExamenHematologico` BEFORE INSERT ON `examen_hematologico` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarExamenHematologico` BEFORE UPDATE ON `examen_hematologico` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //EXAMEN_QUIMICA_SANGUINEA
        $this->addSql('CREATE TRIGGER `llenarExamenQuimicaSanguinea` BEFORE INSERT ON `examen_quimica_sanguinea` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarExamenQuimicaSanguinea` BEFORE UPDATE ON `examen_quimica_sanguinea` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //EXAMEN_HECES_MICROSCOPICO
        $this->addSql('CREATE TRIGGER `llenarExamenHecesMicroscopico` BEFORE INSERT ON `examen_heces_microscopico` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarExamenHecesMicroscopico` BEFORE UPDATE ON `examen_heces_microscopico` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //EXAMEN_HECES_MACROSCOPICO
        $this->addSql('CREATE TRIGGER `llenarExamenHecesMacroscopico` BEFORE INSERT ON `examen_heces_macroscopico` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarExamenHecesMacroscopico` BEFORE UPDATE ON `examen_heces_macroscopico` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //EXAMEN_HECES_QUIMICO
        $this->addSql('CREATE TRIGGER `llenarExamenHecesQuimico` BEFORE INSERT ON `examen_heces_quimico` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarExamenHecesQuimico` BEFORE UPDATE ON `examen_heces_quimico` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //EXAMEN_ORINA_CRISTALURIA
        $this->addSql('CREATE TRIGGER `llenarOrinaCristaluria` BEFORE INSERT ON `examen_orina_cristaluria` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarOrinaCristaluria` BEFORE UPDATE ON `examen_orina_cristaluria` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //EXAMEN_ORINA_QUIMICO
        $this->addSql('CREATE TRIGGER `llenarOrinaQuimico` BEFORE INSERT ON `examen_orina_quimico` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarOrinaQuimico` BEFORE UPDATE ON `examen_orina_quimico` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //EXAMEN_ORINA_MICROSCOPICO
        $this->addSql('CREATE TRIGGER `llenarOrinaMicroscopico` BEFORE INSERT ON `examen_orina_microscopico` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarOrinaMicroscopico` BEFORE UPDATE ON `examen_orina_microscopico` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');

        //EXAMEN_ORINA_MACROSCOPICO
        $this->addSql('CREATE TRIGGER `llenarOrinaMacroscopico` BEFORE INSERT ON `examen_orina_macroscopico` FOR EACH ROW BEGIN SET NEW.creado_en = NOW(); SET NEW.actualizado_en = NOW(); END');
        $this->addSql('CREATE TRIGGER `actualizarOrinaMacroscopico` BEFORE UPDATE ON `examen_orina_macroscopico` FOR EACH ROW BEGIN SET NEW.actualizado_en = NOW(); END');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ingresado DROP FOREIGN KEY FK_CAMILLA_1');
        $this->addSql('ALTER TABLE examen_solicitado DROP FOREIGN KEY FK_CITA_1');
        $this->addSql('ALTER TABLE historia_medica DROP FOREIGN KEY FK_DIAGNOSTICO_1');
        $this->addSql('ALTER TABLE signo_vital DROP FOREIGN KEY FK_CITA_3');
        $this->addSql('ALTER TABLE sala DROP FOREIGN KEY FK_CLINICA_1');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_CLINICA_2');
        $this->addSql('ALTER TABLE historia_medica DROP FOREIGN KEY FK_CITA_2');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_ROL_2');
        $this->addSql('ALTER TABLE anexo DROP FOREIGN KEY FK_EXAMEN_SOLICITADO_1');
        $this->addSql('ALTER TABLE examen_heces_macroscopico DROP FOREIGN KEY FK_EXAMEN_SOLICITADO_2');
        $this->addSql('ALTER TABLE examen_heces_microscopico DROP FOREIGN KEY FK_EXAMEN_SOLICITADO_3');
        $this->addSql('ALTER TABLE examen_heces_quimico DROP FOREIGN KEY FK_EXAMEN_SOLICITADO_4');
        $this->addSql('ALTER TABLE examen_hematologico DROP FOREIGN KEY FK_EXAMEN_SOLICITADO_5');
        $this->addSql('ALTER TABLE examen_orina_cristaluria DROP FOREIGN KEY FK_EXAMEN_SOLICITADO_6');
        $this->addSql('ALTER TABLE examen_orina_macroscopico DROP FOREIGN KEY FK_EXAMEN_SOLICITADO_7');
        $this->addSql('ALTER TABLE examen_orina_microscopico DROP FOREIGN KEY FK_EXAMEN_SOLICITADO_8');
        $this->addSql('ALTER TABLE examen_orina_quimico DROP FOREIGN KEY FK_EXAMEN_SOLICITADO_9');
        $this->addSql('ALTER TABLE examen_quimica_sanguinea DROP FOREIGN KEY FK_EXAMEN_SOLICITADO_10');
        $this->addSql('ALTER TABLE cita DROP FOREIGN KEY FK_USUARIO_1');
        $this->addSql('ALTER TABLE familiares_expediente DROP FOREIGN KEY FK_EXPEDIENTE_2');
        $this->addSql('ALTER TABLE historial_propio DROP FOREIGN KEY FK_EXPEDIENTE_3');
        $this->addSql('ALTER TABLE ingresado DROP FOREIGN KEY FK_USUARIO_3');
        $this->addSql('ALTER TABLE familiares_expediente DROP FOREIGN KEY FK_FAMILIAR_1');
        $this->addSql('ALTER TABLE historial_familiar DROP FOREIGN KEY FK_FAMILIAR_2');
        $this->addSql('ALTER TABLE expediente DROP FOREIGN KEY FK_GENERO_1');
        $this->addSql('ALTER TABLE camilla DROP FOREIGN KEY FK_HABITACION_1');
        $this->addSql('ALTER TABLE plan_tratamiento DROP FOREIGN KEY FK_HISTORIA_MEDICA_1');
        $this->addSql('ALTER TABLE permiso_rol DROP FOREIGN KEY FK_PERMISO_1');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_ESPECIALIDAD_1');
        $this->addSql('ALTER TABLE permiso_rol DROP FOREIGN KEY FK_ROL_1');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_PERSONA_1');
        $this->addSql('ALTER TABLE habitacion DROP FOREIGN KEY FK_SALA_1');
        $this->addSql('ALTER TABLE habitacion DROP FOREIGN KEY FK_TIPO_HABITACION_1');
        $this->addSql('ALTER TABLE cita DROP FOREIGN KEY FK_EXPEDIENTE_1');
        $this->addSql('ALTER TABLE expediente DROP FOREIGN KEY FK_USUARIO_2');
        $this->addSql('ALTER TABLE ingresado DROP FOREIGN KEY FK_EXPEDIENTE_4');
        $this->addSql('DROP TABLE anexo');
        $this->addSql('DROP TABLE camilla');
        $this->addSql('DROP TABLE cita');
        $this->addSql('DROP TABLE clinica');
        $this->addSql('DROP TABLE diagnostico');
        $this->addSql('DROP TABLE especialidad');
        $this->addSql('DROP TABLE examen_heces_macroscopico');
        $this->addSql('DROP TABLE examen_heces_microscopico');
        $this->addSql('DROP TABLE examen_heces_quimico');
        $this->addSql('DROP TABLE examen_hematologico');
        $this->addSql('DROP TABLE examen_orina_cristaluria');
        $this->addSql('DROP TABLE examen_orina_macroscopico');
        $this->addSql('DROP TABLE examen_orina_microscopico');
        $this->addSql('DROP TABLE examen_orina_quimico');
        $this->addSql('DROP TABLE examen_quimica_sanguinea');
        $this->addSql('DROP TABLE examen_solicitado');
        $this->addSql('DROP TABLE expediente');
        $this->addSql('DROP TABLE familiar');
        $this->addSql('DROP TABLE familiares_expediente');
        $this->addSql('DROP TABLE genero');
        $this->addSql('DROP TABLE habitacion');
        $this->addSql('DROP TABLE historial_familiar');
        $this->addSql('DROP TABLE historial_propio');
        $this->addSql('DROP TABLE historia_medica');
        $this->addSql('DROP TABLE ingresado');
        $this->addSql('DROP TABLE permiso');
        $this->addSql('DROP TABLE permiso_rol');
        $this->addSql('DROP TABLE persona');
        $this->addSql('DROP TABLE plan_tratamiento');
        $this->addSql('DROP TABLE rol');
        $this->addSql('DROP TABLE sala');
        $this->addSql('DROP TABLE signo_vital');
        $this->addSql('DROP TABLE tipo_habitacion');
        $this->addSql('DROP TABLE user');
    }
}
