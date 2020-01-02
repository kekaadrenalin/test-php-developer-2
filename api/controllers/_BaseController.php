<?php

namespace api\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\auth\HttpBasicAuth;

/**
 * Site controller
 */
class _BaseController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::class,
        ];

        return $behaviors;
    }
}
