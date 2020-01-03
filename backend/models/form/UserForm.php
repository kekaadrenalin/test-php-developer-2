<?php

namespace backend\models\form;

use Yii;
use Exception;

use yii\base\Model;
use yii\web\NotFoundHttpException;

use common\models\db\User;
use common\models\db\UserSubscription;

/**
 * Class UserForm
 * @package backend\models\form
 */
class UserForm extends Model
{
    /** @var string */
    public $username;

    /** @var string */
    public $family;

    /** @var string */
    public $name;

    /** @var string */
    public $patronymic;

    /** @var string */
    public $password;

    /** @var string */
    public $email;

    /** @var string */
    public $subscription_date;

    /** @var User */
    protected $user;

    /**
     * @param int $id
     *
     * @return UserForm
     * @throws NotFoundHttpException
     */
    public static function findOne(int $id)
    {
        if (($user = User::findOneWithSubscriptionById($id)) !== null) {
            $model = new static;
            $model->attributes = $user->attributes;

            if ($user->subscription) {
                $model->subscription_date = $user->subscription->dateEndText;
            }

            $model->user = $user;

            return $model;
        }

        throw new NotFoundHttpException('The requested user does not exist.');
    }

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            [['login', 'email', 'family', 'name', 'patronymic', 'subscription_date', 'password'], 'trim'],
            [['login', 'email'], 'required'],

            [
                'family', 'required',
                'when'       => function ($model) {
                    return $model->name || $model->patronymic;
                },
                'whenClient' => "function (attribute, value) {
                    return $('#userform-name').val().length || $('#userform-patronymic').val().length;
                }",
            ],

            [
                'name', 'required',
                'when'       => function ($model) {
                    return $model->family || $model->patronymic;
                },
                'whenClient' => "function (attribute, value) {
                    return $('#userform-family').val().length || $('#userform-patronymic').val().length;
                }",
            ],

            [
                'patronymic', 'required',
                'when'       => function ($model) {
                    return $model->name || $model->family;
                },
                'whenClient' => "function (attribute, value) {
                    return $('#userform-name').val().length || $('#userform-family').val().length;
                }",
            ],

            ['email', 'email'],

            [['login', 'email', 'family', 'name', 'patronymic'], 'string', 'max' => 255],

            ['subscription_date', 'default', 'value' => null],
            ['subscription_date', 'date', 'format' => 'php:d-m-Y'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'login'          => 'Логин',
            'email'             => 'Email',
            'family'            => 'Фамилия',
            'name'              => 'Имя',
            'patronymic'        => 'Отчество',
            'password'          => 'Пароль',
            'subscription_date' => 'Дата окончания подписки',
        ];
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        if ($this->validate()) {
            $user = $this->user;
            $userSubscription = $user->subscription;

            $transaction = Yii::$app->db->beginTransaction();

            try {
                $user->attributes = $this->attributes;

                if ($this->password) {
                    $user->setPassword($this->password);
                }

                $user->touch('updated_at');
                $user->update();

                if ($userSubscription && !$this->subscription_date) {
                    $userSubscription->delete();
                } elseif ($userSubscription && $this->subscription_date) {
                    $userSubscription->dateEndText = $this->subscription_date;

                    $userSubscription->update();
                } elseif (!$userSubscription && $this->subscription_date) {
                    $subscription = new UserSubscription;
                    $subscription->user_id = $user->id;
                    $subscription->dateEndText = $this->subscription_date;

                    $subscription->insert();
                }

                $transaction->commit();

                return true;
            }
            catch (Exception $e) {
                Yii::error($e->getMessage(), __METHOD__);
                $transaction->rollBack();
            }
        }

        return false;
    }
}