<?php

namespace api\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UnauthorizedHttpException;

/**
 * Class _BaseController
 * @package api\controllers
 */
class _BaseController extends Controller
{
    /**
     * @param $action
     *
     * @return bool
     * @throws UnauthorizedHttpException
     */
    public function beforeAction($action)
    {
        $headers = Yii::$app->request->headers;

        $login = getenv('AUTH_LOGIN');
        $password = getenv('AUTH_PASSWORD');

        if ($headers->has('Authorization')) {
            $token = $headers->get('Authorization');

            if ($token === 'Basic ' . base64_encode("{$login}:{$password}")) {
                Yii::$app->request->enableCsrfValidation = false;

                return parent::beforeAction($action);
            }
        }

        throw new UnauthorizedHttpException('Your request was made with invalid credentials.');
    }
}
