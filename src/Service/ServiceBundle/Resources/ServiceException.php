<?php

namespace Service\ServiceBundle\Resources;

use Service\ServiceBundle\Entity\Repository\OutputHandler;

class ServiceException extends \Exception{

    public function __construct($message, $code = 0){
		
        parent::__construct($message, $code);
		echo $this;
		die();
    }

    public function __toString() {
		
        $file = $_SERVER['DOCUMENT_ROOT'].'/service/log.txt';
        $current = file_get_contents($file);
        $current .= date('d.m.Y H.i.s')."::ERROR::: ".$this->getMessage().". code: ".$this->getCode()."\n";
        file_put_contents($file, $current);
		
		return json_encode(array(OutputHandler::preRenderOutput(null, 'ERROR', $this->getMessage(), $this->getCode())));

        /*$err = array();
        $err['result'] = 'ERROR';
        $err['message'] = $this->getMessage();
        $err['code'] = $this->getCode();
        return json_encode(array($err));*/
		
    }


}