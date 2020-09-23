<?php
namespace common\help;

/*
 * 自定义全局公共方法
 */
class tools{
    public static function uuid(){
        $chars = md5(uniqid(mt_rand(), true));
        $uuid = substr ( $chars, 0, 8 ) . '-'
            . substr ( $chars, 8, 4 ) . '-'
            . substr ( $chars, 12, 4 ) . '-'
            . substr ( $chars, 16, 4 ) . '-'
            . substr ( $chars, 20, 12 );
        echo  $uuid ;die;
    }
}
