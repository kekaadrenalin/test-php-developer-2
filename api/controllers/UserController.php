<?php

namespace api\controllers;

use Yii;
use common\models\db\User;
use yii\helpers\ArrayHelper;

/**
 * Site controller
 * @package api\controllers
 */
class UserController extends _BaseController
{
    /**
     * @return array|null
     */
    public function actionIndex()
    {
        if (!$user = User::findAllSimpleUsersWithSubscriptionDate()) {
            return null;
        }

        return ArrayHelper::toArray($user, [
            User::class => [
                'id',
                'login'             => 'username',
                'fio',
                'subscription_date' => function ($user) {
                    return date('d-m-Y', $user->subscription_date);
                },
            ],
        ]);
    }

    /**
     * @param int $id
     *
     * @return array|null
     */
    public function actionView(int $id)
    {
        if (!$user = User::findOneWithSubscriptionDateById($id)) {
            return null;
        }

        return ArrayHelper::toArray($user, [
            User::class => [
                'id',
                'login'             => 'username',
                'fio',
                'subscription_date' => function ($user) {
                    return date('d-m-Y', $user->subscription_date);
                },
            ],
        ]);
    }
}
