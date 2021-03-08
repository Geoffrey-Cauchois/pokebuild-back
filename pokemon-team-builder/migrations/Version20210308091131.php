<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210308091131 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE team_appartenance (id INT AUTO_INCREMENT NOT NULL, team_id INT NOT NULL, pokemon_id INT NOT NULL, INDEX IDX_B96547A9296CD8AE (team_id), INDEX IDX_B96547A92FE71C3E (pokemon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE team_appartenance ADD CONSTRAINT FK_B96547A9296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE team_appartenance ADD CONSTRAINT FK_B96547A92FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('DROP TABLE pokemon_team');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pokemon_team (pokemon_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_F849D85C2FE71C3E (pokemon_id), INDEX IDX_F849D85C296CD8AE (team_id), PRIMARY KEY(pokemon_id, team_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE pokemon_team ADD CONSTRAINT FK_F849D85C296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pokemon_team ADD CONSTRAINT FK_F849D85C2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE team_appartenance');
    }
}
