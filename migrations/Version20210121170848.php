<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210121170848 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE label_todo_list');
        $this->addSql('ALTER TABLE todo_list DROP deadline');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE label_todo_list (label_id INT NOT NULL, todo_list_id INT NOT NULL, INDEX IDX_F055A62133B92F39 (label_id), INDEX IDX_F055A621E8A7DCFA (todo_list_id), PRIMARY KEY(label_id, todo_list_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE label_todo_list ADD CONSTRAINT FK_F055A62133B92F39 FOREIGN KEY (label_id) REFERENCES label (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE label_todo_list ADD CONSTRAINT FK_F055A621E8A7DCFA FOREIGN KEY (todo_list_id) REFERENCES todo_list (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE todo_list ADD deadline DATETIME DEFAULT NULL');
    }
}
