<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210309094722 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resistance_modifying_abilities_pokemon DROP FOREIGN KEY FK_6C8B5B3B164A899D');
        $this->addSql('ALTER TABLE resistance_modifying_abilities_type DROP FOREIGN KEY FK_B5389826164A899D');
        $this->addSql('CREATE TABLE resistance_modifying_ability (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, multiplier DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resistance_modifying_ability_type (resistance_modifying_ability_id INT NOT NULL, type_id INT NOT NULL, INDEX IDX_B31DB04482C85110 (resistance_modifying_ability_id), INDEX IDX_B31DB044C54C8C93 (type_id), PRIMARY KEY(resistance_modifying_ability_id, type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resistance_modifying_ability_pokemon (resistance_modifying_ability_id INT NOT NULL, pokemon_id INT NOT NULL, INDEX IDX_31813E9E82C85110 (resistance_modifying_ability_id), INDEX IDX_31813E9E2FE71C3E (pokemon_id), PRIMARY KEY(resistance_modifying_ability_id, pokemon_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE resistance_modifying_ability_type ADD CONSTRAINT FK_B31DB04482C85110 FOREIGN KEY (resistance_modifying_ability_id) REFERENCES resistance_modifying_ability (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE resistance_modifying_ability_type ADD CONSTRAINT FK_B31DB044C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE resistance_modifying_ability_pokemon ADD CONSTRAINT FK_31813E9E82C85110 FOREIGN KEY (resistance_modifying_ability_id) REFERENCES resistance_modifying_ability (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE resistance_modifying_ability_pokemon ADD CONSTRAINT FK_31813E9E2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE resistance_modifying_abilities');
        $this->addSql('DROP TABLE resistance_modifying_abilities_pokemon');
        $this->addSql('DROP TABLE resistance_modifying_abilities_type');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resistance_modifying_ability_type DROP FOREIGN KEY FK_B31DB04482C85110');
        $this->addSql('ALTER TABLE resistance_modifying_ability_pokemon DROP FOREIGN KEY FK_31813E9E82C85110');
        $this->addSql('CREATE TABLE resistance_modifying_abilities (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, multiplier DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE resistance_modifying_abilities_pokemon (resistance_modifying_abilities_id INT NOT NULL, pokemon_id INT NOT NULL, INDEX IDX_6C8B5B3B164A899D (resistance_modifying_abilities_id), INDEX IDX_6C8B5B3B2FE71C3E (pokemon_id), PRIMARY KEY(resistance_modifying_abilities_id, pokemon_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE resistance_modifying_abilities_type (resistance_modifying_abilities_id INT NOT NULL, type_id INT NOT NULL, INDEX IDX_B5389826164A899D (resistance_modifying_abilities_id), INDEX IDX_B5389826C54C8C93 (type_id), PRIMARY KEY(resistance_modifying_abilities_id, type_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE resistance_modifying_abilities_pokemon ADD CONSTRAINT FK_6C8B5B3B164A899D FOREIGN KEY (resistance_modifying_abilities_id) REFERENCES resistance_modifying_abilities (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE resistance_modifying_abilities_pokemon ADD CONSTRAINT FK_6C8B5B3B2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE resistance_modifying_abilities_type ADD CONSTRAINT FK_B5389826164A899D FOREIGN KEY (resistance_modifying_abilities_id) REFERENCES resistance_modifying_abilities (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE resistance_modifying_abilities_type ADD CONSTRAINT FK_B5389826C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE resistance_modifying_ability');
        $this->addSql('DROP TABLE resistance_modifying_ability_type');
        $this->addSql('DROP TABLE resistance_modifying_ability_pokemon');
    }
}
