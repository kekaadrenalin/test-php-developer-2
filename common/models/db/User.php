<?php

namespace common\models\db;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\caching\DbDependency;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use common\models\db\query\UserQuery;

/**
 * This is the model class for table "user".
 *
 * @property int              $id
 * @property string           $login
 * @property string           $auth_key
 * @property string           $password_hash
 * @property string|null      $password_reset_token
 * @property string           $email
 * @property string|null      $family Фамилия
 * @property string|null      $name Имя
 * @property string|null      $patronymic Отчество
 * @property int              $role Роль
 * @property int              $status
 * @property int              $created_at
 * @property int              $updated_at
 * @property string|null      $verification_token
 *
 * @property UserSubscription $subscription
 */
class User extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    const ROLE_USER = 0;
    const ROLE_ADMIN = 10;


    /** @var string */
    public $fio;

    /** @var string */
    public $subscription_date;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * Finds user by login
     *
     * @param string $login
     *
     * @return static|null
     */
    public static function findByLogin($login)
    {
        return static::findOne(['login' => $login, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user (with admin role) by login
     *
     * @param string $login
     *
     * @return static|null
     */
    public static function findByUsernameForAdmin($login)
    {
        return static::findOne([
            'login' => $login,
            'status'   => self::STATUS_ACTIVE,
            'role'     => self::ROLE_ADMIN,
        ]);
    }

    /**
     * Finds user by ID with Subscription
     *
     * @param $id
     *
     * @return static|null
     */
    public static function findOneWithSubscriptionById($id)
    {
        return static::find()
            ->where(['id' => $id])
            ->with('subscription')
            ->simpleUser()
            ->one();
    }

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * Finds user by ID with Subscription
     *
     * @param int $id
     *
     * @return static|null
     */
    public static function findOneWithSubscriptionDateById(int $id)
    {
        $dependency = new DbDependency(['sql' => 'SELECT updated_at FROM user where id=' . $id]);

        return static::getDb()->cache(function () use ($id) {
            return static::find()
                ->simpleUsersWithSubscriptionDate()
                ->andWhere(['user.id' => $id])
                ->one();
        }, 3600, $dependency);
    }

    /**
     * @return array|User[]
     */
    public static function findAllSimpleUsersWithSubscriptionDate()
    {
        $dependency = new DbDependency(['sql' => 'SELECT MAX(updated_at) FROM user']);

        return static::getDb()->cache(function () {
            return static::find()
                ->simpleUsersWithSubscriptionDate()
                ->all();
        }, 3600, $dependency);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     *
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status'               => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     *
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     *
     * @return static|null
     */
    public static function findByVerificationToken($token)
    {
        return static::findOne([
            'verification_token' => $token,
            'status'             => self::STATUS_INACTIVE,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'auth_key', 'password_hash', 'email'], 'required'],
            [['role'], 'integer'],
            [['login', 'password_hash', 'password_reset_token', 'email', 'family', 'name', 'patronymic', 'verification_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['login'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                   => 'ID',
            'login'             => 'Логин',
            'auth_key'             => 'Auth Key',
            'password_hash'        => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email'                => 'Email',
            'family'               => 'Фамилия',
            'name'                 => 'Имя',
            'patronymic'           => 'Отчество',
            'role'                 => 'Роль',
            'status'               => 'Статус',
            'created_at'           => 'Created At',
            'updated_at'           => 'Updated At',
            'verification_token'   => 'Verification Token',

            'fio'               => 'ФИО',
            'subscription_date' => 'Подписка',
        ];
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     *
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @return ActiveQuery
     */
    public function getSubscription()
    {
        return $this->hasOne(UserSubscription::class, ['user_id' => 'id']);
    }
}
