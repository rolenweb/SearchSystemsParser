<?php

use yii\db\Migration;

/**
 * Handles the creation of table `positon_parser`.
 */
class m161225_104131_create_positon_parser_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%position_parser}}', [
            'id' => $this->primaryKey(),
            'keyword_id' => $this->integer(),
            'search_system_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%position_parser}}');
    }
}
