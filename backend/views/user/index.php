<?php

use common\models\db\User;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
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
        'columns'      => [
            [
                'label'     => 'ID',
                'attribute' => 'id',
            ],

            [
                'label'     => 'Логин',
                'attribute' => 'username',
            ],

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
                'attribute' => 'subscription',
                'value'     => 'subscription.date_time',
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>