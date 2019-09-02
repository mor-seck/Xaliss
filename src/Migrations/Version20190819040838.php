<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190819040838 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE retrait (id INT AUTO_INCREMENT NOT NULL, agence_id INT DEFAULT NULL, nomben VARCHAR(255) NOT NULL, pieceben VARCHAR(255) NOT NULL, numpieceben VARCHAR(255) NOT NULL, montant VARCHAR(255) NOT NULL, commission VARCHAR(255) NOT NULL, INDEX IDX_D9846A51D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE retrait ADD CONSTRAINT FK_D9846A51D725330D FOREIGN KEY (agence_id) REFERENCES partenaire (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE retrait');
    }
}
