<?php

namespace Service\ServiceBundle\Entity\Repository;

use Service\ServiceBundle\Resources\ServiceException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection;

class RequestParser{

    public static function checkApiKey(Request $request, $currentKey){
        $apiKey = $request->query->get('api_key');
        if($apiKey != $currentKey){
            $errors[] = 'Api key does not match';
            return $errors;
        }else return false;
    }

    public static function parseRequest(Request $request){
		
		return $request->query->all();
		
//		$res = str_replace("'", "\"", $request->request->get('data'));
//		
//		$res = json_decode($res);
//
//		if($res == false) throw new ServiceException('JSON not readable!', 300);
//		
//		if(!empty($res)){
//			if(count($res) == 1)
//				return (array)$res[0];
//			else{
//				foreach($res as $key => $element)
//					$res[$key] = (array)$res[$key];
//				return $res;
//			}
//		}
//		return array();



        //$data = (array)$res[0];
        //$data = $res;

        //return $data;
    }

    public static function requestValidate(array $data){
        $errors = array();
		if(!empty($data)){
			for($i = 1; $i < func_num_args(); $i++){
				$arg = func_get_arg($i);
				if(strpos($arg, 'id') !== false){
					if(!isset($data[$arg]) || !is_integer($data[$arg]) || empty($data[$arg]))
						$errors[] = 'Invalid id value in '.$arg.' parameter';
				}
				if($arg == 'sort'){
					if(!is_integer($data[$arg]))
						$errors[] = 'Sort parameter must be integer';
				}
				if($arg == 'time'){
					if(!RequestParser::isValidTimeStamp($data[$arg]))
						$errors[] = 'Invalid time, must be in UNIX TIMESTAMP format';
				}
				if($arg == 'amount'){
					if(!is_integer($data[$arg]))
						$errors[] = 'Amount parameter must be integer';
				}
			}
        }

        return $errors;
    }

    public static function isValidTimeStamp($timestamp)
    {
        return true;
        /*return ((string) (int) $timestamp === $timestamp)
        && ($timestamp <= PHP_INT_MAX)
        && ($timestamp >= ~PHP_INT_MAX);*/
    }


}