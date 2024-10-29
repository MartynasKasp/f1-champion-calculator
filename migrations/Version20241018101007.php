<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241018101007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE prediction ADD created_at DATE DEFAULT NULL');
        // $this->addSql('ALTER TABLE prediction ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');

        $this->addSql('ALTER TABLE prediction_comparison ADD prediction_id VARCHAR DEFAULT NULL');
        $this->addSql('ALTER TABLE prediction_comparison ADD CONSTRAINT FK_7CB78815449DFD9E FOREIGN KEY (prediction_id) REFERENCES prediction (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('CREATE INDEX IDX_7CB78815449DFD9E ON prediction_comparison (prediction_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE prediction_comparison DROP CONSTRAINT FK_7CB78815449DFD9E');

        $this->addSql('DROP INDEX IDX_7CB78815449DFD9E');
        $this->addSql('ALTER TABLE prediction_comparison DROP prediction_id');

        $this->addSql('ALTER TABLE prediction DROP created_at');
    }
}
