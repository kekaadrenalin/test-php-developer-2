<?php

namespace api\controllers;

use Yii;
use common\models\db\User;

/**
 * Site controller
 * @package api\controllers
 */
class UserController extends _BaseController
{
    /**
     * Displays homepage.
     *
     * @return array
     */
    public function actionIndex()
    {
        return User::findAllSimpleUsersAsArray();
    }
}
