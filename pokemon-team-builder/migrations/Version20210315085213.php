<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210315085213 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon ADD original_form_id INT DEFAULT NULL, ADD pokedex_id INT NOT NULL');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3D976358A FOREIGN KEY (original_form_id) REFERENCES pokemon (id)');
        $this->addSql('CREATE INDEX IDX_62DC90F3D976358A ON pokemon (original_form_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F3D976358A');
        $this->addSql('DROP INDEX IDX_62DC90F3D976358A ON pokemon');
        $this->addSql('ALTER TABLE pokemon DROP original_form_id, DROP pokedex_id');
    }
}
