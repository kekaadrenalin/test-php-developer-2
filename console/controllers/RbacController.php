<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use rbac\UserGroupRule;

/**
 * Class RbacController
 * @package console\controllers
 */
class RbacController extends Controller
{
    /**
     * Init RBAC
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $rule = new UserGroupRule;
        $auth->add($rule);

        $admin = $auth->createRole('admin');
        $admin->ruleName = $rule->name;
        $admin->description = 'Администратор';
        $auth->add($admin);
        $auth->assign($admin, 1);
    }
}