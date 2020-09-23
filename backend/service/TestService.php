<?php
namespace backend\service;
use common\models\User;
class TestService
{
    public static function foo(){
       echo 'foo';die;
    }

    public static function getUserInfo(){
        return User::findByUsername('zs');
    }
}