<?php

namespace api\models\identity;

/**
 * Class User
 * @package api\models\identity
 */
class User extends \common\models\User
{
    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }
}