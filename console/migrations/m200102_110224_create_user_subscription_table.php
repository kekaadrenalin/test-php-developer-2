<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_subscription}}`.
 */
class m200102_110224_create_user_subscription_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_subscription}}', [
            'id'       => $this->primaryKey(),
            'user_id'  => $this->integer()->notNull()->unique(),
            'date_end' => $this->integer()->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-user_subscription-user_id',
            'user_subscription',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-user_subscription-user_id',
            'user_subscription',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-user_subscription-user_id',
            'user_subscription'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-user_subscription-user_id',
            'user_subscription'
        );

        $this->dropTable('{{%user_subscription}}');
    }
}
