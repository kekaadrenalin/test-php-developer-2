<?php

namespace backend\models\search;

use DateTime;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\db\User;

/**
 * UserSearch represents the model behind the search form of `common\models\db\User`.
 */
class UserSearch extends User
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['username', 'email', 'fio', 'subscription_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find()
            ->simpleUsersWithSubscriptionDate()
            ->addSelect(['email' => 'user.email']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['fio'] = [
            'asc'  => ['fio' => SORT_ASC],
            'desc' => ['fio' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['subscription_date'] = [
            'asc'  => ['subscription_date' => SORT_ASC],
            'desc' => ['subscription_date' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['user.id' => $this->id]);

        $query->andFilterWhere(['like', 'user.username', $this->username])
            ->andFilterWhere(['like', 'user.email', $this->email])
            ->andFilterWhere([
                'or',
                ['like', 'user.name', $this->fio],
                ['like', 'user.family', $this->fio],
                ['like', 'user.patronymic', $this->fio],
            ]);

        if ($this->subscription_date && $date = $this->dateToInteger($this->subscription_date)) {
            $query->andFilterWhere(['user_subscription.date_end' => $date]);
        }

        return $dataProvider;
    }

    /**
     * @param string $value
     *
     * @return bool|string
     */
    public function dateToInteger(string $value)
    {
        if ($value && $date = DateTime::createFromFormat('d-m-Y', $value)) {
            return $date->setTime(23, 59, 59)->format('U');
        }

        return false;
    }
}