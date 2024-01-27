<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240127094551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'First migration to initialize entities';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE access_day (id INT AUTO_INCREMENT NOT NULL, day_name VARCHAR(30) NOT NULL, UNIQUE INDEX UNIQ_85E07445F54B9370 (day_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parking_spot (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, zipcode VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parking_spot_access_day (parking_spot_id INT NOT NULL, access_day_id INT NOT NULL, INDEX IDX_D03BA83BA31B2BA6 (parking_spot_id), INDEX IDX_D03BA83BFB49D414 (access_day_id), PRIMARY KEY(parking_spot_id, access_day_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, truck_id INT DEFAULT NULL, parking_spot_id INT DEFAULT NULL, date_reservation DATETIME NOT NULL, week_number VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_42C84955C6957CCE (truck_id), INDEX IDX_42C84955A31B2BA6 (parking_spot_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE truck (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE parking_spot_access_day ADD CONSTRAINT FK_D03BA83BA31B2BA6 FOREIGN KEY (parking_spot_id) REFERENCES parking_spot (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE parking_spot_access_day ADD CONSTRAINT FK_D03BA83BFB49D414 FOREIGN KEY (access_day_id) REFERENCES access_day (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955C6957CCE FOREIGN KEY (truck_id) REFERENCES truck (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A31B2BA6 FOREIGN KEY (parking_spot_id) REFERENCES parking_spot (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE parking_spot_access_day DROP FOREIGN KEY FK_D03BA83BA31B2BA6');
        $this->addSql('ALTER TABLE parking_spot_access_day DROP FOREIGN KEY FK_D03BA83BFB49D414');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955C6957CCE');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A31B2BA6');
        $this->addSql('DROP TABLE access_day');
        $this->addSql('DROP TABLE parking_spot');
        $this->addSql('DROP TABLE parking_spot_access_day');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE truck');
    }
}
