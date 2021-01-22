<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210121144211 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE label (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE label_todo_list (label_id INT NOT NULL, todo_list_id INT NOT NULL, INDEX IDX_F055A62133B92F39 (label_id), INDEX IDX_F055A621E8A7DCFA (todo_list_id), PRIMARY KEY(label_id, todo_list_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE list_element (id INT AUTO_INCREMENT NOT NULL, list_id INT NOT NULL, content VARCHAR(255) NOT NULL, done TINYINT(1) NOT NULL, info JSON NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_9D0F83343DAE168B (list_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE todo_list (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, priority INT NOT NULL, description LONGTEXT NOT NULL, done TINYINT(1) NOT NULL, elements_count INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_1B199E07A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE label_todo_list ADD CONSTRAINT FK_F055A62133B92F39 FOREIGN KEY (label_id) REFERENCES label (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE label_todo_list ADD CONSTRAINT FK_F055A621E8A7DCFA FOREIGN KEY (todo_list_id) REFERENCES todo_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE list_element ADD CONSTRAINT FK_9D0F83343DAE168B FOREIGN KEY (list_id) REFERENCES todo_list (id)');
        $this->addSql('ALTER TABLE todo_list ADD CONSTRAINT FK_1B199E07A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE label_todo_list DROP FOREIGN KEY FK_F055A62133B92F39');
        $this->addSql('ALTER TABLE label_todo_list DROP FOREIGN KEY FK_F055A621E8A7DCFA');
        $this->addSql('ALTER TABLE list_element DROP FOREIGN KEY FK_9D0F83343DAE168B');
        $this->addSql('DROP TABLE label');
        $this->addSql('DROP TABLE label_todo_list');
        $this->addSql('DROP TABLE list_element');
        $this->addSql('DROP TABLE todo_list');
    }
}
