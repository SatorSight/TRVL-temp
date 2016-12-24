<?php
namespace Service\ServiceBundle\Resources;

class SUtils{
    public static function dump($data){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
    public static function trace($data, $trail = '---'){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die($trail);
    }
}