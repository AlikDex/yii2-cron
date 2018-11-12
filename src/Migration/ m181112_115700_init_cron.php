<?php

use yii\db\Migration;

/**
 * Class m181112_115700_init_cron
 */
class m181112_115700_init_cron extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('cron_tasks', [
            'task_id' => 'smallint(5) unsigned NOT NULL',
            'expression' => 'varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT \'\'',
            'command' => 'text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL',
            'priority' => 'smallint(5) unsigned NOT NULL DEFAULT 1000',
            'last_execution' => 'timestamp NULL DEFAULT NULL',
            'duration' => 'double unsigned DEFAULT 0',
            'status' => 'enum(\'\',\'planned\',\'running\',\'completed\',\'failed\',\'aborted\') COLLATE latin1_general_ci NOT NULL DEFAULT \'\'',
            'enabled' => 'tinyint(3) unsigned NOT NULL DEFAULT 1',
            'created_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ], $tableOptions);

        $this->addPrimaryKey('task_id', 'cron_tasks', 'task_id');
        $this->execute("ALTER TABLE `cron_tasks` MODIFY `task_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181112_115700_init_cron cannot be reverted.\n";

        return false;
    }
}
