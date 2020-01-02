<?php

use yii\db\Migration;

/**
 * Class m200102_124337_add_admin_into_user_table
 */
class m200102_124337_add_admin_into_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%user}}', [
            'username'             => 'admin_1',
            'auth_key'             => 'HP187Mvq7Mmm3CTU80dLkGmni_FUH_lR',
            'password_hash'        => '$2y$13$EjaPFBnZOQsHdGuHI.xvhuDp1fHpo8hKRSk6yshqa9c5EG8s3C3lO',
            'password_reset_token' => 'ExzkCOaYc1L8IOBs4wdTGGbgNiG3Wz1I_1402312317',
            'created_at'           => '1402312317',
            'updated_at'           => '1402312317',
            'email'                => 'nicole.paucek@schultz.info',
            'family'               => 'Админ',
            'name'                 => 'Админ',
            'patronymic'           => 'Админович',
            'role'                 => 10,
            'status'               => 10,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%user}}', ['username' => 'admin_1']);
    }
}
