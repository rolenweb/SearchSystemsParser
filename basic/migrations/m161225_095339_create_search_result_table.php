<?php

use yii\db\Migration;

/**
 * Handles the creation of table `search_result`.
 */
class m161225_095339_create_search_result_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%search_result}}', [
            'id' => $this->primaryKey(),
            'keyword_id' => $this->integer(),
            'search_system_id' => $this->integer(),
            'domain_id' => $this->integer(),
            'title' => $this->text(),
            'description' => $this->text(),
            'url' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('idx-search_result-keyword_id', '{{%search_result}}', 'keyword_id');
        $this->createIndex('idx-search_result-domain_id', '{{%search_result}}', 'domain_id');
    }

    public function down()
    {
        $this->dropIndex('idx-search_result-keyword_id', '{{%search_result}}');
        $this->dropIndex('idx-search_result-domain_id', '{{%search_result}}');
        $this->dropTable('{{%search_result}}');
    }
}
