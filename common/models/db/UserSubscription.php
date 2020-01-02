<?php

namespace common\models\db;

use DateTime;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_subscription".
 *
 * @property int  $id
 * @property int  $user_id
 * @property int  $date_end
 *
 * @property User $user
 */
class UserSubscription extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_subscription';
    }

    /**
     * @param int $userId
     *
     * @return static|null
     */
    public static function findByUserId(int $userId)
    {
        return static::find()
            ->where(['user_id' => $userId])
            ->one();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'date_end'], 'required'],
            [['user_id', 'date_end'], 'integer'],
            [['user_id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'       => 'ID',
            'user_id'  => 'User ID',
            'date_end' => 'Date End',
        ];
    }

    public function beforeValidate()
    {
        if ($this->date_end) {
            $date = DateTime::createFromFormat('d-m-Y', $this->date_end);
            $this->date_end = $date->setTime(23, 59, 59)->format('U');
        }

        return parent::beforeValidate();
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
