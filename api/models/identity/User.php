<?php

namespace api\models\identity;

/**
 * Class User
 * @package api\models\identity
 */
class User extends \common\models\User
{
    /**
     * @param      $token
     * @param null $type
     *
     * @return User|void|\yii\web\IdentityInterface|null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }
}