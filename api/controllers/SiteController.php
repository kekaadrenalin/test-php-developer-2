<?php

namespace api\controllers;

use Yii;

/**
 * Site controller
 */
class SiteController extends _BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        return $behaviors;
    }

    /**
     * Displays homepage.
     *
     * @return array
     */
    public function actionIndex()
    {
        return [
            'success' => 'ok',
        ];
    }
}
