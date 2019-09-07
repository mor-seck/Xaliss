<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190907060222 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE transactions (id INT AUTO_INCREMENT NOT NULL, agentenv_id INT DEFAULT NULL, agentretrait_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, montant BIGINT NOT NULL, nom_comple_env VARCHAR(255) DEFAULT NULL, nom_comple_ben VARCHAR(255) DEFAULT NULL, telenv VARCHAR(255) DEFAULT NULL, teleben VARCHAR(255) DEFAULT NULL, numpieceenv BIGINT DEFAULT NULL, typepieceenv VARCHAR(255) DEFAULT NULL, numpieceben BIGINT DEFAULT NULL, typepieceben VARCHAR(255) DEFAULT NULL, dateenv DATE DEFAULT NULL, dateretrait DATE DEFAULT NULL, commission_system BIGINT DEFAULT NULL, commissionagentenv BIGINT DEFAULT NULL, commissionagentretrait BIGINT DEFAULT NULL, commissionetat BIGINT DEFAULT NULL, INDEX IDX_EAA81A4CAB3BD1E0 (agentenv_id), INDEX IDX_EAA81A4CC4306223 (agentretrait_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4CAB3BD1E0 FOREIGN KEY (agentenv_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4CC4306223 FOREIGN KEY (agentretrait_id) REFERENCES utilisateur (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE transactions');
    }
}
