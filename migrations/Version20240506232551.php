<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240506232551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE driver_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE prediction_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE prediction_comparison_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE race_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE race_result_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE team_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE driver (id VARCHAR(255) NOT NULL, number VARCHAR(255) NOT NULL, full_name VARCHAR(255) NOT NULL, season_points DOUBLE PRECISION NOT NULL, team_id VARCHAR DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_11667CD9296CD8AE ON driver (team_id)');
        $this->addSql('CREATE TABLE prediction (id VARCHAR(255) NOT NULL, driver_id VARCHAR DEFAULT NULL, race_id VARCHAR DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_36396FC8C3423909 ON prediction (driver_id)');
        $this->addSql('CREATE INDEX IDX_36396FC86E59D40D ON prediction (race_id)');
        $this->addSql('CREATE TABLE prediction_comparison (id VARCHAR(255) NOT NULL, leader_position INT NOT NULL, leader_fl BOOLEAN NOT NULL, highest_position INT NOT NULL, without_fl BOOLEAN NOT NULL, contender_id VARCHAR DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7CB788155C57E450 ON prediction_comparison (contender_id)');
        $this->addSql('CREATE TABLE race (id VARCHAR(255) NOT NULL, date DATE NOT NULL, completed BOOLEAN NOT NULL, canceled BOOLEAN NOT NULL, full_distance BOOLEAN NOT NULL, sprint_race BOOLEAN NOT NULL, grand_prix VARCHAR(255) NOT NULL, season_id VARCHAR DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DA6FBBAF4EC001D1 ON race (season_id)');
        $this->addSql('CREATE TABLE race_result (id VARCHAR(255) NOT NULL, position INT NOT NULL, points DOUBLE PRECISION NOT NULL, result_status VARCHAR(255) DEFAULT NULL, race_id VARCHAR DEFAULT NULL, season_id VARCHAR DEFAULT NULL, driver_id VARCHAR DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_793CDFC06E59D40D ON race_result (race_id)');
        $this->addSql('CREATE INDEX IDX_793CDFC04EC001D1 ON race_result (season_id)');
        $this->addSql('CREATE INDEX IDX_793CDFC0C3423909 ON race_result (driver_id)');
        $this->addSql('CREATE TABLE season (id VARCHAR(255) NOT NULL, starts_at DATE NOT NULL, ends_at DATE NOT NULL, races INT NOT NULL, sprints INT NOT NULL, completed_races INT NOT NULL, completed_sprints INT NOT NULL, champion_id VARCHAR DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F0E45BA9FA7FD7EB ON season (champion_id)');
        $this->addSql('CREATE TABLE team (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE driver ADD CONSTRAINT FK_11667CD9296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prediction ADD CONSTRAINT FK_36396FC8C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prediction ADD CONSTRAINT FK_36396FC86E59D40D FOREIGN KEY (race_id) REFERENCES race (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prediction_comparison ADD CONSTRAINT FK_7CB788155C57E450 FOREIGN KEY (contender_id) REFERENCES driver (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE race ADD CONSTRAINT FK_DA6FBBAF4EC001D1 FOREIGN KEY (season_id) REFERENCES season (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE race_result ADD CONSTRAINT FK_793CDFC06E59D40D FOREIGN KEY (race_id) REFERENCES race (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE race_result ADD CONSTRAINT FK_793CDFC04EC001D1 FOREIGN KEY (season_id) REFERENCES season (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE race_result ADD CONSTRAINT FK_793CDFC0C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE season ADD CONSTRAINT FK_F0E45BA9FA7FD7EB FOREIGN KEY (champion_id) REFERENCES driver (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE driver_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE prediction_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE prediction_comparison_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE race_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE race_result_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE team_id_seq CASCADE');
        $this->addSql('ALTER TABLE driver DROP CONSTRAINT FK_11667CD9296CD8AE');
        $this->addSql('ALTER TABLE prediction DROP CONSTRAINT FK_36396FC8C3423909');
        $this->addSql('ALTER TABLE prediction DROP CONSTRAINT FK_36396FC86E59D40D');
        $this->addSql('ALTER TABLE prediction_comparison DROP CONSTRAINT FK_7CB788155C57E450');
        $this->addSql('ALTER TABLE race DROP CONSTRAINT FK_DA6FBBAF4EC001D1');
        $this->addSql('ALTER TABLE race_result DROP CONSTRAINT FK_793CDFC06E59D40D');
        $this->addSql('ALTER TABLE race_result DROP CONSTRAINT FK_793CDFC04EC001D1');
        $this->addSql('ALTER TABLE race_result DROP CONSTRAINT FK_793CDFC0C3423909');
        $this->addSql('ALTER TABLE season DROP CONSTRAINT FK_F0E45BA9FA7FD7EB');
        $this->addSql('DROP TABLE driver');
        $this->addSql('DROP TABLE prediction');
        $this->addSql('DROP TABLE prediction_comparison');
        $this->addSql('DROP TABLE race');
        $this->addSql('DROP TABLE race_result');
        $this->addSql('DROP TABLE season');
        $this->addSql('DROP TABLE team');
    }
}
