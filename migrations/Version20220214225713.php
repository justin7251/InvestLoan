<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220214225713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE investor (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, wallet_amount NUMERIC(8, 2) NOT NULL)');
        $this->addSql('CREATE TABLE loan (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, invest_amount NUMERIC(8, 2) NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, investor_id INTEGER NOT NULL, tranche_id INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE tranche (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, interest_rate NUMERIC(8, 2) NOT NULL, current_amount NUMERIC(8, 2) NOT NULL, maximum_allowance NUMERIC(8, 2) NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE investor');
        $this->addSql('DROP TABLE loan');
        $this->addSql('DROP TABLE tranche');
    }
}
