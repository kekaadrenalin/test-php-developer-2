<?php

namespace backend\models\form;

use common\models\db\User;
use common\models\db\UserSubscription;
use yii\base\Model;
use yii\web\NotFoundHttpException;

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
    public $subscription;

    /** @var integer */
    protected $id;

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
                $model->subscription = date('d-m-Y', $user->subscription->date_end);
            }

            $model->id = $user->id;

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
            [['username', 'email', 'family', 'name', 'patronymic', 'subscription'], 'trim'],
            [['email'], 'required'],
            ['email', 'email'],

            ['subscription', 'default', 'value' => null],
            ['subscription', 'date', 'format' => 'php:d-m-Y'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username'     => 'Логин',
            'email'        => 'Email',
            'family'       => 'Фамилия',
            'name'         => 'Имя',
            'patronymic'   => 'Отчество',
            'password'     => 'Пароль',
            'subscription' => 'Дата окончания подписки',
        ];
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        if ($this->validate()) {
            $userSubscription = UserSubscription::findByUserId($this->id);

            if ($userSubscription && !$this->subscription) {
                return $userSubscription->delete();
            } elseif ($userSubscription && $this->subscription) {
                $userSubscription->date_end = $this->subscription;

                return $userSubscription->update();
            } elseif (!$userSubscription && $this->subscription) {
                $subscription = new UserSubscription;
                $subscription->user_id = $this->id;
                $subscription->date_end = $this->subscription;

                return $subscription->insert();
            }

            return true;
        }

        return false;
    }
}