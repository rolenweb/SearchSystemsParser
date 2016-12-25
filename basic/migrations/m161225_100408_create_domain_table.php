<?php

use yii\db\Migration;

/**
 * Handles the creation of table `domain`.
 */
class m161225_100408_create_domain_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%domain}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'registrar' => $this->string(),
            'creation_date' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('idx-domain-title', '{{%domain}}', 'title');
    }

    public function down()
    {
        $this->dropIndex('idx-domain-title', '{{%domain}}');
        $this->dropTable('{{%domain}}');
    }
}
