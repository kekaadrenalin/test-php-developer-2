<?php

use common\models\db\User;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'formatter'    => ['class' => 'yii\i18n\Formatter'],

        'columns' => [
            'id',
            'username',

            [
                'label'     => 'ФИО',
                'attribute' => 'fio',
            ],

            'email:email',

            [
                'label'     => 'Подписка',
                'attribute' => 'subscription_date',
                'format'    => ['date', 'php:d-m-Y'],
            ],

            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{update}',
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>