<?php

namespace Service\ServiceBundle\Entity\Repository;

use Service\ServiceBundle\Entity\Repository\OutputHandler;

class Messager{

    public static function getSuccessResultMessage(){
		return OutputHandler::preRenderOutput(null);
        //return array('result' => 'OK');
    }

    public static function getErrorResultMessage(){
		return OutputHandler::preRenderOutput(null, 'ERROR');
        //return array('result' => 'ERROR');
    }



}