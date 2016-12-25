<?php

use yii\db\Migration;

class m161224_143718_create_table_keyword extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%keyword}}', [
            'id' => $this->primaryKey(),
            'key' => $this->string(),
            'theme_keyword_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('idx-keyword-key', '{{%keyword}}', 'key');
    }

    public function down()
    {
        $this->dropIndex('idx-keyword-key', '{{%keyword}}');
        $this->dropTable('{{%keyword}}');
    }
}
