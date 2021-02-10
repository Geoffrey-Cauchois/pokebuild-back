<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210210104059 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
       
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE immune_to DROP FOREIGN KEY FK_37ED40C77C5D144B');
        $this->addSql('ALTER TABLE immune_to DROP FOREIGN KEY FK_37ED40C765B844C4');
        $this->addSql('DROP INDEX IDX_37ED40C77C5D144B ON immune_to');
        $this->addSql('DROP INDEX IDX_37ED40C765B844C4 ON immune_to');
        $this->addSql('ALTER TABLE immune_to DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE immune_to ADD attacked_type INT NOT NULL, ADD attacking_type INT NOT NULL, DROP type_source, DROP type_target');
        $this->addSql('ALTER TABLE immune_to ADD CONSTRAINT FK_37ED40C73EDE4E40 FOREIGN KEY (attacked_type) REFERENCES type (id)');
        $this->addSql('ALTER TABLE immune_to ADD CONSTRAINT FK_37ED40C7FED13116 FOREIGN KEY (attacking_type) REFERENCES type (id)');
        $this->addSql('CREATE INDEX IDX_37ED40C73EDE4E40 ON immune_to (attacked_type)');
        $this->addSql('CREATE INDEX IDX_37ED40C7FED13116 ON immune_to (attacking_type)');
        $this->addSql('ALTER TABLE immune_to ADD PRIMARY KEY (attacked_type, attacking_type)');
        $this->addSql('ALTER TABLE neutral_to DROP FOREIGN KEY FK_A431E0A87C5D144B');
        $this->addSql('ALTER TABLE neutral_to DROP FOREIGN KEY FK_A431E0A865B844C4');
        $this->addSql('DROP INDEX IDX_A431E0A87C5D144B ON neutral_to');
        $this->addSql('DROP INDEX IDX_A431E0A865B844C4 ON neutral_to');
        $this->addSql('ALTER TABLE neutral_to DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE neutral_to ADD attacked_type INT NOT NULL, ADD attacking_type INT NOT NULL, DROP type_source, DROP type_target');
        $this->addSql('ALTER TABLE neutral_to ADD CONSTRAINT FK_A431E0A83EDE4E40 FOREIGN KEY (attacked_type) REFERENCES type (id)');
        $this->addSql('ALTER TABLE neutral_to ADD CONSTRAINT FK_A431E0A8FED13116 FOREIGN KEY (attacking_type) REFERENCES type (id)');
        $this->addSql('CREATE INDEX IDX_A431E0A83EDE4E40 ON neutral_to (attacked_type)');
        $this->addSql('CREATE INDEX IDX_A431E0A8FED13116 ON neutral_to (attacking_type)');
        $this->addSql('ALTER TABLE neutral_to ADD PRIMARY KEY (attacked_type, attacking_type)');
        $this->addSql('ALTER TABLE resistant_to DROP FOREIGN KEY FK_B52803FF7C5D144B');
        $this->addSql('ALTER TABLE resistant_to DROP FOREIGN KEY FK_B52803FF65B844C4');
        $this->addSql('DROP INDEX IDX_B52803FF7C5D144B ON resistant_to');
        $this->addSql('DROP INDEX IDX_B52803FF65B844C4 ON resistant_to');
        $this->addSql('ALTER TABLE resistant_to DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE resistant_to ADD attacked_type INT NOT NULL, ADD attacking_type INT NOT NULL, DROP type_source, DROP type_target');
        $this->addSql('ALTER TABLE resistant_to ADD CONSTRAINT FK_B52803FF3EDE4E40 FOREIGN KEY (attacked_type) REFERENCES type (id)');
        $this->addSql('ALTER TABLE resistant_to ADD CONSTRAINT FK_B52803FFFED13116 FOREIGN KEY (attacking_type) REFERENCES type (id)');
        $this->addSql('CREATE INDEX IDX_B52803FF3EDE4E40 ON resistant_to (attacked_type)');
        $this->addSql('CREATE INDEX IDX_B52803FFFED13116 ON resistant_to (attacking_type)');
        $this->addSql('ALTER TABLE resistant_to ADD PRIMARY KEY (attacked_type, attacking_type)');
        $this->addSql('ALTER TABLE vulnerable_to DROP FOREIGN KEY FK_D94ACCE7C5D144B');
        $this->addSql('ALTER TABLE vulnerable_to DROP FOREIGN KEY FK_D94ACCE65B844C4');
        $this->addSql('DROP INDEX IDX_D94ACCE7C5D144B ON vulnerable_to');
        $this->addSql('DROP INDEX IDX_D94ACCE65B844C4 ON vulnerable_to');
        $this->addSql('ALTER TABLE vulnerable_to DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE vulnerable_to ADD attacked_type INT NOT NULL, ADD attacking_type INT NOT NULL, DROP type_source, DROP type_target');
        $this->addSql('ALTER TABLE vulnerable_to ADD CONSTRAINT FK_D94ACCE3EDE4E40 FOREIGN KEY (attacked_type) REFERENCES type (id)');
        $this->addSql('ALTER TABLE vulnerable_to ADD CONSTRAINT FK_D94ACCEFED13116 FOREIGN KEY (attacking_type) REFERENCES type (id)');
        $this->addSql('CREATE INDEX IDX_D94ACCE3EDE4E40 ON vulnerable_to (attacked_type)');
        $this->addSql('CREATE INDEX IDX_D94ACCEFED13116 ON vulnerable_to (attacking_type)');
        $this->addSql('ALTER TABLE vulnerable_to ADD PRIMARY KEY (attacked_type, attacking_type)');
    }
}
