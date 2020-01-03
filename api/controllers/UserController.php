<?php

namespace api\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\db\User;
use common\models\form\UserForm;

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
                'login'             => 'login',
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
                'login'             => 'login',
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
    public function actionUpdate(int $id)
    {
        $user = UserForm::findOne($id);

        if ($user->load(Yii::$app->request->bodyParams, '') && $user->save()) {
            return ['success' => 'ok'];
        }

        return null;
    }
}
