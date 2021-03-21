<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201211102512 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders CHANGE users_Id users_Id INT DEFAULT NULL, CHANGE product_Id product_Id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE producer CHANGE producer_Id producer_Id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(255) NOT NULL, ADD last_name VARCHAR(255) NOT NULL, DROP firstName, DROP lastName');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders CHANGE users_Id users_Id INT NOT NULL, CHANGE product_Id product_Id INT NOT NULL');
        $this->addSql('ALTER TABLE producer CHANGE producer_Id producer_Id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD firstName VARCHAR(55) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD lastName VARCHAR(55) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP first_name, DROP last_name');
    }
}
