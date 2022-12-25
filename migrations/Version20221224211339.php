<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221224211339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notes ADD note1 DOUBLE PRECISION DEFAULT NULL, ADD coef1 DOUBLE PRECISION DEFAULT NULL, ADD note2 DOUBLE PRECISION DEFAULT NULL, ADD coef2 DOUBLE PRECISION DEFAULT NULL, ADD note3 DOUBLE PRECISION DEFAULT NULL, ADD coef3 DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notes DROP note1, DROP coef1, DROP note2, DROP coef2, DROP note3, DROP coef3');
    }
}
