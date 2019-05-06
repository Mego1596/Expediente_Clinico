<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190505201642 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE clinica (id INT AUTO_INCREMENT NOT NULL, nombre_clinica LONGTEXT NOT NULL, direccion LONGTEXT NOT NULL, telefono LONGTEXT NOT NULL, email LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, rol_id INT NOT NULL, clinica_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, nombres VARCHAR(255) NOT NULL, apellidos VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D6494BAB96C (rol_id), INDEX IDX_8D93D6499CD3F6D6 (clinica_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_especialidad (user_id INT NOT NULL, especialidad_id INT NOT NULL, INDEX IDX_2C7C18FBA76ED395 (user_id), INDEX IDX_2C7C18FB16A490EC (especialidad_id), PRIMARY KEY(user_id, especialidad_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE especialidad (id INT AUTO_INCREMENT NOT NULL, nombre_especialidad LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genero (id INT AUTO_INCREMENT NOT NULL, descripcion LONGTEXT NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permiso (id INT AUTO_INCREMENT NOT NULL, permiso VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL, nombre_tabla VARCHAR(255) NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permiso_rol (permiso_id INT NOT NULL, rol_id INT NOT NULL, INDEX IDX_DD501D066CEFAD37 (permiso_id), INDEX IDX_DD501D064BAB96C (rol_id), PRIMARY KEY(permiso_id, rol_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rol (id INT AUTO_INCREMENT NOT NULL, nombre_rol VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL, creado_en DATETIME DEFAULT NULL, actualizado_en DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494BAB96C FOREIGN KEY (rol_id) REFERENCES rol (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499CD3F6D6 FOREIGN KEY (clinica_id) REFERENCES clinica (id)');
        $this->addSql('ALTER TABLE user_especialidad ADD CONSTRAINT FK_2C7C18FBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_especialidad ADD CONSTRAINT FK_2C7C18FB16A490EC FOREIGN KEY (especialidad_id) REFERENCES especialidad (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE permiso_rol ADD CONSTRAINT FK_DD501D066CEFAD37 FOREIGN KEY (permiso_id) REFERENCES permiso (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE permiso_rol ADD CONSTRAINT FK_DD501D064BAB96C FOREIGN KEY (rol_id) REFERENCES rol (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499CD3F6D6');
        $this->addSql('ALTER TABLE user_especialidad DROP FOREIGN KEY FK_2C7C18FBA76ED395');
        $this->addSql('ALTER TABLE user_especialidad DROP FOREIGN KEY FK_2C7C18FB16A490EC');
        $this->addSql('ALTER TABLE permiso_rol DROP FOREIGN KEY FK_DD501D066CEFAD37');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494BAB96C');
        $this->addSql('ALTER TABLE permiso_rol DROP FOREIGN KEY FK_DD501D064BAB96C');
        $this->addSql('DROP TABLE clinica');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_especialidad');
        $this->addSql('DROP TABLE especialidad');
        $this->addSql('DROP TABLE genero');
        $this->addSql('DROP TABLE permiso');
        $this->addSql('DROP TABLE permiso_rol');
        $this->addSql('DROP TABLE rol');
    }
}
