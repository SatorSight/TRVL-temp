<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161215103653 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->getTable('profile');

        $table->dropColumn('gender');
        $table->dropColumn('i_want');
        $table->dropColumn('relations');
        $table->dropColumn('orientation');
        $table->dropColumn('banned');


        $table->addColumn('age', 'string');
        $table->addColumn('sex', 'string');
        $table->addColumn('city', 'string');
        //$table->addColumn('appearance', 'string');
        //$table->addColumn('about_me', 'string');
        $table->addColumn('wanna_communicate', 'integer');
        $table->addColumn('find_companion', 'integer');
        $table->addColumn('find_couple', 'integer');
        $table->addColumn('find_friends', 'integer');
        $table->addColumn('free', 'integer');
        $table->addColumn('orientation', 'integer');



    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {

        $table = $schema->getTable('profile');
        $table->dropColumn('age');
        $table->dropColumn('sex');
        $table->dropColumn('city');
        $table->dropColumn('appearance');
        $table->dropColumn('about_me');
        $table->dropColumn('wanna_communicate');
        $table->dropColumn('find_companion');
        $table->dropColumn('find_couple');
        $table->dropColumn('find_friends');
        $table->dropColumn('free');
        $table->dropColumn('orientation');
        // this down() migration is auto-generated, please modify it to your needs

    }
}
