<?php

use yii\db\Migration;

/**
 * Update table `{{%user}}`.
 */
class m200102_105204_update_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            '{{%user}}',
            'role',
            $this->tinyInteger()->notNull()->defaultValue(0)->after('email')
        );

        $this->addColumn(
            '{{%user}}',
            'patronymic',
            $this->string()->after('email')->defaultValue(null)
        );
        $this->addColumn(
            '{{%user}}',
            'name',
            $this->string()->after('email')->defaultValue(null)
        );
        $this->addColumn(
            '{{%user}}',
            'family',
            $this->string()->after('email')->defaultValue(null)
        );

        $this->addCommentOnColumn('{{%user}}', 'role', 'Роль');
        $this->addCommentOnColumn('{{%user}}', 'family', 'Фамилия');
        $this->addCommentOnColumn('{{%user}}', 'name', 'Имя');
        $this->addCommentOnColumn('{{%user}}', 'patronymic', 'Отчество');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'family');
        $this->dropColumn('{{%user}}', 'name');
        $this->dropColumn('{{%user}}', 'patronymic');
        $this->dropColumn('{{%user}}', 'role');
    }
}
