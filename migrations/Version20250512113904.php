<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250512113904 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE cart DROP FOREIGN KEY FK_BA388B79D86650F
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_BA388B79D86650F ON cart
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart CHANGE user_id user_id_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart ADD CONSTRAINT FK_BA388B79D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_BA388B79D86650F ON cart (user_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE2527DE18E50B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE252720AEF35F
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_F0FE2527DE18E50B ON cart_item
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_F0FE252720AEF35F ON cart_item
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD product_id_id INT NOT NULL, ADD cart_id_id INT NOT NULL, DROP product_id, DROP cart_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE2527DE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE252720AEF35F FOREIGN KEY (cart_id_id) REFERENCES cart (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F0FE2527DE18E50B ON cart_item (product_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F0FE252720AEF35F ON cart_item (cart_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product ADD category_id_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product ADD CONSTRAINT FK_D34A04AD9777D11E FOREIGN KEY (category_id_id) REFERENCES category (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D34A04AD9777D11E ON product (category_id_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE cart DROP FOREIGN KEY FK_BA388B79D86650F
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_BA388B79D86650F ON cart
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart CHANGE user_id_id user_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart ADD CONSTRAINT FK_BA388B79D86650F FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_BA388B79D86650F ON cart (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE2527DE18E50B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE252720AEF35F
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_F0FE2527DE18E50B ON cart_item
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_F0FE252720AEF35F ON cart_item
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD product_id INT NOT NULL, ADD cart_id INT NOT NULL, DROP product_id_id, DROP cart_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE2527DE18E50B FOREIGN KEY (product_id) REFERENCES product (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE252720AEF35F FOREIGN KEY (cart_id) REFERENCES cart (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F0FE2527DE18E50B ON cart_item (product_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F0FE252720AEF35F ON cart_item (cart_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD9777D11E
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D34A04AD9777D11E ON product
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product DROP category_id_id
        SQL);
    }
}
