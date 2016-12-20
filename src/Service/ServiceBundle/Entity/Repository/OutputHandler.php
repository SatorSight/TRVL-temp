<?php

namespace Service\ServiceBundle\Entity\Repository;

use Service\ServiceBundle\Entity\Archive;
use Service\ServiceBundle\Entity\Group;
use Service\ServiceBundle\Entity\Order;
use Service\ServiceBundle\Entity\OrderContent;
use Service\ServiceBundle\Entity\Place;
use Service\ServiceBundle\Entity\Position;
use Service\ServiceBundle\Entity\Sector;
use Service\ServiceBundle\Entity\WorkDay as WD;

class OutputHandler
{
    public static function handle($data = null, $objectArr, $inputArr = null, $modifier = '', $res = 'OK'){

        return self::preRenderOutput($objectArr, $res);
        
        $result = array();
        if(!is_array($objectArr)){
            $tmpArr = array($objectArr);
            $objectArr = $tmpArr;
        }
        foreach($objectArr as $object){
            if($object instanceof Group)
                $result[] = OutputHandler::groupHandle($object);
            if($object instanceof Order)
                $result[] = OutputHandler::orderHandle($object, $modifier);
            if($object instanceof OrderContent)
                $result[] = OutputHandler::orderContentHandle($object);
            if($object instanceof Place)
                $result[] = OutputHandler::placeHandle($object);
            if($object instanceof Position)
                $result[] = OutputHandler::positionHandle($object, $modifier);
            if($object instanceof Sector)
                $result[] = OutputHandler::sectorHandle($object);
            if($object instanceof WD)
                $result[] = OutputHandler::workdayHandle($object);
            if($object instanceof Archive)
                $result[] = OutputHandler::archiveHandle($object);
        }

        if(!empty($data)){
            $file = $_SERVER['DOCUMENT_ROOT'].'/dev/service/log.txt';
            $current = file_get_contents($file);
            $current .= date('d.m.Y H.i.s')."::SUCCESS::input data: ".json_encode($data)."\n";
            file_put_contents($file, $current);
        }
		
		return self::preRenderOutput($result, $res);
		
        //$result = array('result' => 'OK', 'data' => $result);

        //return $result;
    }

    private static function groupHandle(Group $obj){
        return array(
            'id' => $obj->getId(),
            'name' => $obj->getName(),
            'present' => $obj->getPresent(),
            'url' => $obj->getUrl(),
            'image' => $obj->getImage(),
            'sort' => $obj->getSort(),
            'id_place' => $obj->getPlace()->getId()
        );
    }

    private static function orderHandle(Order $obj, $modifier){
       $return = array(
            'id' => $obj->getId(),
            'datetime' => $obj->getDate()->getTimestamp(),
            'place' => $obj->getPlace(),
            'payment_id' => $obj->getPaymentId(),
            'id_workday' => $obj->getWorkday()->getId()
        );
		//if($modifier == 'workday') $return['id_workday'] = $obj->getWorkday()->getId();
		return $return;
    }

    private static function orderContentHandle(OrderContent $obj){
        return array(
            'id_order' => $obj->getOrder()->getId(),
            'id_position' => $obj->getPosition()->getId(),
            'amount' => $obj->getAmount()
        );
    }

    private static function placeHandle(Place $obj){
        return array(
            'id' => $obj->getId(),
            'name' => $obj->getName(),
            'sort' => $obj->getSort(),
            'image' => $obj->getImage(),
            'url' => $obj->getUrl()
        );
    }

    private static function positionHandle(Position $obj, $modifier){
		if($modifier == 'cut'){
			$return = array(
				'id' => $obj->getId(),
				'name' => $obj->getName(),
				'present' => $obj->getPresent(),
			);
			if(!empty($obj->getOrderPrice()))
				$return['order_price'] = $obj->getOrderPrice();
			if(!empty($obj->getOrderSum()))
				$return['order_sum'] = $obj->getOrderSum();
			return $return;
		}else{
		
			$return = array(
				'id' => $obj->getId(),
				'name' => $obj->getName(),
				'present' => $obj->getPresent(),
				'description' => $obj->getDescription(),
				'price' => $obj->getPrice(),
				'url' => $obj->getUrl(),
				'image' => $obj->getImage(),
				'id_group' => $obj->getGroup()->getId()
			);
			if(!empty($obj->getOrderPrice()))
				$return['order_price'] = $obj->getOrderPrice();
			if(!empty($obj->getOrderSum()))
				$return['order_sum'] = $obj->getOrderSum();
		
		}
        return $return;
    }

    private static function sectorHandle(Sector $obj){
        return array(
            'id' => $obj->getId(),
            'name' => $obj->getName(),
            'id_place' => $obj->getPlace()->getId()
        );
    }

    public static function workdayHandle(WD $obj){
        return array(
            'id' => $obj->getId(),
            'opened' => $obj->getOpened(),
            'closed' => $obj->getClosed(),
            'locked' => $obj->getLocked()
        );
    }

    public static function archiveHandle(Archive $obj){
        return array(
            'id' => $obj->getId(),
            'id_order' => $obj->getIdOrder(),
            'date' => $obj->getDate()->getTimestamp(),
            'place' => $obj->getPlace(),
			'payment_id' => $obj->getPaymentId(),
            'id_position' => $obj->getIdPosition(),
            'id_workday' => $obj->getIdWorkday(),
            'sum' => $obj->getSum(),
            'amount' => $obj->getAmount()
        );
    }
	
	public static function preRenderOutput($data, $result = 'OK', $message = null, $code = null){
		if(empty($data)) $data = array();
		return array(
			'result' => $result,
			'message' => $message,
			'code' => $code,
			'data' => $data
		);
	}

}
