<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190907040440 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE transaction');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, compte_id INT DEFAULT NULL, montant BIGINT NOT NULL, commissionsysteme BIGINT DEFAULT NULL, commissionetat BIGINT DEFAULT NULL, numpieceenv BIGINT DEFAULT NULL, code BIGINT NOT NULL, nom_envoyeur VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, nombeneficiaire VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, typepieceenvoyeur VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, typepieceben VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, numpieceben BIGINT DEFAULT NULL, date_envoie DATE DEFAULT NULL, date_retrait DATE DEFAULT NULL, INDEX IDX_723705D1F2C56620 (compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1F2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id)');
    }
}
