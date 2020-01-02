<?php

namespace console\controllers;

use common\models\db\UserSubscription;
use Faker\Factory;
use common\models\User;
use yii\console\Controller;

class UserController extends Controller
{
    /**
     * Generating fake users
     */
    public function actionInit()
    {
        $max = mt_rand(3, 10);

        echo "Start generating fake users...\n";

        for ($i = 0; $i < $max; $i++) {
            $faker = Factory::create('ru_RU');
            $user = new User;

            $gender = rand(0, 1) ? 'male' : 'female';

            $user->username = $faker->userName;
            $user->email = $faker->email;
            $user->family = $faker->lastName($gender);
            $user->name = $faker->firstName($gender);
            $user->patronymic = $faker->middleName($gender);

            $user->setPassword($faker->password);
            $user->generateAuthKey();

            $user->role = 0;
            $user->status = User::STATUS_ACTIVE;

            if ($user->insert()) {
                $subscription = new UserSubscription;
                $subscription->user_id = $user->id;
                $subscription->date_end = $faker->dateTimeBetween('now', '+1000 days')->format('d-m-Y');

                $subscription->insert();

                echo " + user #{$user->id}\n";
            }
        }

        echo "Done!\n";
    }
}