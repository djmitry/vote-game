<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221130111616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shop_item (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, price INT NOT NULL, type SMALLINT NOT NULL, value INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_DEE9C365989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_shop_item (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, shop_item_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_DE094E81A76ED395 (user_id), INDEX IDX_DE094E81115C1274 (shop_item_id), UNIQUE INDEX UNIQ_DE094E81A76ED395115C1274 (user_id, shop_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_shop_item ADD CONSTRAINT FK_DE094E81A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_shop_item ADD CONSTRAINT FK_DE094E81115C1274 FOREIGN KEY (shop_item_id) REFERENCES shop_item (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_shop_item DROP FOREIGN KEY FK_DE094E81A76ED395');
        $this->addSql('ALTER TABLE user_shop_item DROP FOREIGN KEY FK_DE094E81115C1274');
        $this->addSql('DROP TABLE shop_item');
        $this->addSql('DROP TABLE user_shop_item');
    }
}
