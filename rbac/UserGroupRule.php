<?php

namespace rbac;

use Yii;
use yii\rbac\Item;
use yii\rbac\Rule;
use common\models\db\User;

/**
 * Checks if user group matches
 */
class UserGroupRule extends Rule
{
    /** @var string */
    public $name = 'userGroup';

    /**
     * @param int|string $user
     * @param Item       $item
     * @param array      $params
     *
     * @return bool
     */
    public function execute($user, $item, $params)
    {
        if (!Yii::$app->user->isGuest) {
            $role = (int)Yii::$app->user->identity->role;

            if ($item->name === 'admin') {
                return $role === User::ROLE_ADMIN;
            }
        }

        return false;
    }
}