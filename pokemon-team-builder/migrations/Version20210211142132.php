<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210211142132 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE generation (id INT AUTO_INCREMENT NOT NULL, number INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon (id INT AUTO_INCREMENT NOT NULL, generation_id INT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(500) NOT NULL, sprite VARCHAR(500) NOT NULL, hp INT NOT NULL, attack INT NOT NULL, defense INT NOT NULL, special_attack INT NOT NULL, special_defense INT NOT NULL, speed INT NOT NULL, INDEX IDX_62DC90F3553A6EC4 (generation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_team (pokemon_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_F849D85C2FE71C3E (pokemon_id), INDEX IDX_F849D85C296CD8AE (team_id), PRIMARY KEY(pokemon_id, team_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_C4E0A61FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(500) NOT NULL, english_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_pokemon (type_id INT NOT NULL, pokemon_id INT NOT NULL, INDEX IDX_4AFDFF06C54C8C93 (type_id), INDEX IDX_4AFDFF062FE71C3E (pokemon_id), PRIMARY KEY(type_id, pokemon_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vulnerable_to (attacked_type INT NOT NULL, attacking_type INT NOT NULL, INDEX IDX_D94ACCE3EDE4E40 (attacked_type), INDEX IDX_D94ACCEFED13116 (attacking_type), PRIMARY KEY(attacked_type, attacking_type)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resistant_to (attacked_type INT NOT NULL, attacking_type INT NOT NULL, INDEX IDX_B52803FF3EDE4E40 (attacked_type), INDEX IDX_B52803FFFED13116 (attacking_type), PRIMARY KEY(attacked_type, attacking_type)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE neutral_to (attacked_type INT NOT NULL, attacking_type INT NOT NULL, INDEX IDX_A431E0A83EDE4E40 (attacked_type), INDEX IDX_A431E0A8FED13116 (attacking_type), PRIMARY KEY(attacked_type, attacking_type)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE immune_to (attacked_type INT NOT NULL, attacking_type INT NOT NULL, INDEX IDX_37ED40C73EDE4E40 (attacked_type), INDEX IDX_37ED40C7FED13116 (attacking_type), PRIMARY KEY(attacked_type, attacking_type)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3553A6EC4 FOREIGN KEY (generation_id) REFERENCES generation (id)');
        $this->addSql('ALTER TABLE pokemon_team ADD CONSTRAINT FK_F849D85C2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pokemon_team ADD CONSTRAINT FK_F849D85C296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE type_pokemon ADD CONSTRAINT FK_4AFDFF06C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE type_pokemon ADD CONSTRAINT FK_4AFDFF062FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vulnerable_to ADD CONSTRAINT FK_D94ACCE3EDE4E40 FOREIGN KEY (attacked_type) REFERENCES type (id)');
        $this->addSql('ALTER TABLE vulnerable_to ADD CONSTRAINT FK_D94ACCEFED13116 FOREIGN KEY (attacking_type) REFERENCES type (id)');
        $this->addSql('ALTER TABLE resistant_to ADD CONSTRAINT FK_B52803FF3EDE4E40 FOREIGN KEY (attacked_type) REFERENCES type (id)');
        $this->addSql('ALTER TABLE resistant_to ADD CONSTRAINT FK_B52803FFFED13116 FOREIGN KEY (attacking_type) REFERENCES type (id)');
        $this->addSql('ALTER TABLE neutral_to ADD CONSTRAINT FK_A431E0A83EDE4E40 FOREIGN KEY (attacked_type) REFERENCES type (id)');
        $this->addSql('ALTER TABLE neutral_to ADD CONSTRAINT FK_A431E0A8FED13116 FOREIGN KEY (attacking_type) REFERENCES type (id)');
        $this->addSql('ALTER TABLE immune_to ADD CONSTRAINT FK_37ED40C73EDE4E40 FOREIGN KEY (attacked_type) REFERENCES type (id)');
        $this->addSql('ALTER TABLE immune_to ADD CONSTRAINT FK_37ED40C7FED13116 FOREIGN KEY (attacking_type) REFERENCES type (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F3553A6EC4');
        $this->addSql('ALTER TABLE pokemon_team DROP FOREIGN KEY FK_F849D85C2FE71C3E');
        $this->addSql('ALTER TABLE type_pokemon DROP FOREIGN KEY FK_4AFDFF062FE71C3E');
        $this->addSql('ALTER TABLE pokemon_team DROP FOREIGN KEY FK_F849D85C296CD8AE');
        $this->addSql('ALTER TABLE type_pokemon DROP FOREIGN KEY FK_4AFDFF06C54C8C93');
        $this->addSql('ALTER TABLE vulnerable_to DROP FOREIGN KEY FK_D94ACCE3EDE4E40');
        $this->addSql('ALTER TABLE vulnerable_to DROP FOREIGN KEY FK_D94ACCEFED13116');
        $this->addSql('ALTER TABLE resistant_to DROP FOREIGN KEY FK_B52803FF3EDE4E40');
        $this->addSql('ALTER TABLE resistant_to DROP FOREIGN KEY FK_B52803FFFED13116');
        $this->addSql('ALTER TABLE neutral_to DROP FOREIGN KEY FK_A431E0A83EDE4E40');
        $this->addSql('ALTER TABLE neutral_to DROP FOREIGN KEY FK_A431E0A8FED13116');
        $this->addSql('ALTER TABLE immune_to DROP FOREIGN KEY FK_37ED40C73EDE4E40');
        $this->addSql('ALTER TABLE immune_to DROP FOREIGN KEY FK_37ED40C7FED13116');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61FA76ED395');
        $this->addSql('DROP TABLE generation');
        $this->addSql('DROP TABLE pokemon');
        $this->addSql('DROP TABLE pokemon_team');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE type_pokemon');
        $this->addSql('DROP TABLE vulnerable_to');
        $this->addSql('DROP TABLE resistant_to');
        $this->addSql('DROP TABLE neutral_to');
        $this->addSql('DROP TABLE immune_to');
        $this->addSql('DROP TABLE user');
    }
}
