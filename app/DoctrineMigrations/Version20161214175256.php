<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161214175256 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->getTable('profile');
        $table->dropColumn('family');
        $table->dropColumn('email');
        $table->dropColumn('vk');
        $table->dropColumn('vk_token');
        $table->dropColumn('fb');
        $table->dropColumn('fb_token');
        $table->dropColumn('city_id');
        $table->dropColumn('bd');

        $table->addColumn('banned', 'boolean');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $table = $schema->getTable('profile');
        $table->addColumn('count_badge', 'integer');

        $table->dropColumn('banned');
    }
}
