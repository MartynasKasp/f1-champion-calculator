<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251128085641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds "constructor" (Team) column to RaceResult table as a foreign key';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE prediction ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE race_result ADD constructor_id VARCHAR DEFAULT NULL');
        $this->addSql('ALTER TABLE race_result ADD CONSTRAINT FK_793CDFC02D98BF9 FOREIGN KEY (constructor_id) REFERENCES team (id)');
        $this->addSql('CREATE INDEX IDX_793CDFC02D98BF9 ON race_result (constructor_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE prediction ALTER created_at TYPE DATE');
        $this->addSql('ALTER TABLE race_result DROP CONSTRAINT FK_793CDFC02D98BF9');
        $this->addSql('DROP INDEX IDX_793CDFC02D98BF9');
        $this->addSql('ALTER TABLE race_result DROP constructor_id');
    }
}
