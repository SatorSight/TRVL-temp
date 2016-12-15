<?php

namespace Service\ServiceBundle\Controller;

use Service\ServiceBundle\Entity\Repository\Messager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Service\ServiceBundle\Entity\AirportTest;
use Service\ServiceBundle\Entity\Blacklist;
use Service\ServiceBundle\Entity\Media;
use Service\ServiceBundle\Entity\Message;
use Service\ServiceBundle\Entity\Moder;
use Service\ServiceBundle\Entity\Profile;
use Service\ServiceBundle\Entity\Station;
use Service\ServiceBundle\Entity\Travel;
use Service\ServiceBundle\Entity\TravelType;
use Service\ServiceBundle\Entity\User;

use Service\ServiceBundle\Entity\Repository\RequestParser;
use Service\ServiceBundle\Entity\Repository\OutputHandler;

use Service\ServiceBundle\Resources\ServiceException;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Service controller.
 */
class ServiceController extends Controller
{
	
	/*public function testAction(Request $request){
		$data = RequestParser::parseRequest($request);
		
		echo '<pre>';
		print_r($data['image']);
		echo '</pre>';
		$t = base64_decode($data['image']);
		//echo '<pre>';
		//print_r($t);
		//echo '</pre>';
		$hexstr = unpack('H*', $t);

		echo '<br>'.$hexstr[1];
		
		$times = time();
		
		$imgUrl = $this->saveImage($data['image'], 1, '/dev/service/project/web/images/', $times);
		//$imgUrl = $this->saveImage($data['image'], 1, '/dev/service/images/');
		
		echo '<img src="/dev/service/project/web/images/'.$times.'.jpg"/>';
		echo '<br>';
		
		return $this->render('ServiceServiceBundle:Service:index.html.php', array(
			'result' => OutputHandler::handle(null, realpath($imgUrl))
		));
	}*/


    public function indexAction(Request $request){

//        $request = $request->query->get();
        $requestData = RequestParser::parseRequest($request);
        $errors = RequestParser::checkApiKey($request, $this->container->getParameter('service_service.api_key'));

        $return = [];

        if(!empty($requestData['action']) && !$errors) {
//            echo '<pre>';
//            print_r($requestData);
//            echo '</pre>';die('---');
            switch ($requestData['action']){

                case 'auth':
                    $return[] = $this->auth($requestData['id'], $requestData['token']);
                    break;

                case 'save_profile':
                    $return[] = $this->saveProfile($requestData);
                    break;
                
                case 'registration':
                    $return[] = $this->registration($requestData['id'], $requestData['token']);
                    break;

                case 'save_soc_token':
                    $return[] = $this->save_soc_token($requestData['soc_token'], $requestData['type'], $requestData);
                    break;

                case 'test':
                    $return[] = ['test' => 'test', 'id' => $requestData['id']];
                    break;


                case 'show_users_city':
                    $return[] = $this->show_users_city();
                    break;

                case 'user_list':
                    $return[] = $this->user_list();
                    break;
                case 'user_list_id':
                    $return[] = $this->user_list_id($requestData['id']);
                    break;
                case 'user_show__token':
                    $return[] = $this->user_show_token($requestData['token'],$requestData['login'],$requestData['password'],
                        $requestData['soc_token'],$requestData['type_auth']);
                    break;


                case 'user_del':
                    $return[] = $this->user_del($requestData['id']);
                    break;

                case 'user_ban':
                    $return[] = $this->user_ban($requestData['id'],$requestData['ban']);
                    break;

                case 'user_edit':
                    $return[] = $this->user_edit($requestData);
                    break;



                case 'upload_media':
                    $return[] = $this->upload_media($_FILES['userfile'],$requestData['type']);
                    break;



                case 'moder_add':
                    $return[] = $this->moder_add($requestData['login'],$requestData['password'],$requestData['admin']);
                    break;

                case 'moder_edit':
                    $return[] = $this->moder_edit($requestData['id'],$requestData['login'],$requestData['password'],$requestData['admin']);
                    break;

                case 'moder_del':
                    $return[] = $this->moder_del($requestData['id']);
                    break;

                case 'moder_list':
                    $return[] = $this->moder_list();
                    break;


                case 'push_feedback':
                    $return[] = $this->push_feedback();
                    break;

                case 'send_push_to':
                    $return[] = $this->send_push_to($requestData['iToken'],$requestData['text'],$requestData['info']);
                    break;


                case 'phpinfo':
                    echo phpinfo();
                    break;

            }
        }elseif($errors)
            echo json_encode($errors);

        return $this->render('ServiceServiceBundle:Service:index.html.php', array(
            'result' => OutputHandler::handle(null, $return)
        ));

//        $em = $this->getDoctrine()->getManager();
//        $airports = $em->getRepository('ServiceServiceBundle:AirportTest')->findAll();

    }

    public function saveProfile($requestData){

//        echo '<pre>';
//        print_r($requestData);
//        echo '</pre>';
//        die('0');

        $data = json_decode($requestData['data']);


        $em = $this->getDoctrine()->getManager();

        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['appId' => (int)$requestData['id']]);

//        echo '<pre>';
//        print_r($user);
//        echo '</pre>';
//        die('0');

        /** @var Profile $userProfile */
        $userProfile = $em->getRepository('ServiceServiceBundle:Profile')->findBy(['userId' => $user->getId()]);


        $created = false;
        if(!$userProfile) {
            $userProfile = new Profile();
            $userProfile->setUserId($user->getId());
            $created = true;
        }

        $this->sanitizeUserProfileData($data);

        $userProfile->setLastVisit(new \DateTime());
        $userProfile->setName($data['name']);
        $userProfile->setAge($data['age']);
        $userProfile->setSex($data['sex']);
        $userProfile->setCity($data['city']);
        $userProfile->setAppearance($data['appearance']);
        $userProfile->setAbout($data['aboutMe']);
        $userProfile->setWannaCommunicate($data['wannaCommunicate']);
        $userProfile->setFindCompanion($data['findCompanion']);
        $userProfile->setFindCouple($data['findCouple']);
        $userProfile->setFindFriends($data['findFriends']);
        $userProfile->setFree($data['free']);
        $userProfile->setOrientation($data['orientation']);

        if($created)
            $em->persist($userProfile);
        $em->flush();

        return $userProfile;
    }


    public function loadProfile($requestData){
        $em = $this->getDoctrine()->getManager();
        /** @var Profile $userProfile */
        $userProfile = $em->getRepository('ServiceServiceBundle:User')->findBy(['user_id' => (int)$requestData['id']]);
        return $userProfile;
    }
    
    public function auth($id, $token){



        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('ServiceServiceBundle:User')->findBy(['login' => (int)$id]);

//        echo '<pre>';
//        print_r($user);
//        echo '</pre>';die('-');

        if(!$user)
            return $this->registration($id, $token);
        else
            return $user;
    }

    public function registration($id, $token){
        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $user->setActive(true);
        $user->setLogin($id);
        $user->setIosToken($token);
        $user->setInserted(new \DateTime());
        $em->persist($user);
        $em->flush();

        return $user;

        //todo В старом апи какая-то ерунда с паролями и смсками, нужно переделать через социалки
    }

    public function save_soc_token(){
        //todo Я вообще не понял что там происходит, что-то с токенами
    }

    public function user_list(){
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('ServiceServiceBundle:User')->findBy(['active' => '1']);
        return $users;
    }

    public function user_list_id($id){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('ServiceServiceBundle:User')->findBy(['id' => (int)$id, 'active' => '1']);
        return $user;
    }

    public function user_show_token(){
        //todo Пока пропускаю все с токенами, в старом апи видимо все инче было, чем будет
    }

    public function user_del($id){
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findBy(['id' => (int)$id, 'active' => '1']);
        $user->setActive(true);
        $em->flush();
        return $user;
    }

    public function user_ban($id, $ban){
        //todo в таблице почему-то нет поля ban
//        $em = $this->getDoctrine()->getManager();
//        /** @var User $user */
//        $user = $em->getRepository('ServiceServiceBundle:User')->findBy(['id' => (int)$id, 'active' => '1']);
//        $user->setActive(true);
//        $em->flush();
//        return $user;
    }

    public function user_edit($fields){
        $em = $this->getDoctrine()->getManager();
        /** @var Profile $user */
        $user = $em->getRepository('ServiceServiceBundle:Profile')->findBy(['id' => (int)$fields['id'], 'active' => '1']);
        if(!empty($fields['name']))
            $user->setName($fields['name']);
        if(!empty($fields['family']))
            $user->setName($fields['family']);
        if(!empty($fields['email']))
            $user->setName($fields['email']);
        if(!empty($fields['vk']))
            $user->setName($fields['vk']);
        if(!empty($fields['vk_token']))
            $user->setName($fields['vk_token']);
        if(!empty($fields['fb']))
            $user->setName($fields['fb_token']);
        if(!empty($fields['city_id']))
            $user->setName($fields['city_id']);
        if(!empty($fields['last_visit']))
            $user->setName($fields['last_visit']);
        if(!empty($fields['about']))
            $user->setName($fields['about']);
        if(!empty($fields['bd']))
            $user->setName($fields['bd']);
        if(!empty($fields['gender']))
            $user->setName($fields['gender']);
        if(!empty($fields['i_want']))
            $user->setName($fields['i_want']);
        if(!empty($fields['relations']))
            $user->setName($fields['relations']);
        if(!empty($fields['orientation']))
            $user->setName($fields['orientation']);
        if(!empty($fields['appearance']))
            $user->setName($fields['appearance']);

        $em->flush();
    }

    public function upload_media(){
        //todo later
    }

    public function moder_list(){
        $em = $this->getDoctrine()->getManager();
        /** @var Moder $moders */
        $moders = $em->getRepository('ServiceServiceBundle:Moder')->findAll();
        return $moders;
    }

    public function moder_add($login, $password, $admin){
        $moder = new Moder();
        $moder->setLogin($login);
        $moder->setPassword($password);
        $moder->setAdmin($admin);

        $em = $this->getDoctrine()->getManager();
        $em->persist($moder);
        $em->flush();

        return $moder;
    }

    public function moder_edit($id ,$login, $password, $admin){

        $em = $this->getDoctrine()->getManager();
        /** @var Moder $moder */
        $moder = $em->getRepository('ServiceServiceBundle:Moder')->findBy(['id' => $id]);

        if(!empty($login))
            $moder->setLogin($login);
        if(!empty($password))
            $moder->setPassword($password);
        if(!empty($admin))
            $moder->setAdmin($admin);

        $em->persist($moder);
        $em->flush();

        return $moder;

    }

    public function moder_del($id){

        $em = $this->getDoctrine()->getManager();
        /** @var Moder $moder */
        $moder = $em->getRepository('ServiceServiceBundle:Moder')->findBy(['id' => $id]);

        $moder->setActive(false);
        $em->flush();

        return $moder;
    }

    public function push_feedback(){
        //todo this
    }

    public function send_push_to(){
        //todo and this
    }















































    //todo GET LIST ACTIONS ///////////////////

    public function getGroupsAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            //$groups = $this->getDoctrine()->getManager()->getRepository('ServiceServiceBundle:Group')->findAll();
            $groups = $this->getDoctrine()->getManager()->getRepository('ServiceServiceBundle:Group')->getGroupsSorted();
			return $this->render('ServiceServiceBundle:Service:index.html.php', array(
                'result' => OutputHandler::handle(null, $groups)
            ));
        }catch (\Exception $e){
            throw new ServiceException('Method: /get_groups/', 1);
        }
    }
    public function getPlacesAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            //$places = $this->getDoctrine()->getManager()->getRepository('ServiceServiceBundle:Place')->findAll();
			$places = $this->getDoctrine()->getManager()->getRepository('ServiceServiceBundle:Place')->getPlacesSorted();
            return $this->render('ServiceServiceBundle:Service:index.html.php', array(
                'result' => OutputHandler::handle(null, $places)
            ));
        }catch (\Exception $e){
            throw new ServiceException('Method: /get_places/', 2);
        }
    }
    public function getSectorsAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $sectors = $this->getDoctrine()->getManager()->getRepository('ServiceServiceBundle:Sector')->findAll();
            return $this->render('ServiceServiceBundle:Service:index.html.php', array(
                'result' => OutputHandler::handle(null, $sectors)
            ));
        }catch (\Exception $e){
            throw new ServiceException('Method: /get_sectors/', 3);
        }
    }
    public function getPositionsAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            //$positions = $this->getDoctrine()->getManager()->getRepository('ServiceServiceBundle:Position')->findAll();
            $positions = $this->getDoctrine()->getManager()->getRepository('ServiceServiceBundle:Position')->getPositionsSorted();
			
            return $this->render('ServiceServiceBundle:Service:index.html.php', array(
                'result' => OutputHandler::handle(null, $positions) 
            ));
        }catch (\Exception $e){
            throw new ServiceException('Method: /get_positions/', 4);
        }
    }
    public function getOrdersAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $orders = $this->getDoctrine()->getManager()->getRepository('ServiceServiceBundle:Order')->findAll();
            return $this->render('ServiceServiceBundle:Service:index.html.php', array(
                'result' => OutputHandler::handle(null, $orders, null, 'workday')
            ));
        }catch (\Exception $e){
            throw new ServiceException('Method: /get_orders/', 5);
        }
    }
    public function getWorkDaysAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $workdays = $this->getDoctrine()->getManager()->getRepository('ServiceServiceBundle:WorkDay')->findAll();
            return $this->render('ServiceServiceBundle:Service:index.html.php', array(
                'result' => OutputHandler::handle(null, $workdays)
            ));
        }catch (\Exception $e){
            throw new ServiceException('Method: /get_workdays/', 6);
        }
    }


    //todo GET LIST ACTIONS END ///////////////////


    //todo ADD--UPDATE ACTIONS ///////////////////

    public function addPlaceAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $data = RequestParser::parseRequest($request);


            $errors = RequestParser::requestValidate($data, 'id');
            if(!empty($errors)) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $em = $this->getDoctrine()->getManager();
            /** @var Place $place */
            $place = $em->getRepository('ServiceServiceBundle:Place')->find($data['id']);
            if(!$place) {

                $place = new Place();
                $place->setId($data['id']);
                $place->setName($data['name']);
				if(!empty($data['sort']))
					$place->setSort($data['sort']);
				if(!empty($data['image']))
					$place->setImage($this->saveImage($data['image'], $data['id']));
				if(!empty($data['url']))
					$place->setUrl($data['url']);
                $em->persist($place);

            }else{
                if(!empty($data['name']))
                    $place->setName($data['name']);
                if(!empty($data['sort']))
                    $place->setSort($data['sort']);
				if(!empty($data['image']))
					$place->setImage($this->saveImage($data['image'], $data['id']));
				if(!empty($data['url']))
					$place->setUrl($data['url']);
            }

            $em->flush();

            if(!empty($data)) return $this->successMessage($data); else return $this->successMessage();
        }catch (\Exception $e){
            throw new ServiceException('Method: /add_place/', 7);
        }
    }
    public function addSectorAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $data = RequestParser::parseRequest($request);
            $errors = RequestParser::requestValidate($data, 'id', 'id_place');
            if(!empty($errors)) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);
            $em = $this->getDoctrine()->getManager();

            /** @var Sector $sector */
            $sector = $em->getRepository('ServiceServiceBundle:Sector')->find($data['id']);
            if(!$sector) {
                $sector = new Sector();
                $sector->setId($data['id']);
                $sector->setName($data['name']);
                $sector->setPlace($em->getRepository('ServiceServiceBundle:Place')->find($data['id_place']));

                $em->persist($sector);
            }else{
                if(!empty($data['name']))
                    $sector->setName($data['name']);
                if(!empty($data['id_place']))
                    $sector->setPlace($em->getRepository('ServiceServiceBundle:Place')->find($data['id_place']));
            }
            $em->flush();

            if(!empty($data)) return $this->successMessage($data); else return $this->successMessage();
        }catch (\Exception $e){
            throw new ServiceException('Method: /add_sector/', 8);
        }
    }
    public function addGroupAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $data = RequestParser::parseRequest($request);
            $errors = RequestParser::requestValidate($data, 'id');
            if(!empty($errors)) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);
            $em = $this->getDoctrine()->getManager();

            /** @var Group $group */
            $group = $em->getRepository('ServiceServiceBundle:Group')->find($data['id']);
            if(!$group) {

                $group = new Group();
                $group->setId($data['id']);
                $group->setName($data['name']);
                $group->setUrl($data['url']);
                $group->setPresent($data['present']);
                $imgUrl = $this->saveImage($data['image'], $data['id']);
                $group->setImage($imgUrl);
                $group->setSort($data['sort']);
                $group->setPlace($em->getRepository('ServiceServiceBundle:Place')->find($data['id_place']));

                $em->persist($group);
            }else{
                if(!empty($data['name']))
                    $group->setName($data['name']);
                if(!empty($data['sort']))
                    $group->setSort($data['sort']);
				if(!empty($data['present']))
                    $group->setPresent($data['present']);
                if(!empty($data['url']))
                    $group->setUrl($data['url']);
                if(!empty($data['image'])){
                    $imgUrl = $this->saveImage($data['image'], $data['id']);
                    $group->setImage($imgUrl);
                }
                if(!empty($data['id_place']))
                    $group->setPlace($em->getRepository('ServiceServiceBundle:Place')->find($data['id_place']));
            }
            $em->flush();

            if(!empty($data)) return $this->successMessage($data); else return $this->successMessage();
        }catch (\Exception $e){
            throw new ServiceException('Method: /add_group/', 9);
        }
    }
    public function addPositionAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $data = RequestParser::parseRequest($request);
            $errors = RequestParser::requestValidate($data, 'id');
            if(!empty($errors)) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);
            $em = $this->getDoctrine()->getManager();

            /** @var Position $position */
            $position = $em->getRepository('ServiceServiceBundle:Position')->find($data['id']);
            if(!$position){
				
                $position = new Position();
                $position->setId($data['id']);
                $position->setName($data['name']);
				$position->setPresent($data['present']);
                $position->setSort($data['sort']);
                $position->setDescription($data['description']);
				
                $imgUrl = $this->saveImage($data['image'], $data['id']);

                $position->setImage($imgUrl);
                $position->setUrl($data['url']);
                $position->setPrice($data['price']);
                $position->setGroup($em->getRepository('ServiceServiceBundle:Group')->find($data['id_group']));

                $em->persist($position);
				
            }else{
                if(!empty($data['name']))
                    $position->setName($data['name']);
				if(!empty($data['present']))
                    $position->setPresent($data['present']);
                if(!empty($data['description']))
                    $position->setDescription($data['description']);
                if(!empty($data['price']))
                    $position->setPrice($data['price']);
                if(!empty($data['url']))
                    $position->setUrl($data['url']);
                if(!empty($data['image'])){
					$this->deleteImage($position->getImage());
                    $imgUrl = $this->saveImage($data['image'], $data['id']);
                    $position->setImage($imgUrl);
                }
                if(!empty($data['sort']))
                    $position->setSort($data['sort']);
                if(!empty($data['id_group']))
                    $position->setGroup($em->getRepository('ServiceServiceBundle:Group')->find($data['id_group']));
            }


            $em->flush();
			

            if(!empty($data)) return $this->successMessage($data); else return $this->successMessage();
        }catch (\Exception $e){
            throw new ServiceException('Method: /add_position/', 10);
        }
    }
    public function addOrderAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $data = RequestParser::parseRequest($request);
            $errors = RequestParser::requestValidate($data, 'id');
            if(!empty($errors)) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);
            $em = $this->getDoctrine()->getManager();

            /** @var Order $order */
            $order = $em->getRepository('ServiceServiceBundle:Order')->find($data['id']);
            if(!$order) {

                $order = new Order();
                $order->setId($data['id']);
                $order->setDate(new \DateTime($data['time']));
                $order->setPlace($data['place']);
                $order->setWorkday($em->getRepository('ServiceServiceBundle:WorkDay')->find($data['id_workday']));

                $em->persist($order);
            }else{
                if(!empty($data['time']))
                    $order->setDate(new \DateTime(date('Y-m-d H:i:s',$data['time'])));
                if(!empty($data['place']))
                    $order->setPlace($data['place']);
                if(!empty($data['id_workday']))
                    $order->setWorkday($em->getRepository('ServiceServiceBundle:WorkDay')->find($data['id_workday']));
            }
            $em->flush();

            if(!empty($data)) return $this->successMessage($data); else return $this->successMessage();
        }catch (\Exception $e){
            throw new ServiceException('Method: /add_order/', 11);
        }
    }

    //todo ADD--UPDATE ACTIONS END ///////////////////

    //todo WORKDAY ACTIONS ///////////////////

    public function startWorkDayAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $em = $this->getDoctrine()->getManager();
			
			$workday = $em->getRepository('ServiceServiceBundle:WorkDay')->findOneBy(array('closed' => null));
			if($workday) throw new ServiceException('Method: /start_workday/ Workday already opened', 12);

            $workday = new WorkDay();
            $workday->setOpened(new \DateTime());
			$workday->setLocked(1);
			
            $em->persist($workday);
            $em->flush();

            if(!empty($data)) return $this->successMessage($data); else return $this->successMessage();
        }catch (\Exception $e){
            throw new ServiceException('Method: /start_workday/', 12);
        }
    }
    public function closeWorkDayAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $em = $this->getDoctrine()->getManager();
            /** @var WorkDay $workday */
            $workday = $em->getRepository('ServiceServiceBundle:WorkDay')->findOneBy(array('closed' => null));
			if(!$workday) throw new ServiceException('Method: /close_workday/ No active workdays found', 201);


            /** @var \Doctrine\Common\Collections\Collection $orders */
            $orders = $workday->getOrders()->getValues();

            foreach($orders as $order){
                $orderPositions = $order->getPositionOrderContents();
                foreach($orderPositions as $orderPosition){

                    /** @var OrderContent $orderPosition */
                    ($orderPosition);
                    /** @var Position $position */
                    $position = $orderPosition->getPosition();

                    /** @var Archive $archive */
                    $archive = new Archive();
                    $archive->setIdWorkday($workday->getId());
                    $archive->setPlace($order->getPlace());
                    $archive->setPaymentId($order->getPaymentId());
                    $archive->setDate($order->getDate());
                    $archive->setAmount($orderPosition->getAmount());
                    $archive->setIdOrder($order->getId());
                    $archive->setIdPosition($position->getId());
                    $archive->setSum($orderPosition->getSum());

                    $em->persist($archive);
                    $em->flush();

                }

            }

            $workday->setClosed(new \DateTime());

            $em->persist($workday);
            $em->flush();

            if(!empty($data)) return $this->successMessage($data); else return $this->successMessage();
        }catch (\Exception $e){
            throw new ServiceException('Method: /close_workday/', 13);
        }
    }

    //todo WORKDAY ACTIONS END ///////////////////


    //todo GET ACTIONS ///////////////////

    public function getGroupByIDAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $data = RequestParser::parseRequest($request);
            $errors = RequestParser::requestValidate($data, 'id');
            if(!empty($errors)) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);
            $em = $this->getDoctrine()->getManager();

            $group = $em->getRepository('ServiceServiceBundle:Group')->find($data['id']);

            return $this->render('ServiceServiceBundle:Service:index.html.php', array(
                'result' => OutputHandler::handle($data, $group)
            ));
        }catch (\Exception $e){
            throw new ServiceException('Method: /get_group_by_id/', 14);
        }
    }
    public function getPlaceByIDAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $data = RequestParser::parseRequest($request);
            $errors = RequestParser::requestValidate($data, 'id');
            if(!empty($errors)) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);
            $em = $this->getDoctrine()->getManager();

            $place = $em->getRepository('ServiceServiceBundle:Place')->find($data['id']);

            return $this->render('ServiceServiceBundle:Service:index.html.php', array(
                'result' => OutputHandler::handle($data, $place)
            ));
        }catch (\Exception $e){
            throw new ServiceException('Method: /get_place_by_id/', 15);
        }
    }
    public function getSectorByIDAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $data = RequestParser::parseRequest($request);
            $errors = RequestParser::requestValidate($data, 'id');
            if(!empty($errors)) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);
            $em = $this->getDoctrine()->getManager();

            $sector = $em->getRepository('ServiceServiceBundle:Sector')->find($data['id']);

            return $this->render('ServiceServiceBundle:Service:index.html.php', array(
                'result' => OutputHandler::handle($data, $sector)
            ));
        }catch (\Exception $e){
            throw new ServiceException('Method: /get_sector_by_id/', 16);
        }
    }
    public function getPositionByIDAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $data = RequestParser::parseRequest($request);
            $errors = RequestParser::requestValidate($data, 'id');
            if(!empty($errors)) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);
            $em = $this->getDoctrine()->getManager();

            $position = $em->getRepository('ServiceServiceBundle:Position')->find($data['id']);

            return $this->render('ServiceServiceBundle:Service:index.html.php', array(
                'result' => OutputHandler::handle($data, $position)
            ));
        }catch (\Exception $e){
            throw new ServiceException('Method: /get_position_by_id/', 17);
        }
    }
    public function getOrderByIDAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $data = RequestParser::parseRequest($request);
            $errors = RequestParser::requestValidate($data, 'id');
            if(!empty($errors)) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);
            $em = $this->getDoctrine()->getManager();

            $order = $em->getRepository('ServiceServiceBundle:Order')->find($data['id']);

            return $this->render('ServiceServiceBundle:Service:index.html.php', array(
                'result' => OutputHandler::handle($data, $order)
            ));
        }catch (\Exception $e){
            throw new ServiceException('Method: /get_order_by_id/', 18);
        }
    }
    public function getWorkDayByIDAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $data = RequestParser::parseRequest($request);
            $errors = RequestParser::requestValidate($data, 'id');
            if(!empty($errors)) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);
            $em = $this->getDoctrine()->getManager();

            $workday = $em->getRepository('ServiceServiceBundle:WorkDay')->find($data['id']);

            return $this->render('ServiceServiceBundle:Service:index.html.php', array(
                'result' => OutputHandler::handle($data, $workday)
            ));
        }catch (\Exception $e){
            throw new ServiceException('Method: /get_workday_by_id/', 19);
        }
    }

    //todo GET ACTIONS END ///////////////////

    //todo NEW ORDER ///////////////////

    public function newOrderAction(Request $request){
        try {
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if ($errors !== false) if (!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $data = RequestParser::parseRequest($request);
            $em = $this->getDoctrine()->getManager();

            $order = new Order();
            $order->setDate(new \DateTime(date('Y-m-d H:i:s', $data['time'])));
            $order->setPlace($data['place']);
            $order->setPaymentId($data['payment_id']);

            $workday = $em->getRepository('ServiceServiceBundle:WorkDay')->findOneBy(array('closed' => null));
            
            if(!$workday) throw new ServiceException('Method: /new_order/ No active workdays found!', 200);
            
            $order->setWorkday($workday);

            foreach ($data['positions'] as $key => $pos) {
                $position = $em->getRepository('ServiceServiceBundle:Position')->find((int)$key);

                $orderContent = new OrderContent();
                $orderContent->setAmount($pos->amount);
                $orderContent->setSum($pos->price * $pos->amount);
                $orderContent->setPosition($position);
                $orderContent->setOrder($order);

                $order->addPositionOrderContent($orderContent);
            }

            $em->persist($order);
            $em->flush();


            if (!empty($data)) return $this->successMessage($data); else return $this->successMessage();
        }catch (\Exception $e){
            throw new ServiceException('Method: /new_order/', 20);
        }
    }

    //todo NEW ORDER END ///////////////////


    //todo GET BY ACTIONS ///////////////////

    public function getGroupsByPlaceAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $data = RequestParser::parseRequest($request);
            $errors = RequestParser::requestValidate($data, 'id');
            if(!empty($errors)) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);
            $em = $this->getDoctrine()->getManager();
            $groups = $em->getRepository('ServiceServiceBundle:Group')->findBy(array('place' => $data['id']));

            return $this->render('ServiceServiceBundle:Service:index.html.php', array(
                'result' => OutputHandler::handle($data, $groups)
            ));
        }catch (\Exception $e){
            throw new ServiceException('Method: /get_groups_by_place/', 21);
        }
    }
    public function getPositionsByGroupAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $data = RequestParser::parseRequest($request);
            $errors = RequestParser::requestValidate($data, 'id');
            if(!empty($errors)) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);
            $em = $this->getDoctrine()->getManager();

            $positions =$em->getRepository('ServiceServiceBundle:Position')->findBy(array('group' => $data['id']));

            return $this->render('ServiceServiceBundle:Service:index.html.php', array(
                'result' => OutputHandler::handle($data, $positions)
            ));
        }catch (\Exception $e){
            throw new ServiceException('Method: /get_positions_by_group/', 22);
        }
    }
    public function getSectorsByPlaceAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $data = RequestParser::parseRequest($request);
            $errors = RequestParser::requestValidate($data, 'id');
            if(!empty($errors)) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);
            $em = $this->getDoctrine()->getManager();

            $sectors = $em->getRepository('ServiceServiceBundle:Sector')->findBy(array('place' => $data['id']));

            return $this->render('ServiceServiceBundle:Service:index.html.php', array(
                'result' => OutputHandler::handle($data, $sectors)
            ));
        }catch (\Exception $e){
            throw new ServiceException('Method: /get_sectors_by_place/', 23);
        }
    }
    public function getOrdersByWorkDayAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $data = RequestParser::parseRequest($request);
            $errors = RequestParser::requestValidate($data, 'id');
            if(!empty($errors)) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);
            $em = $this->getDoctrine()->getManager();

            $orders = $em->getRepository('ServiceServiceBundle:Order')->findBy(array('workday' => $data['id']));

            return $this->render('ServiceServiceBundle:Service:index.html.php', array(
                'result' => OutputHandler::handle($data, $orders)
            ));
        }catch (\Exception $e){
            throw new ServiceException('Method: /get_orders_by_workday/', 24);
        }
    }
    public function getOrdersByTimeAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $data = RequestParser::parseRequest($request);
            $errors = RequestParser::requestValidate($data, 'time');
            if(!empty($errors)) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);
            $em = $this->getDoctrine()->getManager();

            $dateFrom = new \DateTime(date('Y-m-d H:i:s', $data['time']));
            $orders = $em->getRepository('ServiceServiceBundle:Order')->getOrdersFromTime($dateFrom);

            return $this->render('ServiceServiceBundle:Service:index.html.php', array(
                'result' => OutputHandler::handle($data, $orders)
            ));
        }catch (\Exception $e){
            throw new ServiceException('Method: /get_orders_by_time/', 25);
        }
    }
	public function getOrdersByIDAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $data = RequestParser::parseRequest($request);
            $errors = RequestParser::requestValidate($data, 'id');
            if(!empty($errors)) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);
            $em = $this->getDoctrine()->getManager();

            //$dateFrom = new \DateTime(date('Y-m-d H:i:s', $data['time']));
			$id = (int)$data['id'];
            $orders = $em->getRepository('ServiceServiceBundle:Order')->getOrdersFromId($id);

            return $this->render('ServiceServiceBundle:Service:index.html.php', array(
                'result' => OutputHandler::handle($data, $orders)
            ));
        }catch (\Exception $e){
            throw new ServiceException('Method: /get_orders_by_id/', 38);
        }
    }
    public function getPositionsByOrderAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $data = RequestParser::parseRequest($request);
            $errors = RequestParser::requestValidate($data, 'id');
            if(!empty($errors)) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);
            $em = $this->getDoctrine()->getManager();

            /** @var Order $order */
            $order = $em->getRepository('ServiceServiceBundle:Order')->find($data['id']);


            /** @var  \Doctrine\Common\Collections\Collection $positionsRes */
            $positionsRes = $order->getPositionOrderContents()->getValues();
            $positions = array();
            foreach($positionsRes as $orderPosition) {

                /** @var Position $position */
                $position = $orderPosition->getPosition();
                $position->setOrderPrice($orderPosition->getSum());
                $position->setOrderSum($orderPosition->getAmount());
                $positions[] = $position;
            }

            return $this->render('ServiceServiceBundle:Service:index.html.php', array(
                'result' => OutputHandler::handle($data, $positions, null, 'cut')
            ));
        }catch (\Exception $e){
            throw new ServiceException('Method: /get_positions_by_order/', 26);
        }
    }
    public function getCurrentWorkDayAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $em = $this->getDoctrine()->getManager();
            $workday = $em->getRepository('ServiceServiceBundle:WorkDay')->findOneBy(array('closed' => null));

            return $this->render('ServiceServiceBundle:Service:index.html.php', array(
                'result' => OutputHandler::handle(null, $workday)
            ));
        }catch (\Exception $e){
            throw new ServiceException('Method: /get_current_workday/', 27);
        }
    }

    public function getArchiveByDayAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);


            $data = RequestParser::parseRequest($request);
            $em = $this->getDoctrine()->getManager();

            $connection = $em->getConnection();
            $statement = $connection->prepare("
            SELECT *
            FROM archive as a
            WHERE DATE(a.date) = DATE('".date('Y-m-d H:i:s',$data['time'])."')
        ");

            $statement->execute();
            $results = $statement->fetchAll();

            $archives = array();
            foreach($results as $result){
                $ar = new Archive();
                $ar->setId($result['id']);
                $ar->setIdOrder($result['id_order']);
                $ar->setDate(new \DateTime($result['date']));
                $ar->setPlace($result['place']);
                $ar->setIdPosition($result['id_position']);
                $ar->setIdWorkday($result['id_workday']);
                $ar->setSum($result['sum']);
                $ar->setAmount($result['amount']);

                $archives[] = $ar;

            }

            return $this->render('ServiceServiceBundle:Service:index.html.php', array(
                'result' => OutputHandler::handle($data, $archives)
            ));
        }catch (\Exception $e){
            throw new ServiceException('Method: /get_archive_by_day/', 28);
        }
    }

    //todo GET BY ACTIONS END ///////////////////


    //todo DELETE ACTIONS ///////////////////


    public function deleteSectorsAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $em = $this->getDoctrine()->getManager();
            $sectors = $em->getRepository('ServiceServiceBundle:Sector')->findAll();
            foreach($sectors as $sector){
                $em->remove($sector);
            }
            $em->flush();

            if(!empty($data)) return $this->successMessage($data); else return $this->successMessage();
        }catch (\Exception $e){
            throw new ServiceException('Method: /delete_sectors/', 29);
        }
    }

    public function deletePlacesAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $em = $this->getDoctrine()->getManager();
            $places = $em->getRepository('ServiceServiceBundle:Place')->findAll();
            foreach($places as $place){
                $em->remove($place);
            }
            $em->flush();

            if(!empty($data)) return $this->successMessage($data); else return $this->successMessage();
        }catch (\Exception $e){
            throw new ServiceException('Method: /delete_places/', 30);
        }
    }

    public function deleteGroupsAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $em = $this->getDoctrine()->getManager();
            $groups = $em->getRepository('ServiceServiceBundle:Group')->findAll();
            foreach($groups as $group){
                $em->remove($group);
            }
            $em->flush();

            if(!empty($data)) return $this->successMessage($data); else return $this->successMessage();
        }catch (\Exception $e){
            throw new ServiceException('Method: /delete_groups/', 31);
        }
    }

    public function deletePositionsAction(Request $request){
         try{
			$errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
			if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

			$em = $this->getDoctrine()->getManager();
			$positions = $em->getRepository('ServiceServiceBundle:Position')->findAll();

			foreach($positions as $position){
				$this->deleteImage($position->getImage());
				$em->remove($position);
			}
			$em->flush();

        if(!empty($data)) return $this->successMessage($data); else return $this->successMessage();
        }catch (\Exception $e){
			throw new ServiceException('Method: /delete_positions/', 32);
        }
    }


    //todo DELETE ACTIONS END ///////////////////



    //todo ADD MULTIPLE ACTIONS ///////////////////


    public function addPositionsAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $data = RequestParser::parseRequest($request);
            $em = $this->getDoctrine()->getManager();

            if(isset($data['id'])){
                $tmp = $data;
                $data = array($tmp);
            }

            foreach($data as $position){

                /** @var Position $pos */
                $pos = $em->getRepository('ServiceServiceBundle:Position')->find($position['id']);
                if(!$pos){
                    $pos = new Position();
                    $pos->setId($position['id']);
                    $pos->setName($position['name']);
                    $pos->setSort($position['sort']);
					$pos->setPresent($position['present']);
                    $pos->setDescription($position['description']);

                    $imgUrl = $this->saveImage($data['image'], $position['id']);

                    $pos->setImage($imgUrl);
                    $pos->setUrl($position['url']);
                    $pos->setPrice($position['price']);
                    $pos->setGroup($em->getRepository('ServiceServiceBundle:Group')->find($position['id_group']));

                    $em->persist($pos);
                }else{
                    if(!empty($position['name']))
                        $pos->setName($position['name']);
                    if(!empty($position['sort']))
                        $pos->setSort($position['sort']);
					if(!empty($position['present']))
                        $pos->setPresent($position['present']);
                    if(!empty($position['description']))
                        $pos->setDescription($position['description']);
                    if(!empty($position['image'])){
						$this->deleteImage($pos->getImage());
                        $imgUrl = $this->saveImage($data['image'], $position['id']);
                        $pos->setImage($imgUrl);
                    }
                    if(!empty($position['url']))
                        $pos->setUrl($position['url']);
                    if(!empty($position['price']))
                        $pos->setPrice($position['price']);
                    if(!empty($position['id_group']))
                        $pos->setGroup($em->getRepository('ServiceServiceBundle:Group')->find($position['id_group']));

                }

            }
            $em->flush();

            if(!empty($data)) return $this->successMessage($data); else return $this->successMessage();
        }catch (\Exception $e){
            throw new ServiceException('Method: /add_positions/', 33);
        }
    }

    public function addGroupsAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $data = RequestParser::parseRequest($request);
            $em = $this->getDoctrine()->getManager();

            if(isset($data['id'])){
                $tmp = $data;
                $data = array($tmp);
            }

            foreach($data as $group){
                /** @var Group $gr */
                $gr = $em->getRepository('ServiceServiceBundle:Group')->find($group['id']);
                if(!$gr){

                    $gr = new Group();
                    $gr->setId($group['id']);
                    $gr->setName($group['name']);
					$gr->setPresent($group['present']);
                    $gr->setSort($group['sort']);
                    $gr->setUrl($group['url']);
                    $imgUrl = $this->saveImage($group['image'], $group['id']);
                    $gr->setImage($imgUrl);
                    $gr->setPlace($em->getRepository('ServiceServiceBundle:Group')->find($group['id_place']));

                    $em->persist($gr);
                }else{
                    if(!empty($group['id']))
                        $gr->setId($group['id']);
					if(!empty($group['present']))
                        $gr->setPresent($group['present']);
                    if(!empty($group['name']))
                        $gr->setName($group['name']);
                    if(!empty($group['sort']))
                        $gr->setSort($group['sort']);
                    if(!empty($group['url']))
                        $gr->setUrl($group['url']);
                    if(!empty($group['image'])){
                        $imgUrl = $this->saveImage($group['image'], $group['id']);
                        $gr->setImage($imgUrl);
                    }
                    if(!empty($group['id_place']))
                        $gr->setPlace($em->getRepository('ServiceServiceBundle:Group')->find($group['id_place']));

                }

            }
            $em->flush();

            if(!empty($data)) return $this->successMessage($data); else return $this->successMessage();
        }catch (\Exception $e){
            throw new ServiceException('Method: /add_groups/', 34);
        }
    }



    //todo ADD MULTIPLE ACTIONS END ///////////////////
	
	public function lockWorkdayAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $em = $this->getDoctrine()->getManager();
			$workday = $em->getRepository('ServiceServiceBundle:WorkDay')->findOneBy(array('closed' => null));

            if(!$workday) throw new ServiceException('Method: /lock_workday/ No active workday', 156);
			$workday->setLocked(1);
			$em->flush();

            return $this->successMessage();
        }catch (\Exception $e){
            throw new ServiceException('Method: /lock_workday/', 157);
        }
    }
	
	public function unlockWorkdayAction(Request $request){
        try{
            $errors = RequestParser::checkVersion($request, $this->container->getParameter('service_service.versions.current'));
            if($errors !== false) if(!empty($data)) return $this->errorsMessage($errors, $data); else return $this->errorsMessage($errors);

            $em = $this->getDoctrine()->getManager();
			$workday = $em->getRepository('ServiceServiceBundle:WorkDay')->findOneBy(array('closed' => null));

            if(!$workday) throw new ServiceException('Method: /lock_workday/ No active workday', 156);
			$workday->setLocked(0);
			$em->flush();

            return $this->successMessage();
        }catch (\Exception $e){
            throw new ServiceException('Method: /lock_workday/', 157);
        }
    }
	
	
	
	




    public function successMessage($data = null){

        if(!empty($data)){
            $file = $_SERVER['DOCUMENT_ROOT'].'/dev/service/log.txt';
            $current = file_get_contents($file);
            $current .= date('d.m.Y H.i.s')."::SUCCESS::input data: ".json_encode($data)."\n";
            file_put_contents($file, $current);
        }


        return $this->render('ServiceServiceBundle:Service:index.html.php', array(
            'result' => Messager::getSuccessResultMessage()
        ));
    }

    public function errorsMessage($errors, $data = null){

        if(!empty($data)){
            $file = $_SERVER['DOCUMENT_ROOT'].'/dev/service/log.txt';
            $current = file_get_contents($file);
            $current .= date('d.m.Y H.i.s')."::ERROR::input data: ".json_encode($data).". errors: ".json_encode($errors)."\n";
            file_put_contents($file, $current);
        }


        $result['error'] = $errors;
        return $this->render('ServiceServiceBundle:Service:index.html.php', array(
            'result' => $errors
        ));
    }

    public function saveImage($image, $id, $alterPath = null, $originalName = false){
		if($alterPath !== null) $imgUploadPath = $alterPath;
		else
			$imgUploadPath = $this->container->getParameter('service_service.image.url');
        try {
			$image = str_replace(' ', '+', $image);
			$imgDecoded = base64_decode($image);
		
            $imgInfo = getimagesizefromstring($imgDecoded);
		
			if(strpos($imgInfo['mime'], 'jpeg') !== false || strpos($imgInfo['mime'], 'jpg') !== false)
				$imgExt = '.jpg';
			elseif(strpos($imgInfo['mime'], 'png') !== false)
				$imgExt = '.png';
			else
				$imgExt = '.img';
			
			$imgName = md5($id.time());
			$imgUrl = $_SERVER['DOCUMENT_ROOT'].$imgUploadPath.$imgName.$imgExt;
			$imgLink = $imgUploadPath.$imgName.$imgExt;
			
			if($originalName !== false)
				$imgUrl = $_SERVER['DOCUMENT_ROOT'].$imgUploadPath.$originalName.$imgExt;
			else
				$imgUrl = $_SERVER['DOCUMENT_ROOT'].$imgUploadPath.$imgName.$imgExt;
			
			$a = file_put_contents($imgUrl, $imgDecoded);
		
		}catch (Exception $e){
            throw new ServiceException('Method: /add_position/ Failed to save image', 100);
        }
        return $imgLink;
    }
	
	public function deleteImage($path){
		
		$truePath = $_SERVER['DOCUMENT_ROOT'].$path;
		unlink($truePath);
		
	}


    /**
     * @param array $requestData
     * sanitizing user profile data
     * int casting for numbers and real_escape_string for strings
     */
    public static function sanitizeUserProfileData(&$requestData){
        $mysqli = new \mysqli();
        if(!empty($requestData['id']))
            $requestData['id'] = (int)$requestData['id'];
        if(!empty($requestData['age']))
            $requestData['age'] = $mysqli->real_escape_string($requestData['age']);
        if(!empty($requestData['city']))
            $requestData['city'] = $mysqli->real_escape_string($requestData['city']);
        if(!empty($requestData['appearance']))
            $requestData['appearance'] = $mysqli->real_escape_string($requestData['appearance']);
        if(!empty($requestData['aboutMe']))
            $requestData['aboutMe'] = $mysqli->real_escape_string($requestData['aboutMe']);
        if(!empty($requestData['sex']))
            $requestData['sex'] = (int)$requestData['sex'];
        if(!empty($requestData['wannaCommunicate']))
            $requestData['wannaCommunicate'] = (int)$requestData['wannaCommunicate'];
        if(!empty($requestData['findCompanion']))
            $requestData['findCompanion'] = (int)$requestData['findCompanion'];
        if(!empty($requestData['findCouple']))
            $requestData['findCouple'] = (int)$requestData['findCouple'];
        if(!empty($requestData['findFriends']))
            $requestData['findFriends'] = (int)$requestData['findFriends'];
        if(!empty($requestData['free']))
            $requestData['free'] = (int)$requestData['free'];
        if(!empty($requestData['orientation']))
            $requestData['orientation'] = (int)$requestData['orientation'];

    }

    public function cacheClearAction(){
        $fs = new Filesystem();
        $fs->remove($this->container->getParameter('kernel.cache_dir'));
        die('done');
    }


}