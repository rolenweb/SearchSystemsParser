<?php

use yii\db\Migration;

/**
 * Handles the creation of table `webspider`.
 */
class m170331_120133_create_webspider_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%webspider}}', [
            'id' => $this->primaryKey(),
            'domain' => $this->string(),
            'url' => $this->string(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('idx-webspider-url', '{{%webspider}}', 'url');
        $this->createIndex('idx-webspider-domain', '{{%webspider}}', 'domain');
    }

    public function down()
    {
        $this->dropIndex('idx-webspider-url', '{{%webspider}}');
        $this->dropIndex('idx-webspider-domain', '{{%webspider}}');
        $this->dropTable('{{%webspider}}');
    }
}
