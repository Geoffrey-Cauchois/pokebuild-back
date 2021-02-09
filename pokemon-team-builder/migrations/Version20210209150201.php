<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210209150201 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vulnerable_to DROP FOREIGN KEY FK_5BD1922165B844C4');
        $this->addSql('ALTER TABLE vulnerable_to DROP FOREIGN KEY FK_5BD192217C5D144B');
        $this->addSql('DROP INDEX IDX_5BD192217C5D144B ON vulnerable_to');
        $this->addSql('DROP INDEX IDX_5BD1922165B844C4 ON vulnerable_to');
        $this->addSql('ALTER TABLE vulnerable_to DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE vulnerable_to ADD type_source INT NOT NULL, ADD type_target INT NOT NULL, DROP vulnerable_type, DROP effective_attack_type');
        $this->addSql('ALTER TABLE vulnerable_to ADD CONSTRAINT FK_D94ACCE7C5D144B FOREIGN KEY (type_source) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vulnerable_to ADD CONSTRAINT FK_D94ACCE65B844C4 FOREIGN KEY (type_target) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_D94ACCE7C5D144B ON vulnerable_to (type_source)');
        $this->addSql('CREATE INDEX IDX_D94ACCE65B844C4 ON vulnerable_to (type_target)');
        $this->addSql('ALTER TABLE vulnerable_to ADD PRIMARY KEY (type_source, type_target)');
        $this->addSql('ALTER TABLE resistant_to DROP FOREIGN KEY FK_5BD1922165B844C5');
        $this->addSql('ALTER TABLE resistant_to DROP FOREIGN KEY FK_5BD192217C5D144C');
        $this->addSql('DROP INDEX IDX_5BD192217C5D144B ON resistant_to');
        $this->addSql('DROP INDEX IDX_5BD1922165B844C4 ON resistant_to');
        $this->addSql('ALTER TABLE resistant_to DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE resistant_to ADD type_source INT NOT NULL, ADD type_target INT NOT NULL, DROP resistant_type, DROP weak_attack_type');
        $this->addSql('ALTER TABLE resistant_to ADD CONSTRAINT FK_B52803FF7C5D144B FOREIGN KEY (type_source) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE resistant_to ADD CONSTRAINT FK_B52803FF65B844C4 FOREIGN KEY (type_target) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_B52803FF7C5D144B ON resistant_to (type_source)');
        $this->addSql('CREATE INDEX IDX_B52803FF65B844C4 ON resistant_to (type_target)');
        $this->addSql('ALTER TABLE resistant_to ADD PRIMARY KEY (type_source, type_target)');
        $this->addSql('ALTER TABLE neutral_to DROP FOREIGN KEY FK_5BD1922165B844C6');
        $this->addSql('ALTER TABLE neutral_to DROP FOREIGN KEY FK_5BD192217C5D144D');
        $this->addSql('DROP INDEX IDX_5BD192217C5D144B ON neutral_to');
        $this->addSql('DROP INDEX IDX_5BD1922165B844C4 ON neutral_to');
        $this->addSql('ALTER TABLE neutral_to DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE neutral_to ADD type_source INT NOT NULL, ADD type_target INT NOT NULL, DROP neutral_type, DROP neutral_attack_type');
        $this->addSql('ALTER TABLE neutral_to ADD CONSTRAINT FK_A431E0A87C5D144B FOREIGN KEY (type_source) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE neutral_to ADD CONSTRAINT FK_A431E0A865B844C4 FOREIGN KEY (type_target) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_A431E0A87C5D144B ON neutral_to (type_source)');
        $this->addSql('CREATE INDEX IDX_A431E0A865B844C4 ON neutral_to (type_target)');
        $this->addSql('ALTER TABLE neutral_to ADD PRIMARY KEY (type_source, type_target)');
        $this->addSql('ALTER TABLE immune_to DROP FOREIGN KEY FK_5BD1922165B844C7');
        $this->addSql('ALTER TABLE immune_to DROP FOREIGN KEY FK_5BD192217C5D144E');
        $this->addSql('DROP INDEX IDX_5BD192217C5D144B ON immune_to');
        $this->addSql('DROP INDEX IDX_5BD1922165B844C4 ON immune_to');
        $this->addSql('ALTER TABLE immune_to DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE immune_to ADD type_source INT NOT NULL, ADD type_target INT NOT NULL, DROP immune_type, DROP innefective_attack_type');
        $this->addSql('ALTER TABLE immune_to ADD CONSTRAINT FK_37ED40C77C5D144B FOREIGN KEY (type_source) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE immune_to ADD CONSTRAINT FK_37ED40C765B844C4 FOREIGN KEY (type_target) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_37ED40C77C5D144B ON immune_to (type_source)');
        $this->addSql('CREATE INDEX IDX_37ED40C765B844C4 ON immune_to (type_target)');
        $this->addSql('ALTER TABLE immune_to ADD PRIMARY KEY (type_source, type_target)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE immune_to DROP FOREIGN KEY FK_37ED40C77C5D144B');
        $this->addSql('ALTER TABLE immune_to DROP FOREIGN KEY FK_37ED40C765B844C4');
        $this->addSql('DROP INDEX IDX_37ED40C77C5D144B ON immune_to');
        $this->addSql('DROP INDEX IDX_37ED40C765B844C4 ON immune_to');
        $this->addSql('ALTER TABLE immune_to DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE immune_to ADD immune_type INT NOT NULL, ADD innefective_attack_type INT NOT NULL, DROP type_source, DROP type_target');
        $this->addSql('ALTER TABLE immune_to ADD CONSTRAINT FK_5BD1922165B844C7 FOREIGN KEY (innefective_attack_type) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE immune_to ADD CONSTRAINT FK_5BD192217C5D144E FOREIGN KEY (immune_type) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_5BD192217C5D144B ON immune_to (immune_type)');
        $this->addSql('CREATE INDEX IDX_5BD1922165B844C4 ON immune_to (innefective_attack_type)');
        $this->addSql('ALTER TABLE immune_to ADD PRIMARY KEY (immune_type, innefective_attack_type)');
        $this->addSql('ALTER TABLE neutral_to DROP FOREIGN KEY FK_A431E0A87C5D144B');
        $this->addSql('ALTER TABLE neutral_to DROP FOREIGN KEY FK_A431E0A865B844C4');
        $this->addSql('DROP INDEX IDX_A431E0A87C5D144B ON neutral_to');
        $this->addSql('DROP INDEX IDX_A431E0A865B844C4 ON neutral_to');
        $this->addSql('ALTER TABLE neutral_to DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE neutral_to ADD neutral_type INT NOT NULL, ADD neutral_attack_type INT NOT NULL, DROP type_source, DROP type_target');
        $this->addSql('ALTER TABLE neutral_to ADD CONSTRAINT FK_5BD1922165B844C6 FOREIGN KEY (neutral_attack_type) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE neutral_to ADD CONSTRAINT FK_5BD192217C5D144D FOREIGN KEY (neutral_type) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_5BD192217C5D144B ON neutral_to (neutral_type)');
        $this->addSql('CREATE INDEX IDX_5BD1922165B844C4 ON neutral_to (neutral_attack_type)');
        $this->addSql('ALTER TABLE neutral_to ADD PRIMARY KEY (neutral_type, neutral_attack_type)');
        $this->addSql('ALTER TABLE resistant_to DROP FOREIGN KEY FK_B52803FF7C5D144B');
        $this->addSql('ALTER TABLE resistant_to DROP FOREIGN KEY FK_B52803FF65B844C4');
        $this->addSql('DROP INDEX IDX_B52803FF7C5D144B ON resistant_to');
        $this->addSql('DROP INDEX IDX_B52803FF65B844C4 ON resistant_to');
        $this->addSql('ALTER TABLE resistant_to DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE resistant_to ADD resistant_type INT NOT NULL, ADD weak_attack_type INT NOT NULL, DROP type_source, DROP type_target');
        $this->addSql('ALTER TABLE resistant_to ADD CONSTRAINT FK_5BD1922165B844C5 FOREIGN KEY (weak_attack_type) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE resistant_to ADD CONSTRAINT FK_5BD192217C5D144C FOREIGN KEY (resistant_type) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_5BD192217C5D144B ON resistant_to (resistant_type)');
        $this->addSql('CREATE INDEX IDX_5BD1922165B844C4 ON resistant_to (weak_attack_type)');
        $this->addSql('ALTER TABLE resistant_to ADD PRIMARY KEY (resistant_type, weak_attack_type)');
        $this->addSql('ALTER TABLE vulnerable_to DROP FOREIGN KEY FK_D94ACCE7C5D144B');
        $this->addSql('ALTER TABLE vulnerable_to DROP FOREIGN KEY FK_D94ACCE65B844C4');
        $this->addSql('DROP INDEX IDX_D94ACCE7C5D144B ON vulnerable_to');
        $this->addSql('DROP INDEX IDX_D94ACCE65B844C4 ON vulnerable_to');
        $this->addSql('ALTER TABLE vulnerable_to DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE vulnerable_to ADD vulnerable_type INT NOT NULL, ADD effective_attack_type INT NOT NULL, DROP type_source, DROP type_target');
        $this->addSql('ALTER TABLE vulnerable_to ADD CONSTRAINT FK_5BD1922165B844C4 FOREIGN KEY (effective_attack_type) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vulnerable_to ADD CONSTRAINT FK_5BD192217C5D144B FOREIGN KEY (vulnerable_type) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_5BD192217C5D144B ON vulnerable_to (vulnerable_type)');
        $this->addSql('CREATE INDEX IDX_5BD1922165B844C4 ON vulnerable_to (effective_attack_type)');
        $this->addSql('ALTER TABLE vulnerable_to ADD PRIMARY KEY (vulnerable_type, effective_attack_type)');
    }
}
