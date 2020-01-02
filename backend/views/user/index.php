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

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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
                'value'     => function ($model) {
                    /** @var User $model */
                    return join(' ', [
                        $model->family,
                        $model->name,
                        $model->patronymic,
                    ]);
                },
            ],

            'email:email',

            [
                'label'     => 'Подписка',
                'attribute' => 'subscription',
                'value'     => 'subscription.date_end',
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