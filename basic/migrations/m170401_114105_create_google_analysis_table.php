<?php

use yii\db\Migration;

/**
 * Handles the creation of table `google_analysis`.
 */
class m170401_114105_create_google_analysis_table extends Migration
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

        $this->createTable('{{%google_analysis}}', [
            'id' => $this->primaryKey(),
            'keyword_id' => $this->integer(),
            'domain_id' => $this->integer(),
            'url' => $this->string(),
            'position' => $this->integer(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('idx-google_analysis-url', '{{%google_analysis}}', 'url');
        $this->createIndex('idx-google_analysis-domain_id', '{{%google_analysis}}', 'domain_id');
        $this->createIndex('idx-google_analysis-keyword_id', '{{%google_analysis}}', 'keyword_id');
    }

    public function down()
    {
        $this->dropIndex('idx-google_analysis-url', '{{%google_analysis}}');
        $this->dropIndex('idx-google_analysis-domain_id', '{{%google_analysis}}');
        $this->dropIndex('idx-google_analysis-keyword_id', '{{%google_analysis}}');
        $this->dropTable('{{%google_analysis}}');
    }
}
