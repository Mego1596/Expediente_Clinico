<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190601003542 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE examen_solicitado (id INT AUTO_INCREMENT NOT NULL, cita_id INT DEFAULT NULL, tipo_examen LONGTEXT NOT NULL, categoria LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, INDEX IDX_779218FB1E011DDF (cita_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE anexo (id INT AUTO_INCREMENT NOT NULL, examen_solicitado_id INT NOT NULL, nombre_archivo LONGTEXT NOT NULL, ruta LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, INDEX IDX_CD7EAF2C43CA3347 (examen_solicitado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE habitacion (id INT AUTO_INCREMENT NOT NULL, sala_id INT NOT NULL, tipo_habitacion_id INT DEFAULT NULL, numero_habitacion INT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, INDEX IDX_F45995BAC51CDF3F (sala_id), INDEX IDX_F45995BAB0BA7A53 (tipo_habitacion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE camilla (id INT AUTO_INCREMENT NOT NULL, habitacion_id INT DEFAULT NULL, numero_camilla INT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, INDEX IDX_712619ADB009290D (habitacion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expediente (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, genero_id INT NOT NULL, numero_expediente LONGTEXT NOT NULL, fecha_nacimiento DATETIME NOT NULL, direccion LONGTEXT NOT NULL, telefono LONGTEXT NOT NULL, apellido_casada LONGTEXT DEFAULT NULL, estado_civil LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, habilitado TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_D59CA413DB38439E (usuario_id), INDEX IDX_D59CA413BCE7B795 (genero_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cita (id INT AUTO_INCREMENT NOT NULL, usuario_id INT DEFAULT NULL, expediente_id INT NOT NULL, fecha_reservacion DATETIME NOT NULL, consulta_por LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, fecha_fin DATETIME DEFAULT NULL, INDEX IDX_3E379A62DB38439E (usuario_id), INDEX IDX_3E379A624BF37E4E (expediente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clinica (id INT AUTO_INCREMENT NOT NULL, nombre_clinica LONGTEXT NOT NULL, direccion LONGTEXT NOT NULL, telefono LONGTEXT NOT NULL, email LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE especialidad (id INT AUTO_INCREMENT NOT NULL, nombre_especialidad LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE examen_heces_macroscopico (id INT AUTO_INCREMENT NOT NULL, examen_solicitado_id INT NOT NULL, aspecto LONGTEXT NOT NULL, consistencia LONGTEXT NOT NULL, color LONGTEXT NOT NULL, olor LONGTEXT NOT NULL, presencia_de_sangre LONGTEXT NOT NULL, restos_alimenticios LONGTEXT NOT NULL, presencia_moco LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_B368264143CA3347 (examen_solicitado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE examen_heces_microscopico (id INT AUTO_INCREMENT NOT NULL, examen_solicitado_id INT NOT NULL, hematies LONGTEXT NOT NULL, leucocito LONGTEXT NOT NULL, flora_bacteriana LONGTEXT NOT NULL, levadura LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_9ABC70443CA3347 (examen_solicitado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE examen_heces_quimico (id INT AUTO_INCREMENT NOT NULL, examen_solicitado_id INT NOT NULL, ph DOUBLE PRECISION NOT NULL, azucares_reductores LONGTEXT NOT NULL, sangre_oculta LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_49A805AC43CA3347 (examen_solicitado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE examen_hematologico (id INT AUTO_INCREMENT NOT NULL, examen_solicitado_id INT NOT NULL, tipo_serie LONGTEXT NOT NULL, unidad LONGTEXT NOT NULL, valor_referencia LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, INDEX IDX_AE3CB97343CA3347 (examen_solicitado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE examen_orina_cristaluria (id INT AUTO_INCREMENT NOT NULL, examen_solicitado_id INT NOT NULL, uratos_amorfos LONGTEXT NOT NULL, acido_urico LONGTEXT NOT NULL, oxalatos_calcicos LONGTEXT NOT NULL, fosfatos_amorfos LONGTEXT NOT NULL, fosfatos_calcicos LONGTEXT NOT NULL, fosfatos_amonicos LONGTEXT NOT NULL, riesgo_litogenico LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME NOT NULL, UNIQUE INDEX UNIQ_7AC4955A43CA3347 (examen_solicitado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE examen_orina_macroscopico (id INT AUTO_INCREMENT NOT NULL, examen_solicitado_id INT NOT NULL, color LONGTEXT NOT NULL, aspecto LONGTEXT NOT NULL, sedimento LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_C81E2D0043CA3347 (examen_solicitado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE examen_orina_microscopico (id INT AUTO_INCREMENT NOT NULL, examen_solicitado_id INT NOT NULL, uretral LONGTEXT NOT NULL, urotelio LONGTEXT NOT NULL, renal LONGTEXT NOT NULL, leucocitos LONGTEXT NOT NULL, piocitos LONGTEXT NOT NULL, eritrocitos LONGTEXT NOT NULL, bacteria LONGTEXT NOT NULL, parasitos LONGTEXT NOT NULL, funguria LONGTEXT NOT NULL, filamento_de_mucina LONGTEXT NOT NULL, proteina_uromocoide LONGTEXT NOT NULL, cilindros LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_72DDCC4543CA3347 (examen_solicitado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE examen_orina_quimico (id INT AUTO_INCREMENT NOT NULL, examen_solicitado_id INT NOT NULL, densidad DOUBLE PRECISION NOT NULL, ph DOUBLE PRECISION NOT NULL, glucosa LONGTEXT NOT NULL, proteinas LONGTEXT NOT NULL, hemoglobina LONGTEXT NOT NULL, cuerpo_cetonico LONGTEXT NOT NULL, pigmento_biliar LONGTEXT NOT NULL, urobilinogeno LONGTEXT NOT NULL, nitritos LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_D422CE0343CA3347 (examen_solicitado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE examen_quimica_sanguinea (id INT AUTO_INCREMENT NOT NULL, examen_solicitado_id INT NOT NULL, parametro LONGTEXT NOT NULL, resultado INT NOT NULL, comentario LONGTEXT NOT NULL, unidades LONGTEXT NOT NULL, rango LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, INDEX IDX_D8F766BE43CA3347 (examen_solicitado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE familiar (id INT AUTO_INCREMENT NOT NULL, nombres LONGTEXT NOT NULL, apellidos LONGTEXT NOT NULL, fecha_nacimiento DATETIME NOT NULL, telefono LONGTEXT NOT NULL, descripcion LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genero (id INT AUTO_INCREMENT NOT NULL, descripcion LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE historia_medica (id INT AUTO_INCREMENT NOT NULL, cita_id INT NOT NULL, diagnostico_id INT NOT NULL, consulta_por LONGTEXT NOT NULL, signos LONGTEXT NOT NULL, sintomas LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, codigo_especifico VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_328E741C1E011DDF (cita_id), UNIQUE INDEX UNIQ_328E741C7A94BA1A (diagnostico_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE historial_familiar (id INT AUTO_INCREMENT NOT NULL, familiar_id INT NOT NULL, descripcion LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, INDEX IDX_523A50F910C20D71 (familiar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE historial_propio (id INT AUTO_INCREMENT NOT NULL, expediente_id INT NOT NULL, descripcion LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, INDEX IDX_60EB986D4BF37E4E (expediente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingresado (id INT AUTO_INCREMENT NOT NULL, camilla_id INT NOT NULL, expediente_id INT NOT NULL, usuario_id INT NOT NULL, fecha_ingreso DATETIME NOT NULL, fecha_salida DATETIME DEFAULT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, INDEX IDX_6682CB4BFEEC2797 (camilla_id), INDEX IDX_6682CB4B4BF37E4E (expediente_id), INDEX IDX_6682CB4BDB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plan_tratamiento (id INT AUTO_INCREMENT NOT NULL, historia_medica_id INT NOT NULL, dosis LONGTEXT NOT NULL, medicamento LONGTEXT NOT NULL, frecuencia LONGTEXT NOT NULL, tipo_medicamento LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, INDEX IDX_951D7817322E8DC3 (historia_medica_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rol (id INT AUTO_INCREMENT NOT NULL, nombre_rol VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sala (id INT AUTO_INCREMENT NOT NULL, clinica_id INT NOT NULL, nombre_sala LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, INDEX IDX_E226041C9CD3F6D6 (clinica_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE signo_vital (id INT AUTO_INCREMENT NOT NULL, cita_id INT NOT NULL, peso DOUBLE PRECISION NOT NULL, temperatura DOUBLE PRECISION NOT NULL, estatura DOUBLE PRECISION NOT NULL, presion_arterial DOUBLE PRECISION NOT NULL, ritmo_cardiaco DOUBLE PRECISION NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_75668911E011DDF (cita_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tipo_habitacion (id INT AUTO_INCREMENT NOT NULL, tipo_habitacion LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, rol_id INT NOT NULL, clinica_id INT DEFAULT NULL, usuario_especialidades_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, nombres VARCHAR(255) NOT NULL, apellidos VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, emergencia TINYINT(1) DEFAULT NULL, planta TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D6494BAB96C (rol_id), INDEX IDX_8D93D6499CD3F6D6 (clinica_id), INDEX IDX_8D93D649AF3A97F (usuario_especialidades_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE diagnostico (id INT AUTO_INCREMENT NOT NULL, descripcion LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, codigo_categoria VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE familiares_expediente (id INT AUTO_INCREMENT NOT NULL, familiar_id INT NOT NULL, expediente_id INT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, responsable TINYINT(1) NOT NULL, INDEX IDX_B24733AA10C20D71 (familiar_id), INDEX IDX_B24733AA4BF37E4E (expediente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permiso (id INT AUTO_INCREMENT NOT NULL, permiso VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL, nombre_tabla VARCHAR(255) NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permiso_rol (permiso_id INT NOT NULL, rol_id INT NOT NULL, INDEX IDX_DD501D066CEFAD37 (permiso_id), INDEX IDX_DD501D064BAB96C (rol_id), PRIMARY KEY(permiso_id, rol_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE examen_solicitado ADD CONSTRAINT FK_779218FB1E011DDF FOREIGN KEY (cita_id) REFERENCES cita (id)');
        $this->addSql('ALTER TABLE anexo ADD CONSTRAINT FK_CD7EAF2C43CA3347 FOREIGN KEY (examen_solicitado_id) REFERENCES examen_solicitado (id)');
        $this->addSql('ALTER TABLE habitacion ADD CONSTRAINT FK_F45995BAC51CDF3F FOREIGN KEY (sala_id) REFERENCES sala (id)');
        $this->addSql('ALTER TABLE habitacion ADD CONSTRAINT FK_F45995BAB0BA7A53 FOREIGN KEY (tipo_habitacion_id) REFERENCES tipo_habitacion (id)');
        $this->addSql('ALTER TABLE camilla ADD CONSTRAINT FK_712619ADB009290D FOREIGN KEY (habitacion_id) REFERENCES habitacion (id)');
        $this->addSql('ALTER TABLE expediente ADD CONSTRAINT FK_D59CA413DB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE expediente ADD CONSTRAINT FK_D59CA413BCE7B795 FOREIGN KEY (genero_id) REFERENCES genero (id)');
        $this->addSql('ALTER TABLE cita ADD CONSTRAINT FK_3E379A62DB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cita ADD CONSTRAINT FK_3E379A624BF37E4E FOREIGN KEY (expediente_id) REFERENCES expediente (id)');
        $this->addSql('ALTER TABLE examen_heces_macroscopico ADD CONSTRAINT FK_B368264143CA3347 FOREIGN KEY (examen_solicitado_id) REFERENCES examen_solicitado (id)');
        $this->addSql('ALTER TABLE examen_heces_microscopico ADD CONSTRAINT FK_9ABC70443CA3347 FOREIGN KEY (examen_solicitado_id) REFERENCES examen_solicitado (id)');
        $this->addSql('ALTER TABLE examen_heces_quimico ADD CONSTRAINT FK_49A805AC43CA3347 FOREIGN KEY (examen_solicitado_id) REFERENCES examen_solicitado (id)');
        $this->addSql('ALTER TABLE examen_hematologico ADD CONSTRAINT FK_AE3CB97343CA3347 FOREIGN KEY (examen_solicitado_id) REFERENCES examen_solicitado (id)');
        $this->addSql('ALTER TABLE examen_orina_cristaluria ADD CONSTRAINT FK_7AC4955A43CA3347 FOREIGN KEY (examen_solicitado_id) REFERENCES examen_solicitado (id)');
        $this->addSql('ALTER TABLE examen_orina_macroscopico ADD CONSTRAINT FK_C81E2D0043CA3347 FOREIGN KEY (examen_solicitado_id) REFERENCES examen_solicitado (id)');
        $this->addSql('ALTER TABLE examen_orina_microscopico ADD CONSTRAINT FK_72DDCC4543CA3347 FOREIGN KEY (examen_solicitado_id) REFERENCES examen_solicitado (id)');
        $this->addSql('ALTER TABLE examen_orina_quimico ADD CONSTRAINT FK_D422CE0343CA3347 FOREIGN KEY (examen_solicitado_id) REFERENCES examen_solicitado (id)');
        $this->addSql('ALTER TABLE examen_quimica_sanguinea ADD CONSTRAINT FK_D8F766BE43CA3347 FOREIGN KEY (examen_solicitado_id) REFERENCES examen_solicitado (id)');
        $this->addSql('ALTER TABLE historia_medica ADD CONSTRAINT FK_328E741C1E011DDF FOREIGN KEY (cita_id) REFERENCES cita (id)');
        $this->addSql('ALTER TABLE historia_medica ADD CONSTRAINT FK_328E741C7A94BA1A FOREIGN KEY (diagnostico_id) REFERENCES diagnostico (id)');
        $this->addSql('ALTER TABLE historial_familiar ADD CONSTRAINT FK_523A50F910C20D71 FOREIGN KEY (familiar_id) REFERENCES familiar (id)');
        $this->addSql('ALTER TABLE historial_propio ADD CONSTRAINT FK_60EB986D4BF37E4E FOREIGN KEY (expediente_id) REFERENCES expediente (id)');
        $this->addSql('ALTER TABLE ingresado ADD CONSTRAINT FK_6682CB4BFEEC2797 FOREIGN KEY (camilla_id) REFERENCES camilla (id)');
        $this->addSql('ALTER TABLE ingresado ADD CONSTRAINT FK_6682CB4B4BF37E4E FOREIGN KEY (expediente_id) REFERENCES expediente (id)');
        $this->addSql('ALTER TABLE ingresado ADD CONSTRAINT FK_6682CB4BDB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE plan_tratamiento ADD CONSTRAINT FK_951D7817322E8DC3 FOREIGN KEY (historia_medica_id) REFERENCES historia_medica (id)');
        $this->addSql('ALTER TABLE sala ADD CONSTRAINT FK_E226041C9CD3F6D6 FOREIGN KEY (clinica_id) REFERENCES clinica (id)');
        $this->addSql('ALTER TABLE signo_vital ADD CONSTRAINT FK_75668911E011DDF FOREIGN KEY (cita_id) REFERENCES cita (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494BAB96C FOREIGN KEY (rol_id) REFERENCES rol (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499CD3F6D6 FOREIGN KEY (clinica_id) REFERENCES clinica (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649AF3A97F FOREIGN KEY (usuario_especialidades_id) REFERENCES especialidad (id)');
        $this->addSql('ALTER TABLE familiares_expediente ADD CONSTRAINT FK_B24733AA10C20D71 FOREIGN KEY (familiar_id) REFERENCES familiar (id)');
        $this->addSql('ALTER TABLE familiares_expediente ADD CONSTRAINT FK_B24733AA4BF37E4E FOREIGN KEY (expediente_id) REFERENCES expediente (id)');
        $this->addSql('ALTER TABLE permiso_rol ADD CONSTRAINT FK_DD501D066CEFAD37 FOREIGN KEY (permiso_id) REFERENCES permiso (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE permiso_rol ADD CONSTRAINT FK_DD501D064BAB96C FOREIGN KEY (rol_id) REFERENCES rol (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE anexo DROP FOREIGN KEY FK_CD7EAF2C43CA3347');
        $this->addSql('ALTER TABLE examen_heces_macroscopico DROP FOREIGN KEY FK_B368264143CA3347');
        $this->addSql('ALTER TABLE examen_heces_microscopico DROP FOREIGN KEY FK_9ABC70443CA3347');
        $this->addSql('ALTER TABLE examen_heces_quimico DROP FOREIGN KEY FK_49A805AC43CA3347');
        $this->addSql('ALTER TABLE examen_hematologico DROP FOREIGN KEY FK_AE3CB97343CA3347');
        $this->addSql('ALTER TABLE examen_orina_cristaluria DROP FOREIGN KEY FK_7AC4955A43CA3347');
        $this->addSql('ALTER TABLE examen_orina_macroscopico DROP FOREIGN KEY FK_C81E2D0043CA3347');
        $this->addSql('ALTER TABLE examen_orina_microscopico DROP FOREIGN KEY FK_72DDCC4543CA3347');
        $this->addSql('ALTER TABLE examen_orina_quimico DROP FOREIGN KEY FK_D422CE0343CA3347');
        $this->addSql('ALTER TABLE examen_quimica_sanguinea DROP FOREIGN KEY FK_D8F766BE43CA3347');
        $this->addSql('ALTER TABLE camilla DROP FOREIGN KEY FK_712619ADB009290D');
        $this->addSql('ALTER TABLE ingresado DROP FOREIGN KEY FK_6682CB4BFEEC2797');
        $this->addSql('ALTER TABLE cita DROP FOREIGN KEY FK_3E379A624BF37E4E');
        $this->addSql('ALTER TABLE historial_propio DROP FOREIGN KEY FK_60EB986D4BF37E4E');
        $this->addSql('ALTER TABLE ingresado DROP FOREIGN KEY FK_6682CB4B4BF37E4E');
        $this->addSql('ALTER TABLE familiares_expediente DROP FOREIGN KEY FK_B24733AA4BF37E4E');
        $this->addSql('ALTER TABLE examen_solicitado DROP FOREIGN KEY FK_779218FB1E011DDF');
        $this->addSql('ALTER TABLE historia_medica DROP FOREIGN KEY FK_328E741C1E011DDF');
        $this->addSql('ALTER TABLE signo_vital DROP FOREIGN KEY FK_75668911E011DDF');
        $this->addSql('ALTER TABLE sala DROP FOREIGN KEY FK_E226041C9CD3F6D6');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499CD3F6D6');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649AF3A97F');
        $this->addSql('ALTER TABLE historial_familiar DROP FOREIGN KEY FK_523A50F910C20D71');
        $this->addSql('ALTER TABLE familiares_expediente DROP FOREIGN KEY FK_B24733AA10C20D71');
        $this->addSql('ALTER TABLE expediente DROP FOREIGN KEY FK_D59CA413BCE7B795');
        $this->addSql('ALTER TABLE plan_tratamiento DROP FOREIGN KEY FK_951D7817322E8DC3');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494BAB96C');
        $this->addSql('ALTER TABLE permiso_rol DROP FOREIGN KEY FK_DD501D064BAB96C');
        $this->addSql('ALTER TABLE habitacion DROP FOREIGN KEY FK_F45995BAC51CDF3F');
        $this->addSql('ALTER TABLE habitacion DROP FOREIGN KEY FK_F45995BAB0BA7A53');
        $this->addSql('ALTER TABLE expediente DROP FOREIGN KEY FK_D59CA413DB38439E');
        $this->addSql('ALTER TABLE cita DROP FOREIGN KEY FK_3E379A62DB38439E');
        $this->addSql('ALTER TABLE ingresado DROP FOREIGN KEY FK_6682CB4BDB38439E');
        $this->addSql('ALTER TABLE historia_medica DROP FOREIGN KEY FK_328E741C7A94BA1A');
        $this->addSql('ALTER TABLE permiso_rol DROP FOREIGN KEY FK_DD501D066CEFAD37');
        $this->addSql('DROP TABLE examen_solicitado');
        $this->addSql('DROP TABLE anexo');
        $this->addSql('DROP TABLE habitacion');
        $this->addSql('DROP TABLE camilla');
        $this->addSql('DROP TABLE expediente');
        $this->addSql('DROP TABLE cita');
        $this->addSql('DROP TABLE clinica');
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
        $this->addSql('DROP TABLE familiar');
        $this->addSql('DROP TABLE genero');
        $this->addSql('DROP TABLE historia_medica');
        $this->addSql('DROP TABLE historial_familiar');
        $this->addSql('DROP TABLE historial_propio');
        $this->addSql('DROP TABLE ingresado');
        $this->addSql('DROP TABLE plan_tratamiento');
        $this->addSql('DROP TABLE rol');
        $this->addSql('DROP TABLE sala');
        $this->addSql('DROP TABLE signo_vital');
        $this->addSql('DROP TABLE tipo_habitacion');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE diagnostico');
        $this->addSql('DROP TABLE familiares_expediente');
        $this->addSql('DROP TABLE permiso');
        $this->addSql('DROP TABLE permiso_rol');
    }
}
