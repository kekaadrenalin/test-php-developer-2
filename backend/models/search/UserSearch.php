<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\db\User;

/**
 * UserSearch represents the model behind the search form of `common\models\db\User`.
 */
class UserSearch extends User
{
    /** @var string */
    public $fio;

    public $subscription;

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return array_merge(parent::attributes(), ['fio']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['username', 'email', 'fio', 'subscription'], 'safe'],
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
        $query = User::find();

        $query->joinWith(['subscription']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['fio'] = [
            'asc'  => ['family' => SORT_ASC, 'name' => SORT_ASC, 'patronymic' => SORT_ASC],
            'desc' => ['family' => SORT_DESC, 'name' => SORT_DESC, 'patronymic' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['subscription'] = [
            'asc'  => ['tbl_subscription.date_end' => SORT_ASC],
            'desc' => ['tbl_subscription.date_end' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id'     => $this->id,
            'role'   => $this->role,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'fio', $this->fio]);

        return $dataProvider;
    }
}