<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240614090239 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipe ADD section_id INT NOT NULL');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA15D823E37A FOREIGN KEY (section_id) REFERENCES section (id)');
        $this->addSql('CREATE INDEX IDX_2449BA15D823E37A ON equipe (section_id)');
        $this->addSql('ALTER TABLE licencie ADD equipe_id INT NOT NULL');
        $this->addSql('ALTER TABLE licencie ADD CONSTRAINT FK_3B7556126D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id)');
        $this->addSql('CREATE INDEX IDX_3B7556126D861B89 ON licencie (equipe_id)');
        $this->addSql('ALTER TABLE section ADD club_id INT NOT NULL');
        $this->addSql('ALTER TABLE section ADD CONSTRAINT FK_2D737AEF61190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('CREATE INDEX IDX_2D737AEF61190A32 ON section (club_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA15D823E37A');
        $this->addSql('DROP INDEX IDX_2449BA15D823E37A ON equipe');
        $this->addSql('ALTER TABLE equipe DROP section_id');
        $this->addSql('ALTER TABLE licencie DROP FOREIGN KEY FK_3B7556126D861B89');
        $this->addSql('DROP INDEX IDX_3B7556126D861B89 ON licencie');
        $this->addSql('ALTER TABLE licencie DROP equipe_id');
        $this->addSql('ALTER TABLE section DROP FOREIGN KEY FK_2D737AEF61190A32');
        $this->addSql('DROP INDEX IDX_2D737AEF61190A32 ON section');
        $this->addSql('ALTER TABLE section DROP club_id');
    }
}
