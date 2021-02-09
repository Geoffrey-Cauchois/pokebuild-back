<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210209140937 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE neutral_to (neutral_type INT NOT NULL, neutral_attack_type INT NOT NULL, INDEX IDX_5BD192217C5D144B (neutral_type), INDEX IDX_5BD1922165B844C4 (neutral_attack_type), PRIMARY KEY(neutral_type, neutral_attack_type)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE neutral_to ADD CONSTRAINT FK_5BD192217C5D144D FOREIGN KEY (neutral_type) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE neutral_to ADD CONSTRAINT FK_5BD1922165B844C6 FOREIGN KEY (neutral_attack_type) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('CREATE TABLE vulnerable_to (vulnerable_type INT NOT NULL, effective_attack_type INT NOT NULL, INDEX IDX_5BD192217C5D144B (vulnerable_type), INDEX IDX_5BD1922165B844C4 (effective_attack_type), PRIMARY KEY(vulnerable_type, effective_attack_type)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vulnerable_to ADD CONSTRAINT FK_5BD192217C5D144B FOREIGN KEY (vulnerable_type) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vulnerable_to ADD CONSTRAINT FK_5BD1922165B844C4 FOREIGN KEY (effective_attack_type) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('CREATE TABLE resistant_to (resistant_type INT NOT NULL, weak_attack_type INT NOT NULL, INDEX IDX_5BD192217C5D144B (resistant_type), INDEX IDX_5BD1922165B844C4 (weak_attack_type), PRIMARY KEY(resistant_type, weak_attack_type)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE resistant_to ADD CONSTRAINT FK_5BD192217C5D144C FOREIGN KEY (resistant_type) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE resistant_to ADD CONSTRAINT FK_5BD1922165B844C5 FOREIGN KEY (weak_attack_type) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('CREATE TABLE immune_to (immune_type INT NOT NULL, innefective_attack_type INT NOT NULL, INDEX IDX_5BD192217C5D144B (immune_type), INDEX IDX_5BD1922165B844C4 (innefective_attack_type), PRIMARY KEY(immune_type, innefective_attack_type)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE immune_to ADD CONSTRAINT FK_5BD192217C5D144E FOREIGN KEY (immune_type) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE immune_to ADD CONSTRAINT FK_5BD1922165B844C7 FOREIGN KEY (innefective_attack_type) REFERENCES type (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE neutral_to');
        $this->addSql('DROP TABLE vulnerable_to');
        $this->addSql('DROP TABLE resistant_to');
        $this->addSql('DROP TABLE immune_to');
    }
}
