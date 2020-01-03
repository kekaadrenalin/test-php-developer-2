<?php

namespace common\models\db\query;

use common\models\db\User;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\common\models\db\User]].
 *
 * @see \common\models\db\User
 */
class UserQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return User[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return User|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @return UserQuery
     */
    public function active()
    {
        return $this->andWhere(['status' => User::STATUS_ACTIVE]);
    }

    /**
     * @return UserQuery
     */
    public function simpleUsersWithSubscriptionDate()
    {
        return $this
            ->selectForSimpleUsers()
            ->withSubscriptionDateEnd()
            ->simpleUser();
    }

    /**
     * @return UserQuery
     */
    public function simpleUser()
    {
        return $this->andWhere(['role' => User::ROLE_USER]);
    }

    /**
     * @return UserQuery
     */
    public function withSubscriptionDateEnd()
    {
        return $this->addSelect(['subscription_date' => 'user_subscription.date_end'])
            ->joinWith('subscription', false);
    }

    /**
     * @return UserQuery
     */
    public function selectForSimpleUsers()
    {
        return $this->select([
            'id'       => 'user.id',
            'username' => 'user.username',
            'fio'      => "CONCAT(user.family, ' ', user.name, ' ', user.patronymic)",
        ]);
    }
}