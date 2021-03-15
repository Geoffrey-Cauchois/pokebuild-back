<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210315085722 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon ADD evolved_from_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3F96E4EAD FOREIGN KEY (evolved_from_id) REFERENCES pokemon (id)');
        $this->addSql('CREATE INDEX IDX_62DC90F3F96E4EAD ON pokemon (evolved_from_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F3F96E4EAD');
        $this->addSql('DROP INDEX IDX_62DC90F3F96E4EAD ON pokemon');
        $this->addSql('ALTER TABLE pokemon DROP evolved_from_id');
    }
}
