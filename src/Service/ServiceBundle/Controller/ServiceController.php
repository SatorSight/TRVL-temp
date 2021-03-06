<?php

namespace Service\ServiceBundle\Controller;

use Doctrine\ORM\EntityManager;
use Service\ServiceBundle\Entity\City;
use Service\ServiceBundle\Entity\Country;
use Service\ServiceBundle\Entity\Feedback;
use Service\ServiceBundle\Entity\Flight;
use Service\ServiceBundle\Entity\Like;
use Service\ServiceBundle\Entity\Photo;
use Service\ServiceBundle\Entity\PushText;
use Service\ServiceBundle\Entity\Repository\Messager;
use Service\ServiceBundle\Entity\Repository\FlightRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Service\ServiceBundle\Entity\AirportTest;
use Service\ServiceBundle\Entity\Blacklist;
use Service\ServiceBundle\Entity\Media;
use Service\ServiceBundle\Entity\Message;
use Service\ServiceBundle\Entity\Moder;
use Service\ServiceBundle\Entity\Profile;
use Service\ServiceBundle\Entity\UserFlight;
use Service\ServiceBundle\Entity\Travel;
use Service\ServiceBundle\Entity\TravelType;
use Service\ServiceBundle\Entity\User;

use Service\ServiceBundle\Resources\SUtils;

use Service\ServiceBundle\Entity\Repository\RequestParser;
use Service\ServiceBundle\Entity\Repository\OutputHandler;

use Service\ServiceBundle\Resources\ServiceException;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Service controller.
 */
class ServiceController extends Controller
{


    public function indexAction(Request $request){

        $requestData = RequestParser::parseRequest($request);

        if($requestData['action'] != 'admin')
            $errors = RequestParser::checkApiKey($request, $this->container->getParameter('service_service.api_key'));

        $noAuthActions = ['auth', 'get_cities', 'test', 'get_politics', 'get_rules', 'admin', 'get_settings_share', 'get_flights_share'];

        $tokenFailed = false;
        if(!in_array($requestData['action'], $noAuthActions) && !$errors) {
            $err = $this->checkToken($requestData);
            if(!$err)
                $errors = 'token mismatch';

//            $errors = $this->checkToken($requestData) ? false : ['Token mismatch'];
        }

        $return = [];

        $resCode = 'OK';

        if(!empty($requestData['action']) && !$errors) {

            switch ($requestData['action']){


                case 'test':

                    $this->createPartnerTrainLink('24032017','MOW','IKT');
                    die('push stop');
                    //SUtils::trace($this->getTrainsFromData($requestData));
                    break;

                case 'admin':
                    return $this->admin();
                    break;

                case 'auth':
                    $return[] = $this->auth($requestData);
                    break;

                case 'save_profile':
                    $return[] = $this->saveProfile($requestData);
                    break;

                case 'save_profile_image':
                    $return[] = $this->saveProfileImage($requestData);
                    break;

                case 'get_profile_image':
                    $return[] = $this->getProfileImage($requestData);
                    break;

                case 'reload_data':
                    $return[] = $this->reloadData($requestData, $resCode);
                    break;

                case 'users_profiles':
                    $return[] = $this->getUsersProfiles($requestData);
                    break;



                case 'like_user':
                    $return[] = $this->likeUser($requestData);
                    break;

                case 'get_who_liked_me':
                    $return[] = $this->getWhoLikedMe($requestData);
                    break;

                case 'get_who_i_liked':
                    $return[] = $this->getWhoILiked($requestData);
                    break;

                case 'get_user_liked_count_by_id':
                    $return[] = $this->getLikedCountByID($requestData);
                    break;

                case 'get_liked_count_me':
                    $return[] = $this->getLikedCountMe($requestData);
                    break;

                case 'get_mutual_likes':
                    $return[] = $this->getMutualLikes($requestData);
                    break;


                //photos

                case 'get_my_photos':
                    $return[] = $this->getMyPhotos($requestData);
                    break;

                case 'get_user_photos':
                    $return[] = $this->getUserPhotos($requestData);
                    break;

                case 'save_photo':
                    $return[] = $this->savePhoto($requestData);
                    break;

                case 'get_user_photo_by_id':
                    $return[] = $this->getUserPhotoByID($requestData);
                    break;

                case 'get_user_photo_contents':
                    $return[] = $this->getUserPhotoContentsByURL($requestData);
                    break;

                case 'get_user_ok_photos':
                    $return[] = $this->getUserOKPhotos($requestData);
                    break;

//                case 'registration':
//                    $return[] = $this->registration($requestData['id'], $requestData['token']);
//                    break;

                case 'get_cities':
                    $return[] = $this->getCities();
                    break;


                case 'get_politics':
                    $return[] = $this->getPolitics();
                    break;

                case 'get_rules':
                    $return[] = $this->getRules();
                    break;

                case 'get_settings_share':
                    $return[] = $this->getSettingsShare();
                    break;

                case 'get_flights_share':
                    $return[] = $this->getFlightsShare($requestData);
                    break;



                //////////////////////////////FLIGHTS INTERACTIONS//////////////////////////////
                //Show flights for date and directions
                case 'get_flight_codes':
                    $return[] = $this->getFlightCodesFromData($requestData);
                    break;

                case 'get_flight_codes_with_date':
                    $return[] = $this->getFlightCodesWithDateFromData($requestData);
                    break;

                case 'get_user_flights':
                    $return[] = $this->getUserFlights($requestData);
                    break;

                case 'get_flight_details':
                    $return[] = $this->getFlightDetails($requestData);
                    break;

                case 'add_user_to_flight':
                    $return[] = $this->addUserToFlight($requestData);
                    break;

                case 'add_user_to_existing_flight':
                    $return[] = $this->addUserToExistingFlight($requestData);
                    break;


                case 'get_flight_users':
                    $return[] = $this->getFlightUsers($requestData);
                    break;

                case 'get_flights_with_users':
                    $return[] = $this->getFlightsWithUsers($requestData);
                    break;
                //////////////////////////////FLIGHTS INTERACTIONS END//////////////////////////////


                case 'chat_register':
                    $return[] = $this->chatRegister($requestData);
                    break;

                case 'get_user_profile':
                    $return[] = $this->getUserProfile($requestData);
                    break;


                case 'send_feedback':
                    $return[] = $this->sendFeedback($requestData);
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
            $return = $errors;
//            echo json_encode($errors);

        return $this->render('ServiceServiceBundle:Service:index.html.php', array(
            'result' => OutputHandler::handle(null, $return, null, '', $resCode)
        ));

//        $em = $this->getDoctrine()->getManager();
//        $airports = $em->getRepository('ServiceServiceBundle:AirportTest')->findAll();

    }

    public function getSettingsShare(){

//        SUtils::dump('hi');

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var PushText $text */
        $text = $em->getRepository('ServiceServiceBundle:PushText')->findOneBy(['id' => 3]);

//        SUtils::dump($text->getId());
//        SUtils::dump($text->getLabel());

        if(empty($text->getAddText()) || empty($text->getAddValue()))
            $l = false;
        else $l = true;

        $arr = [
            'id' => $text->getId(),
            'text' => $text->getValue(),
            'text_link' => $text->getAddText(),
            'text_link_val' => $text->getAddValue(),
            'link' => $l ? 'yes' : 'no'
        ];

//        SUtils::dump($arr);

        return $arr;
    }

    public function getFlightsShare($requestData){

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var PushText $text */
        $text = $em->getRepository('ServiceServiceBundle:PushText')->findOneBy(['id' => 4]);

        $t = $text->getValue();
        if(strpos($t, '#ROUTE#'))
            $t = str_replace('#ROUTE#', $requestData['route'], $t);
        if(strpos($t, '#DATE#'))
            $t = str_replace('#DATE#', $requestData['date'], $t);





        if(empty($text->getAddText()) || empty($text->getAddValue()))
            $l = false;
        else $l = true;

        $arr = [
            'id' => $text->getId(),
            'text' => $t,
            'text_link' => $text->getAddText(),
            'text_link_val' => $text->getAddValue(),
            'link' => $l ? 'yes' : 'no'
        ];

        return $arr;
    }


    public function banUser($id){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['id' => $id]);
        $user->setBanned(true);
        $em->flush();
    }

    public function unbanUser($id){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['id' => $id]);
        $user->setBanned(false);
        $em->flush();
    }
    
    
    
    public function admin(){

        session_start();

        $user = 'admin';
        $pass = '123';

        $wrong = false;

        if($_POST['user'] && $_POST['pass']){


            if($_POST['user'] == $user && $_POST['pass'] == $pass){
                $_SESSION['user'] = $user;
                $_SESSION['pass'] = $pass;

            }else
                $wrong = true;


        }


        //(!isset($_SESSION['user']) || !isset($_SESSION['pass'])) ||
        if($_SESSION['user'] != $user || $_SESSION['pass'] != $pass){

            return $this->render('ServiceServiceBundle:Service:login.html.php', ['wrong' => $wrong]);
            
        }



        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        
        if(!$_GET['sub'] || $_GET['sub'] == 'users'){


            if($_POST['ban_him']){
                $this->banUser($_POST['ban_him']);
            }




            $userArr = [];


            /** @var User[] $users */
            $users = $em->getRepository('ServiceServiceBundle:User')->findAll();

            foreach($users as $user){

                /** @var Profile $profile */
                $profile = $user->getProfile();


                $u = [];
                $u['id'] = $user->getId();
                $u['created'] = $user->getInserted()->format('Y-m-d');
                $u['banned'] = $user->getBanned();
                $u['app_type'] = $user->getAppType();
                $u['chat_id'] = $user->getChatId();
                $u['name'] = $profile->getName();
                $u['last_visit'] = $profile->getLastVisit()->format('Y-m-d');
                $u['about'] = $profile->getAbout();
                $u['orientation'] = $profile->getOrientation();
                $u['appearance'] = $profile->getAppearance();
                $u['age'] = $profile->getAge();
                $u['city'] = $profile->getCity();
                $u['sex'] = $profile->getSex();
                $u['wannaCommunicate'] = $profile->getWannaCommunicate();
                $u['findCompanion'] = $profile->getFindCompanion();
                $u['findCouple'] = $profile->getFindCouple();
                $u['findFriends'] = $profile->getFindFriends();
                $u['free'] = $profile->getFree();

                $userArr[] = $u;

            }

            return $this->render('ServiceServiceBundle:Service:admin.html.php', array(
                'users' => $userArr,
                'forPage' => 20
            ));


            
        }elseif($_GET['sub'] == 'stat'){

            $man = $woman = 0;
            $age18 = $age25 = $age38 = $age50 = $age100 = 0;
            $fromArr = $toArr = [];


            /** @var User[] $users */
            $users = $em->getRepository('ServiceServiceBundle:User')->findAll();


            foreach($users as $user){
                $profile = $user->getProfile();

                if(!empty($profile->getAge())){
                    if($profile->getAge() < 18){
                        $age18++;
                    }elseif($profile->getAge() < 25){
                        $age25++;
                    }elseif($profile->getAge() < 38){
                        $age38++;
                    }elseif($profile->getAge() < 50){
                        $age50++;
                    }else $age100++;

                }

                if($profile->getSex() == 0)
                    $woman++;
                else
                    $man++;


                $userFlights = $user->getUserFlights();
                /** @var UserFlight $userFlight */
                foreach($userFlights as $userFlight){
                    $flight = $userFlight->getFlight();

                    if(!isset($fromArr[$flight->getFromCity()]))
                        $fromArr[$flight->getFromCity()] = 0;

                    $fromArr[$flight->getFromCity()]++;

                    if(!isset($toArr[$flight->getToCity()]))
                        $toArr[$flight->getToCity()] = 0;

                    $toArr[$flight->getToCity()]++;

                }


            }


            return $this->render('ServiceServiceBundle:Service:stat.html.php', [
                'man' => $man,
                'woman' => $woman,
                'age18' => $age18,
                'age25' => $age25,
                'age38' => $age38,
                'age50' => $age50,
                'age100' => $age100,
                'from' => $fromArr,
                'to' => $toArr
            ]);

        }elseif($_GET['sub'] == 'feedback'){

            $feedbacks = $em->getRepository('ServiceServiceBundle:Feedback')->findAll();

            $feedbackArr = [];

            foreach($feedbacks as $feedback){
                $f = [];
                $f['name'] = $feedback->getName();
                $f['created'] = $feedback->getCreated()->format('Y-m-d');
                $f['email'] = $feedback->getEmail();
                $f['text'] = $feedback->getText();

                $feedbackArr[] = $f;
            }



            return $this->render('ServiceServiceBundle:Service:feedback.html.php', [
                'feedback' => $feedbackArr,
                'forPage' => 20
            ]);

        }elseif($_GET['sub'] == 'flights'){


            $flightsArr = [];

            /** @var Flight[] $flights */
            $flights = $em->getRepository('ServiceServiceBundle:Flight')->findAll();


            foreach($flights as $flight){
                $fl = [];

                $fl['id'] = $flight->getId();
                $fl['type'] = $flight->getType();
                $fl['no'] = $flight->getNo();
                $fl['from'] = $flight->getFrom();
                $fl['to'] = $flight->getTo();
                $fl['airlineCode'] = $flight->getAirlineCode();
                $fl['code'] = $flight->getCode();
                $fl['fromCode'] = $flight->getFromCode();
                $fl['fromAirport'] = $flight->getFromAirport();
                $fl['fromCity'] = $flight->getFromCity();
                $fl['fromCountry'] = $flight->getFromCountry();
                $fl['toCode'] = $flight->getToCode();
                $fl['toAirport'] = $flight->getToAirport();
                $fl['toCity'] = $flight->getToCity();
                $fl['toCountry'] = $flight->getToCountry();
                $fl['fromDate'] = $flight->getFromDate()->format('Y-m-d');
                $fl['toDate'] = $flight->getToDate()->format('Y-m-d');



                $flightsArr[] = $fl;
            }




            return $this->render('ServiceServiceBundle:Service:flights.html.php', [
                'flights' => $flightsArr,
                'forPage' => 20
            ]);

        }elseif($_GET['sub'] == 'banned'){

            if($_POST['unban_him']){
                $this->unbanUser($_POST['unban_him']);
            }

            $banned = $em->getRepository('ServiceServiceBundle:User')->findBy(['banned' => 1]);
            $bannedArr = [];
            foreach($banned as $user){
                $profile = $user->getProfile();


                $u = [];
                $u['id'] = $user->getId();
                $u['created'] = $user->getInserted()->format('Y-m-d');
                $u['banned'] = $user->getBanned();
                $u['app_type'] = $user->getAppType();
                $u['chat_id'] = $user->getChatId();
                $u['name'] = $profile->getName();
                $u['last_visit'] = $profile->getLastVisit()->format('Y-m-d');

                $bannedArr[] = $u;

            }

            return $this->render('ServiceServiceBundle:Service:banned.html.php', [
                'banned' => $bannedArr
            ]);

        }elseif($_GET['sub'] == 'texts'){

            if($_POST['politics']){
                file_put_contents('/var/www/html/TRVL-temp/app/Resources/politika.html',$_POST['politics']);
            }
            if($_POST['rules']){
                file_put_contents('/var/www/html/TRVL-temp/app/Resources/pravila.html',$_POST['rules']);
            }




            $path = '/var/www/html/TRVL-temp/app/Resources/politika.html';
            $politics = file_get_contents($path);

            $path = '/var/www/html/TRVL-temp/app/Resources/pravila.html';
            $rules = file_get_contents($path);

            return $this->render('ServiceServiceBundle:Service:texts.html.php', [
                'politics' => $politics,
                'rules' => $rules
            ]);



        }elseif($_GET['sub'] == 'logout'){

            unset($_SESSION['user']);
            unset($_SESSION['pass']);
            header('Location: /?action=admin');


        }elseif($_GET['sub'] == 'push'){


            if($_POST['push_id'] && $_POST['push_val']){
                $psh = $em->getRepository('ServiceServiceBundle:PushText')->findOneBy(['id' => $_POST['push_id']]);
                $psh->setValue($_POST['push_val']);
                $em->flush();
            }


            if($_POST['text_id'] && $_POST['text_val']){
                $psh = $em->getRepository('ServiceServiceBundle:PushText')->findOneBy(['id' => $_POST['text_id']]);
                $psh->setValue($_POST['text_val']);
                $psh->setAddText($_POST['text_link_label']);
                $psh->setAddValue($_POST['text_link_val']);
                $em->flush();
            }

            $pushesArr = [];
            $pushes = $em->getRepository('ServiceServiceBundle:PushText')->findAll();
            foreach($pushes as $push){
                $p = [];
                $p['id'] = $push->getId();
                $p['label'] = $push->getLabel();
                $p['value'] = $push->getValue();
                $p['link_text'] = $push->getAddText();
                $p['link_val'] = $push->getAddValue();
                $pushesArr[] = $p;
            }

            return $this->render('ServiceServiceBundle:Service:push.html.php', [
                'pushes' => $pushesArr
            ]);

        }
        
        
        
        
        

        
    }


    public function getPolitics(){
        $path = '/var/www/html/TRVL-temp/app/Resources/politika.html';
        $text = file_get_contents($path);
        return ['text' => $text];
    }


    public function getRules(){
        $path = '/var/www/html/TRVL-temp/app/Resources/pravila.html';
        $text = file_get_contents($path);
        return ['text' => $text];
    }



    public function getUserOKPhotos($requestData){
        $token = $requestData['token'];
        $app_key = $this->container->getParameter('service_service.ok_app_key');
        $app_secret_key = $this->container->getParameter('service_service.ok_app_secret_key');
        $sig = md5($token.$app_secret_key);
        $sig = md5('application_key='.$app_key.'format=jsonmethod=photos.getPhotosuid='.$requestData['id'].''.$app_secret_key);
        $ok_response = file_get_contents('https://api.ok.ru/fb.do?application_key='.$app_key.'&format=json&method=photos.getPhotos&uid='.$requestData['id'].'&sig='.$sig);
        $ok_response_decoded = (array)json_decode($ok_response);

        $links = [];
        foreach($ok_response_decoded['photos'] as $obj){
            $links[] = $obj->pic640x480;
        }
        return $links;
    }


    public function getMyPhotos($requestData){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['appId' => $requestData['id'], 'appType' => $requestData['app_type']]);
        
        $photosArr = [];
        /** @var Photo $photo */
        foreach($user->getPhotos() as $photo)
            $photosArr[] = $photo->getId();

        return $photosArr;
    }

    public function getUserPhotos($requestData){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['id' => $requestData['user_id']]);

        $photosArr = [];
        /** @var Photo $photo */
        foreach($user->getPhotos() as $photo)
            $photosArr[] = $photo->getId();

        return $photosArr;
    }

    public function savePhoto($requestData){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['appId' => $requestData['id'], 'appType' => $requestData['app_type']]);

        $p = $user->getPhotos()->count();
        $img = $requestData['image'];
        $link = $this->saveImage($img, $user->getProfile()->getId().$p);

        $description = $requestData['description'];

        $photo  = new Photo();
        $photo->setImage($link);
        $photo->setDescription($description);
        $photo->setUploaded(new \DateTime());
        $photo->setUser($user);
        $em->persist($photo);
        $em->flush();

        return [
            'id' => $photo->getId(),
            'link' => $photo->getImage(),
            'description' => $photo->getDescription(),
            'uploaded' => $photo->getUploaded()->format('Y-m-d H:i:s')
        ];

    }

    public function getUserPhotoByID($requestData){
        $id = (int)$requestData['photo_id'];
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Photo $photo */
        $photo = $em->getRepository('ServiceServiceBundle:Photo')->findOneBy(['id' => $id]);

        return [
            'id' => $photo->getId(),
            'link' => $photo->getImage(),
            'description' => $photo->getDescription(),
            'uploaded' => $photo->getUploaded()->format('Y-m-d H:i:s')
        ];
    }


    public function sendFeedback($requestData){

        $data = json_decode($requestData['data']);

        if(is_array($data) && count($data) == 1)
            $data = array_shift($data);


        $message = 'Новое обращение в Traveltogether'."\n";;
        $message .= 'Имя: '.$data->name."\n";
        $message .= 'Email: '.$data->email."\n";
        $message .= 'Текст: '.$data->text."\n";

        $headers = "From: admin@traveltogether.ru\r\n";

        mail('oviktorr1988@gmail.com','Новое обращение в Traveltogether',$message,$headers);


//        SUtils::trace($data);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();


        $feedback = new Feedback();

        $feedback->setName($data->name);
        $feedback->setEmail($data->email);
        $feedback->setText($data->text);
        $feedback->setCreated(new \DateTime());

        $em->persist($feedback);
        $em->flush();

        //todo mail to admin maybe or save to db or both...
        //mail();

        return $data;

    }


    public function likeUser($requestData){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['appId' => $requestData['id'], 'appType' => $requestData['app_type']]);
        $targetUserID = $requestData['user_to'];
        $targetUser = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['id' => $targetUserID]);

        $likeExists = $em->getRepository('ServiceServiceBundle:Like')->findOneBy(['user_from' => $user->getId(), 'user_to' => $targetUser->getId()]);

        $return = 'liked';
        if($likeExists){
            $em->remove($likeExists);
            $em->flush();
            $return = 'unliked';
        }else{

            $like = new Like();
            $like->setUserFrom($user);
            $like->setUserTo($targetUser);
            $like->setCreated(new \DateTime());

            $em->persist($like);
            $em->flush();
            

            if(!empty($targetUser->getDeviceToken())){
                $pushTextObj = $em->getRepository('ServiceServiceBundle:PushText')->findOneBy(['id' => 1]);
                $pushText = $pushTextObj->getValue();
                $pushText = str_replace($pushText,'#VAR#',$user->getProfile()->getName());
                $this->sendPush($targetUser, $pushText);
            }

        }

        return $return;
    }

    public function getLikedCountByID($requestData){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['id' => $requestData['user_id']]);

        /** @var Like[] $likes */
        $likes = $em->getRepository('ServiceServiceBundle:Like')->findBy(['user_to' => $user->getId()]);
        $count = 0;
        foreach($likes as $like)
            $count++;
        return ['count' => $count];

    }

    public function getLikedCountMe($requestData){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['appId' => $requestData['id'], 'appType' => $requestData['app_type']]);

        /** @var Like[] $likes */
        $likes = $em->getRepository('ServiceServiceBundle:Like')->findBy(['user_to' => $user->getId()]);
        $count = 0;
        foreach($likes as $like)
            $count++;
        return ['count' => $count];

    }

    public function getMutualLikes($requestData){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['appId' => $requestData['id'], 'appType' => $requestData['app_type']]);

        /** @var Like[] $likesToMe */
        $likesToMe = $em->getRepository('ServiceServiceBundle:Like')->findBy(['user_to' => $user->getId()]);

        /** @var Like[] $likesFromMe */
        $likesFromMe = $em->getRepository('ServiceServiceBundle:Like')->findBy(['user_from' => $user->getId()]);

        $mutualLikesArr = [];

        foreach($likesFromMe as $like){
            foreach($likesToMe as $likeToMe){
                if($like->getUserTo()->getId() == $likeToMe->getUserFrom()->getId()){
                    $ml = [];
                    $ml['uid'] = $like->getUserTo()->getId();
                    $ml['date'] = $like->getCreated()->format('Y-m-d H:i:s');
                    $ml['name'] = $like->getUserTo()->getProfile()->getName();
                    $ml['chat_id'] = $like->getUserTo()->getChatId();
                    $ml['ava'] = $this->getUserImage($like->getUserTo()->getId());

                    $mutualLikesArr[] = $ml;
                }
            }
        }

        return $mutualLikesArr;
    }

    /**
     * @param $requestData
     */
    public function getWhoLikedMe($requestData){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['appId' => $requestData['id'], 'appType' => $requestData['app_type']]);

        /** @var Like[] $likes */
        $likes = $em->getRepository('ServiceServiceBundle:Like')->findBy(['user_to' => $user->getId()]);

        $result = [];

        /** @var Like $like */
        foreach($likes as $like) {
            $data = [];
            $data['uid'] = $like->getUserFrom()->getId();
            $data['ava'] = $this->getUserImage($like->getUserFrom()->getId());
            $data['date'] = $like->getCreated()->format('Y-m-d H:i:s');
            $data['chat_id'] = $like->getUserTo()->getChatId();

            $result[] = $data;
        }

        return $result;

    }

    public function getWhoILiked($requestData){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['appId' => $requestData['id'], 'appType' => $requestData['app_type']]);
        /** @var Like[] $likes */
        $likes = $em->getRepository('ServiceServiceBundle:Like')->findBy(['user_from' => $user->getId()]);

        $userIDArr = [];

        /** @var Like $like */
        foreach($likes as $like)
            $userIDArr[] = $like->getUserTo()->getId();

        return $userIDArr;
    }


    public function chatRegister($requestData){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['appId' => $requestData['id'], 'appType' => $requestData['app_type']]);
        $chatId = (int)$requestData['chat_id'];
        $user->setChatId($chatId);

        $em->flush();

        return [
            'log' => $user->getAppId().$user->getAppType(),
            'pass' => $user->getChatPass(),
            'id' => $user->getChatId()
        ];
    }

    public function getUserProfile($requestData){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['id' => (int)$requestData['user_id']]);
        if(!$user) return ['User not found'];
        $userProfile = $user->getProfile();
        if(!$userProfile) return ['User profile not found'];

        return [
            'userID' => $user->getAppId(),
            'id' => $user->getId(),
            'name' => $userProfile->getName(),
            'age' => $userProfile->getAge(),
            'sex' => $userProfile->getSex(),
            'city' => $userProfile->getCity(),
            'appearance' => $userProfile->getAppearance(),
            'aboutMe' => $userProfile->getAbout(),
            'wannaCommunicate' => $userProfile->getWannaCommunicate(),
            'findCompanion' => $userProfile->getFindCompanion(),
            'findCouple' => $userProfile->getFindCouple(),
            'findFriends' => $userProfile->getFindFriends(),
            'free' => $userProfile->getFree(),
            'orientation' => $userProfile->getOrientation(),
            'chat_id' => $user->getChatId(),
            'chat_pass' => $user->getChatPass(),
            'ava' => $this->getUserImage($requestData['user_id'])
        ];
    }

    public function getUsersProfiles($requestData){
        $userIDs = $requestData['user_ids'];
        $userIDsArr = explode(',',$userIDs);
        foreach($userIDsArr as $key => $idArr)
            if(empty($idArr))
                unset($userIDsArr[$key]);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var User[] $users */
        $users = $em->getRepository('ServiceServiceBundle:User')->findBy(['chatId' => $userIDsArr]);

        $profilesArr = [];
        foreach($users as $user){
            $userProfile = $user->getProfile();
            $pr = [];
            $pr['name'] = $userProfile->getName();
            $pr['age'] = $userProfile->getAge();
            $pr['ava'] = $this->getUserImage($user->getId());
            $pr['chat_id'] = $user->getChatId();
            $profilesArr[] = $pr;
        }
        return $profilesArr;
    }



    public function getUserFlights($requestData){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['appId' => $requestData['id'], 'appType' => $requestData['app_type']]);

        $userFlights = $user->getUserFlights();

        //todo sort novyi vverh

        $flights = [];
        /** @var UserFlight $userFlight */
        foreach($userFlights as $userFlight){
            $flight = $userFlight->getFlight();
            $fl = [];
            $fl['id'] = $flight->getId();
            $fl['type'] = $flight->getType() == 0 ? 'plane' : 'train';
            $fl['fromCode'] = $flight->getFromCode();
            $fl['toCode'] = $flight->getToCode();
            $fl['no'] = $flight->getNo();
            $fl['airlineCode'] = $flight->getAirlineCode();
            $fl['code'] = $flight->getCode();
            $fl['from'] = $flight->getFrom();
            $fl['from_city'] = $flight->getFromCity();
            $fl['to'] = $flight->getTo();
            $fl['to_city'] = $flight->getToCity();
            $fl['fromDate'] = $flight->getFromDate()->format('Y-m-d');
            $fl['fromTime'] = $flight->getFromDate()->format('H:i');
            $fl['toDate'] = $flight->getToDate()->format('Y-m-d');
            $fl['toTime'] = $flight->getToDate()->format('H:i');
            $fl['user_count'] = count($flight->getUserFlights());

            if($fl['type'] == 'plane')
                $fl['link'] = $this->createPartnerLink($fl['fromDate'],$fl['from'],$fl['to']);
            else
                $fl['link'] = $this->createPartnerTrainLink($flight->getFromDate()->format('dmY'),$fl['from'],$fl['to']);

            $flights[] = $fl;

        }

        usort($flights,function($a,$b){
            if ($a['id'] == $b['id'])
                return 0;
            return ($a['id'] < $b['id']) ? 1 : -1;
        });

        return $flights;


    }

    public function getCities(){
        $em = $this->getDoctrine()->getManager();
        $cities = $em->getRepository('ServiceServiceBundle:City')->findBy([], ['name_ru' => 'asc']);
        $citiesArr = [];
        /** @var City $city */
        foreach($cities as $city){
            $c = [];
            $c['id'] = $city->getId();
            $c['name'] = $city->getName();
            $c['code'] = $city->getCode();
            $c['country_code'] = $city->getCountryCode();
            $c['name_ru'] = $city->getNameRu();
            $c['country'] = $city->getCountry()->getRuName();
            $citiesArr[] = $c;
        }

        return $citiesArr;
    }

    public function auth($requestData){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['appId' => $requestData['id'], 'appType' => $requestData['app_type']]);

        if(!$user)
            return $this->registration($requestData);
        else{
            if($this->authToken($user, $requestData, $em)) {

                if(!empty($requestData['device_token'])) {
                    $user->setDeviceToken($requestData['device_token']);
                    $em->flush();
                }
                return ['message' => 'user authenticated', 'data' =>
                    [
                        'id' => $user->getId(),
                        'token' => $user->getToken(),
                        'app_type' => $user->getAppType(),
                        'chat_id' => $user->getChatId(),
                        'chat_pass' => $user->getChatPass()
                    ]
                ];
            }else return ['Failed to authorize'];
        }
    }

    public function reloadData($requestData, &$resCode){
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['appId' => $requestData['id'], 'appType' => $requestData['app_type']]);
        if(!$user){
            $resCode = 'ERR';
            return ['User not found'];
        }
        /** @var Profile $userProfile */
        $userProfile = $em->getRepository('ServiceServiceBundle:Profile')->findOneBy(['userId' => $user->getId()]);
        if(!$userProfile){
            $resCode = 'ERR';
            return ['Profile not found'];
        }
        return[
            'userID' => $user->getAppId(),
            'userToken' => $user->getToken(),
            'userAppType' => $user->getAppType(),
            'banned' => $user->getBanned(),
            'name' => $userProfile->getName(),
            'age' => $userProfile->getAge(),
            'sex' => $userProfile->getSex(),
            'city' => $userProfile->getCity(),
            'appearance' => $userProfile->getAppearance(),
            'aboutMe' => $userProfile->getAbout(),
            'wannaCommunicate' => $userProfile->getWannaCommunicate(),
            'findCompanion' => $userProfile->getFindCompanion(),
            'findCouple' => $userProfile->getFindCouple(),
            'findFriends' => $userProfile->getFindFriends(),
            'free' => $userProfile->getFree(),
            'orientation' => $userProfile->getOrientation(),
            'chat_id' => $user->getChatId(),
            'chat_pass' => $user->getChatPass(),
            'ava' => $this->getProfileImage($requestData)
        ];
    }


    /**
     * Functions checks if query token matches user token in db
     * and if its not, trying to validate query token with social network api.
     * If new token is valid token in db refreshed
     * @param $requestData
     * $requestData:
     *      id
     *      token
     *      app_type
     * @return bool
     */
    public function authToken(User $user, $requestData, EntityManager $em){
        $token = $requestData['token'];
        if($user->getToken() == $token)
            return true;
        else{
            $tokenValid = false;
            //todo make check for other app type
            switch ($requestData['app_type']) {
                case 'vk':
                    $vk_response = file_get_contents('https://api.vk.com/method/users.isAppUser?access_token='.$token);
                    $vk_response_decoded = (array)json_decode($vk_response);
                    if (isset($vk_response_decoded['response']))
                        $tokenValid = true;
                    break;
                case 'fb':
                    $fb_response = file_get_contents('https://graph.facebook.com/v2.8/me?access_token='.$token.'&debug=all&fields=id&format=json&method=get&pretty=0&suppress_http_code=1');
                    $fb_response_decoded = (array)json_decode($fb_response);
                    if (isset($fb_response_decoded['id']) && $fb_response_decoded['id'] == $user->getAppId())
                        $tokenValid = true;
                    break;
                case 'in':
                    $in_response = file_get_contents('https://api.instagram.com/v1/users/self?access_token='.$token);
                    $in_response_decoded = (array)json_decode($in_response);

                    if (isset($in_response_decoded['data']->id) && $in_response_decoded['data']->id == $user->getAppId())
                        $tokenValid = true;
                    break;
                case 'ok':
                    $app_key = $this->container->getParameter('service_service.ok_app_key');
                    $app_secret_key = $this->container->getParameter('service_service.ok_app_secret_key');
                    $sig = md5($token.$app_secret_key);
                    $sig = md5('application_key='.$app_key.'format=jsonmethod=users.getCurrentUser'.$sig);
                    $ok_response = file_get_contents('https://api.ok.ru/fb.do?application_key='.$app_key.'&format=json&method=users.getCurrentUser&sig='.$sig.'&access_token='.$token);
                    $ok_response_decoded = (array)json_decode($ok_response);

                    if (isset($ok_response_decoded['uid']) && $ok_response_decoded['uid'] == $user->getAppId())
                        $tokenValid = true;
                    break;
                default:
                    return false;
            }
            if($tokenValid){
                $user->setToken($token);
                $em->flush();
                return true;
            }else return false;
        }
    }

    /**
     * Checks if query token matches user token in db
     * @param $requestData
     * @return array|bool
     */
    public function checkToken($requestData){
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['appId' => $requestData['id'], 'appType' => $requestData['app_type']]);
        if(!$user) return ['User not found'];
        return $user->getToken() != $requestData['token'] ? false : true;
    }

    public function saveProfile($requestData){

        $data = (array)json_decode($requestData['data']);
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['appId' => $requestData['id'], 'appType' => $requestData['app_type']]);
        if(!$user) return ['User not found'];
        /** @var Profile $userProfile */
        $userProfile = $em->getRepository('ServiceServiceBundle:Profile')->findOneBy(['userId' => $user->getId()]);

        $created = false;
        if(!$userProfile) {
            $userProfile = new Profile();
            $userProfile->setUserId($user->getId());
            $created = true;
        }

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

        return [
            'message' => $created ? 'User profile created' : 'User profile saved',
            'last_visit' => $userProfile->getLastVisit(),
            'name' => $userProfile->getName(),
            'age' => $userProfile->getAge(),
            'sex' => $userProfile->getSex(),
            'city' => $userProfile->getCity(),
            'appearance' => $userProfile->getAppearance(),
            'aboutMe' => $userProfile->getAbout(),
            'wannaCommunicate' => $userProfile->getWannaCommunicate(),
            'findCompanion' => $userProfile->getFindCompanion(),
            'findCouple' => $userProfile->getFindCouple(),
            'findFriends' => $userProfile->getFindFriends(),
            'orientation' => $userProfile->getOrientation(),
            'image' => $userProfile->getImage()
        ];
    }


    public function saveProfileImage($requestData){
        //$img = '/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAIBAQIBAQICAgICAgICAwUDAwMDAwYEBAMFBwYHBwcGBwcICQsJCAgKCAcHCg0KCgsMDAwMBwkODw0MDgsMDAz/2wBDAQICAgMDAwYDAwYMCAcIDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAz/wAARCAAcADUDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD9/KK+EP8Agpl/wXz+HP8AwSp/ax8DfDn4keEPGc2i+MtFOsSeKNMhSW20/NxJAsflMVaUqYy8mxtyK8RCOXwPb/8Ah7F+yz/0ct8AP/Dh6R/8kUAfQFFfNvjH/gsb+yf4F8LX+sXv7SPwSntNNhaeWPT/ABlYajdOq8kR29vLJNK3okaMx7A18G/Fv/g7ItvHX7QGreBf2Wf2evG/7TcGm6Uuof2xorahaOQRGJJBp406W58iKSaONpJPKy5wOGR2AP0z/bR/a98H/sF/sxeLPi149fUU8KeDoIprwWFv9oupWlnjt4o40JUF3mljQbmVQWyWABI+U/8Ag3G/4KGfFD/gpj+wz4l+InxWGnHWbfx1qGk6bJY6f9igewS2s5owoHDhJJ5o9/JIiAYlgxP5+fEP9kf/AIKQf8F+PGPhTTPjfps37NXwK1fTJRqNvpmYbWZone4ge+0ObUftk9w1xHbqBP5aRCNZFUMP3n7j/swfA+3/AGZP2afh58NrO/m1W0+HvhnTfDUF7NGI5LxLK1itllZQSFZhGGIBwCaAO5ooooA8o/bH/Yf+Fn/BQH4RJ4E+L3hK38Y+For+LVIrOS8ubNobqJXVJUlt5I5UYLJIvyuMq7A5BIr5X/4hcf2FP+iG/wDl5+IP/k6vv+igD4A/4hcf2FP+iG/+Xn4g/wDk6vqz9nb9h74N/sj7m+GHws8AeArmWyj0+e80PQbazvLyBMbUnuEQSzcqCTIzEt8xJPNep0UAFFFFABRRRQB//9k=';

        $img = $requestData['image'];

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['appId' => $requestData['id'], 'appType' => $requestData['app_type']]);
        $userProfile = $user->getProfile();

        $link = $this->saveImage($img, $userProfile->getId());

        $oldImage = $userProfile->getImage();
        if(!empty($oldImage))
            unlink($oldImage);

        $userProfile->setImage($link);
        $em->flush();

        return [
            'last_visit' => $userProfile->getLastVisit(),
            'name' => $userProfile->getName(),
            'age' => $userProfile->getAge(),
            'sex' => $userProfile->getSex(),
            'city' => $userProfile->getCity(),
            'appearance' => $userProfile->getAppearance(),
            'aboutMe' => $userProfile->getAbout(),
            'wannaCommunicate' => $userProfile->getWannaCommunicate(),
            'findCompanion' => $userProfile->getFindCompanion(),
            'findCouple' => $userProfile->getFindCouple(),
            'findFriends' => $userProfile->getFindFriends(),
            'orientation' => $userProfile->getOrientation(),
            'image' => $userProfile->getImage()
        ];

    }

    public function getProfileImage($requestData){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['appId' => $requestData['id'], 'appType' => $requestData['app_type']]);
        $userProfile = $user->getProfile();

        if(!$userProfile || empty($userProfile->getImage()))
            return null;

        $imgPath = $userProfile->getImage();
        $data = file_get_contents($imgPath);
        $base64 = base64_encode($data);

        return $base64;
    }

    public function getUserImage($id){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['id' => $id]);

        if(!$user) return null;

        $userProfile = $user->getProfile();

        if(!$userProfile || empty($userProfile->getImage()))
            return null;

        $imgPath = $userProfile->getImage();
        $data = file_get_contents($imgPath);
        $base64 = base64_encode($data);

        return $base64;
    }

    public function getUserPhotoContentsByURL($requestData){

        $url = $requestData['url'];
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Photo $image */
        $image = $em->getRepository('ServiceServiceBundle:Photo')->findOneBy(['image' => $url]);

        if(!$image) return ['Image not found'];

        $data = file_get_contents($url);
        $base64 = base64_encode($data);

        return $base64;
    }


    public function getFlightDetails($requestData){

        $code = strtoupper(str_replace(' ','',trim($requestData['code'])));
        if(empty($code)) return ['Flight not found'];


        $data = json_decode($requestData['data']);
        if(empty($data)) return ['Empty data'];
        $data = (array)$data;
        if($data['type'] == 'plane')
            $flights = $this->getFlightsFromData($requestData);
        else
            $flights = $this->getTrainsFromData($requestData);


//        SUtils::dump($code);
//        SUtils::trace($flights);

//        $flights = $this->getFlightsFromData($requestData);

        if(empty($flights) || $flights[key($flights)] == 'Empty data') return ['Flight not found'];




        foreach($flights as $flight) {

//            SUtils::dump($flight);

//            if($data['type'] == 'train')
//                $fCode = $flight['no'];
//            else
            $fCode = $flight['code'];

//            SUtils::dump($data);
//            SUtils::dump($fCode);
//            SUtils::dump($code);

            if ($fCode == $code)
                return $flight;

        }

        return ['Flight not found'];
    }

    /**
     *
     * @param $requestData
     * @return array
     */
    public function getFlightCodesFromData($requestData){

        $data = json_decode($requestData['data']);
        if(empty($data)) return ['Empty data'];
        $data = (array)$data;
        if($data['type'] == 'plane')
            $flights = $this->getFlightsFromData($requestData);
        else
            $flights = $this->getTrainsFromData($requestData);

//        SUtils::trace($flights);

        $flightCodes = [];
        foreach($flights as $flight)
            $flightCodes[] = $flight['airlineCode'].$flight['no'];
        return $flightCodes;
    }


    /**
     * @param $requestData
     * @return array
     */
    public function getFlightCodesWithDateFromData($requestData){

        $data = json_decode($requestData['data']);
        if(empty($data)) return ['Empty data'];
        $data = (array)$data;
        if($data['type'] == 'plane')
            $flights = $this->getFlightsFromData($requestData);
        else
            $flights = $this->getTrainsFromData($requestData);
        $flightCodes = [];
        foreach($flights as $flight){
            $fl = [];
            $fl['code'] = $flight['airlineCode'] . $flight['no'];
            if($data['type'] == 'plane')
                $fl['time'] =  substr($flight['fromTime'], 0, strrpos($flight['fromTime'], ':'));
            else {
                $fl['time'] = substr($flight['fromTime'], strrpos($flight['fromTime'], ' '));
                $fl['time'] = substr($fl['time'], 0, strrpos($fl['time'], ':'));
            }
            $flightCodes[] = $fl;
        }
        return $flightCodes;
    }



    /**
     *
     * @param $requestData
     * @return array
     */
    public function getTrainsFromData($requestData){
        $data = json_decode($requestData['data']);
        if(empty($data)) return ['Empty data'];
        $data = (array)$data;

        $date = $data['date'];
        $from = strtoupper($data['from']);
        $to = strtoupper($data['to']);

        $yandexApiKey = $this->container->getParameter('service_service.yandex_api_key');

        $yandexQuery = 'https://api.rasp.yandex.net/v1.0/search/?apikey='.$yandexApiKey.'&format=json&from='.$from.'&to='.$to.'&lang=ru&date='.$date.'&transport_types=train&system=iata';
        $yaResponse = json_decode(file_get_contents($yandexQuery));


//        SUtils::trace($yaResponse);

        $yaCodes = [];

        $yaFlights = [];
        foreach($yaResponse->threads as $key => $thread){

            if(!in_array($thread->thread->carrier->code,$yaCodes)) {
                $yaCodes[] = $thread->thread->carrier->code;

//            $no = str_replace(' ','',str_replace($thread->carrier->codes->iata,'',$thread->number));
//            $no = $thread->number;


//            $station = $thread->from->code;
//            $yandexAirportQuery = 'https://api.rasp.yandex.net/v1.0/schedule/?apikey=c875b8df-2d10-4728-bd23-7bd35040ad16&format=json&station='.$station.'&lang=ru&date='.$date.'&transport_types=plane&show_systems=iata';
//            SUtils::dump($yandexAirportQuery);
//            $resp = file_get_contents($yandexAirportQuery);
//            SUtils::dump($resp);
//
//
//            $station = $thread->to->code;
//            $yandexAirportQuery = 'https://api.rasp.yandex.net/v1.0/schedule/?apikey=c875b8df-2d10-4728-bd23-7bd35040ad16&format=json&station='.$station.'&lang=ru&date='.$date.'&transport_types=plane&show_systems=iata';
//            SUtils::dump($yandexAirportQuery);
//            $resp = file_get_contents($yandexAirportQuery);
//            SUtils::dump($resp);

                $fromCode = '';
                $toCode = '';


                //$fromCity = substr($thread->from->title, 0, strpos($thread->from->title, ' '));
//            $fromCity = substr($fromCity,0,strpos($fromCity,'-'));
//            $fromCity = str_replace(' ','',$fromCity);
//            $fromCity = str_replace('-','',$fromCity);

                //$toCity = substr($thread->to->title, 0, strpos($thread->to->title, ' '));
//            $toCity = substr($toCity,0,strpos($toCity,'-'));
//            $toCity = str_replace(' ','',$toCity);
//            $toCity = str_replace('-','',$toCity);

                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();


                /** @var City $fromCityObj */
                $fromCityObj = $em->getRepository('ServiceServiceBundle:City')->findOneBy(['code' => $from]);
                if ($fromCityObj)
                    $fromCityCountry = $fromCityObj->getCountry()->getRuName();
                else
                    $fromCityCountry = '';

                /** @var City $toCityObj */
                $toCityObj = $em->getRepository('ServiceServiceBundle:City')->findOneBy(['code' => $to]);
                if ($toCityObj)
                    $toCityCountry = $toCityObj->getCountry()->getRuName();
                else
                    $toCityCountry = '';


//            SUtils::trace(json_decode($resp));

                $fromObj = new \stdClass();
                $toObj = new \stdClass();


                $fromObj->code = $fromCode;
                $fromObj->airport = $thread->from->title;
                $fromObj->city = $yaResponse->search->from->title;
                $fromObj->country = $fromCityCountry;

                $toObj->code = $toCode;
                $toObj->airport = $thread->to->title;
                $toObj->city = $yaResponse->search->to->title;
                $toObj->country = $toCityCountry;

                $fDate = new \DateTime($thread->departure);
                $tDate = new \DateTime($thread->arrival);

                $fromTime = $fDate->format('H:i:s');
                $toTime = $tDate->format('H:i:s');

                $fromDate = $fDate->format('Y-m-d');
                $toDate = $tDate->format('Y-m-d');

//                $time = new \DateTime($fromTime);
//                $time2 = new \DateTime($toTime);

//            SUtils::dump($date);


//                if ($time > $time2 || $time == $time2) {
//
//                    $toDateObj = date_create_from_format('Y-m-d', $date);
//                    $timeStamp = $toDateObj->getTimestamp();
//                    $newDate = date('Y-m-d', strtotime('+1 day', $timeStamp));
//                    $toDateObj = $newDate;
//                    $toDate = $toDateObj;
//                } else {
//                    $toDateObj = date_create_from_format('Y-m-d', $date);
//                    $toDate = $toDateObj->format('Y-m-d');
//                }

//            SUtils::dump($fromDate);
//            var_dump($toDateObj);


//            foreach($data->segments as $key2 => $segment){
//            foreach($thread as $key3 => $flight){
                $fl = [];
                $fl['fromCode'] = $from;
                $fl['toCode'] = $to;
                $fl['no'] = str_replace(' ', '', $thread->thread->number);
                $fl['airlineCode'] = $thread->thread->carrier->codes->iata;
                $fl['code'] = str_replace(' ', '', $thread->thread->number);
                $fl['from'] = $fromObj;
                $fl['to'] = $toObj;
                $fl['fromDate'] = $fromDate;
                $fl['fromTime'] = $fromTime;
                $fl['toDate'] = $toDate;
                $fl['toTime'] = $toTime;

                $fl['link'] = $this->createPartnerLink($fl['fromDate'],$fromObj->code,$toObj->code);
                $yaFlights[] = $fl;
            }
//            }
//            }
        }

//        SUtils::trace($yaFlights);
        return $yaFlights;



    }

    /**
     *
     * @param $requestData
     * @return array
     */
    public function getFlightsFromData($requestData){






//        SUtils::trace('qqq');

        $data = json_decode($requestData['data']);
        if(empty($data)) return ['Empty data'];
        $data = (array)$data;

        $date = $data['date'];
        $from = strtoupper($data['from']);
        $to = strtoupper($data['to']);

        $apiQuery =  'http://partners.ozon.travel/search_v1_0/flight/?Flight='.$from.$to.'&Date1='.$date.'&Dlts=1&OnlyDirect=true';

        $response = json_decode(file_get_contents($apiQuery));


//        $yandexQuery = 'https://api.rasp.yandex.net/v1.0/search/?apikey=c875b8df-2d10-4728-bd23-7bd35040ad16&format=json&from='.$from.'&to='.$to.'&lang=ru&[date='.$date.'&transport_types=plane&system=iata';
//        $yaResponse = json_decode(file_get_contents($yandexQuery));
//
//
//        $yaFlights = [];
//        foreach($yaResponse->threads as $key => $thread){
//
//
//            $no = str_replace(' ','',str_replace($thread->carrier->codes->iata,'',$thread->number));
//
//
//            $station = $thread->from->code;
//            $yandexAirportQuery = 'https://api.rasp.yandex.net/v1.0/schedule/?apikey=c875b8df-2d10-4728-bd23-7bd35040ad16&format=json&station='.$station.'&lang=ru&date='.$date.'&transport_types=plane&show_systems=iata';
//            SUtils::dump($yandexAirportQuery);
//            $resp = file_get_contents($yandexAirportQuery);
//            SUtils::dump($resp);
//
//            $fromCode = $resp->station->codes->iata;
//
//            $station = $thread->to->code;
//            $yandexAirportQuery = 'https://api.rasp.yandex.net/v1.0/schedule/?apikey=c875b8df-2d10-4728-bd23-7bd35040ad16&format=json&station='.$station.'&lang=ru&date='.$date.'&transport_types=plane&show_systems=iata';
//            SUtils::dump($yandexAirportQuery);
//            $resp = file_get_contents($yandexAirportQuery);
//            SUtils::dump($resp);
//
//            $toCode = $resp->station->codes->iata;
//
//
//            $fromCity = $thread->title;
//            $fromCity = substr($fromCity,0,strpos($fromCity,'-'));
//            $fromCity = str_replace(' ','',$fromCity);
//            $fromCity = str_replace('-','',$fromCity);
//
//            $toCity = $thread->title;
//            $toCity = substr($toCity,0,strpos($toCity,'-'));
//            $toCity = str_replace(' ','',$toCity);
//            $toCity = str_replace('-','',$toCity);
//
//            /** @var EntityManager $em */
//            $em = $this->getDoctrine()->getManager();
//
//
//            /** @var City $fromCityObj */
//            $fromCityObj = $em->getRepository('ServiceServiceBundle:City')->findOneBy(['name_ru' => $fromCity]);
//            if($fromCityObj)
//                $fromCityCountry = $fromCityObj->getCountry()->getRuName();
//            else
//                $fromCityCountry = '';
//
//            /** @var City $toCityObj */
//            $toCityObj = $em->getRepository('ServiceServiceBundle:City')->findOneBy(['name_ru' => $toCity]);
//            if($toCityObj)
//                $toCityCountry = $toCityObj->getCountry()->getRuName();
//            else
//                $toCityCountry = '';
//
//
////            SUtils::trace(json_decode($resp));
//
//            $fromObj = new \stdClass();
//            $toObj = new \stdClass();
//
//
//            $fromObj->code = $fromCode;
//            $fromObj->airport = $thread->from->title;
//            $fromObj->city = $fromCity;
//            $fromObj->country = $fromCityCountry;
//
//            $toObj->code = $toCode;
//            $toObj->airport = $thread->to->title;
//            $toObj->city = $toCity;
//            $toObj->country = $toCityCountry;
//
//
//
//            $fromTime = $thread->departure;
//            $toTime = $thread->arrival;
//
//            $fromDate = $date;
//
//            $time = new \DateTime($fromTime);
//            $time2 = new \DateTime($toTime);
//
//            if($time > $time2 || $time == $time2) {
//
//                $toDateObj = date_create_from_format('Y-m-d', $date);
//                $timeStamp = $toDateObj->getTimestamp();
//                $newDate = date('Y-m-d', strtotime('+1 day', $timeStamp));
//                $toDateObj = $newDate;
//            }else {
//                $toDateObj = date_create_from_format('Y-m-d', $date);
//            }
//
//            $toDate = $toDateObj->format('Y-m-d');
//
//
////            foreach($data->segments as $key2 => $segment){
////            foreach($thread as $key3 => $flight){
//            $fl = [];
//            $fl['fromCode'] = $from;
//            $fl['toCode'] = $to;
//            $fl['no'] = $no;
//            $fl['airlineCode'] = $thread->carrier->codes->iata;
//            $fl['code'] = str_replace(' ','',$thread->thread->number);
//            $fl['from'] = $fromObj;
//            $fl['to'] = $toObj;
//            $fl['fromDate'] = $fromDate;
//            $fl['fromTime'] = $fromTime;
//            $fl['toDate'] = $toDate;
//            $fl['toTime'] = $toTime;
//            $yaFlights[] = $fl;
////            }
////            }
//        }
//
//        SUtils::trace($yaFlights);


        $flights = [];
        foreach($response->data as $key => $data){
            foreach($data->segments as $key2 => $segment){
                foreach($segment->flights as $key3 => $flight){
                    $fl = [];
                    $fl['fromCode'] = $from;
                    $fl['toCode'] = $to;
                    $fl['no'] = $flight->flightLegs[0]->flightNo;
                    $fl['airlineCode'] = $flight->flightLegs[0]->airlineCode;
                    $fl['code'] = strtoupper($fl['airlineCode'].$fl['no']);
                    $fl['from'] = $flight->flightLegs[0]->from;
                    $fl['to'] = $flight->flightLegs[0]->to;
                    $fl['fromDate'] = $flight->flightLegs[0]->fromDate;
                    $fl['fromTime'] = $flight->flightLegs[0]->fromTime;
                    $fl['toDate'] = $flight->flightLegs[0]->toDate;
                    $fl['toTime'] = $flight->flightLegs[0]->toTime;

                    $fl['link'] = $this->createPartnerLink($fl['fromDate'],$fl['from']->code,$fl['to']->code);
                    $flights[] = $fl;
                }
            }
        }

//        SUtils::trace($flights);


//        SUtils::trace($flights);

        return $flights;
    }

    public function addUserToExistingFlight($requestData){
        $flightID = $requestData['flight_id'];
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['appId' => $requestData['id'], 'appType' => $requestData['app_type']]);
        /** @var Flight $flight */
        $flight = $em->getRepository('ServiceServiceBundle:Flight')->findOneBy(['id' => $flightID]);

        if(!$user || !$flight) return ['Wrong data'];

        $userFlight = $em->getRepository('ServiceServiceBundle:UserFlight')->findOneBy(['user' => $user->getId(), 'flight' => $flight->getId()]);
        if(!$userFlight) {
            $userFlight = new UserFlight();
            $userFlight->setFlight($flight);
            $userFlight->setUser($user);
            $em->persist($userFlight);
            $em->flush();

            $uFlights = $flight->getUserFlights();
            if($uFlights){
                /** @var UserFlight $uFlight */
                foreach($uFlights as $uFlight){
                    $pUser = $uFlight->getUser();
                    $dev_token = $pUser->getDeviceToken();
                    if(!empty($dev_token)){

                        $pushTextObj = $em->getRepository('ServiceServiceBundle:PushText')->findOneBy(['id' => 2]);
                        $pushText = $pushTextObj->getValue();
                        $this->sendPush($pUser, $pushText);
                    }
                }
            }
        }

        return $userFlight;

    }

    public function addUserToFlight($requestData){


        $flightData = $this->getFlightDetails($requestData);

//        SUtils::trace($flightData);

        $data = json_decode($requestData['data']);
        if(empty($data)) return ['Empty data'];
        $data = (array)$data;
        $isPlane = true;
        if($data['type'] == 'train')
            $isPlane = false;

//        SUtils::trace($flightData);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['appId' => $requestData['id'], 'appType' => $requestData['app_type']]);
        /** @var Flight $flight */
        $flight = $em->getRepository('ServiceServiceBundle:Flight')->findOneBy(['code' => $flightData['code'], 'fromDate' => new \DateTime($flightData['fromDate'].' '.$flightData['fromTime'])]);

        $existed = true;

        if(!$flight){

            $newFlight = new Flight();
            $newFlight->setCode($flightData['code']);
            $newFlight->setAirlineCode($flightData['airlineCode']);
            $newFlight->setNo($flightData['no']);

            $newFlight->setType($isPlane ? 0 : 1);

            $newFlight->setFrom($flightData['fromCode']);
            $newFlight->setTo($flightData['toCode']);

            $newFlight->setFromAirport($flightData['from']->airport);
            $newFlight->setFromCity($flightData['from']->city);
            $newFlight->setFromCode($flightData['from']->code);
            $newFlight->setFromCountry($flightData['from']->country);
//            if($isPlane)
            $newFlight->setFromDate(new \DateTime($flightData['fromDate'].' '.$flightData['fromTime']));
//            else
//                $newFlight->setFromDate(new \DateTime($flightData['fromTime']));

            $newFlight->setToAirport($flightData['to']->airport);
            $newFlight->setToCity($flightData['to']->city);
            $newFlight->setToCode($flightData['to']->code);
            $newFlight->setToCountry($flightData['to']->country);
//            if($isPlane)
            $newFlight->setToDate(new \DateTime($flightData['toDate'].' '.$flightData['toTime']));
//            else
//                $newFlight->setToDate(new \DateTime($flightData['toTime']));
//            $newFlight->setToDate(new \DateTime($flightData['toDate'].' '.$flightData['toTime']));

            $em->persist($newFlight);
            $em->flush();

            $existed = false;

            $flight = $em->getRepository('ServiceServiceBundle:Flight')->findOneBy(['code' => $flightData['code'], 'fromDate' => new \DateTime($flightData['fromDate'].' '.$flightData['fromTime'])]);
        }

        if(!$flight) return ['Flight error'];

        $userFlight = $em->getRepository('ServiceServiceBundle:UserFlight')->findOneBy(['user' => $user->getId(), 'flight' => $flight->getId()]);
        if(!$userFlight) {
            $userFlight = new UserFlight();
            $userFlight->setFlight($flight);
            $userFlight->setUser($user);
            $em->persist($userFlight);
            $em->flush();

            if($existed){
                $uFlights = $flight->getUserFlights();
                if($uFlights){
                    /** @var UserFlight $uFlight */
                    foreach($uFlights as $uFlight){

                        $pUser = $uFlight->getUser();
                        $dev_token = $pUser->getDeviceToken();
                        if(!empty($dev_token)){
                            $pushTextObj = $em->getRepository('ServiceServiceBundle:PushText')->findOneBy(['id' => 2]);
                            $pushText = $pushTextObj->getValue();
                            $this->sendPush($pUser, $pushText);
                        }
                    }
                }
            }


        }

        return $userFlight;
    }

    public function getFlightUsers($requestData){
        $em = $this->getDoctrine()->getManager();
        /** @var Flight $flight */
        $flight = $em->getRepository('ServiceServiceBundle:Flight')->findOneBy(['id' => $requestData['flight_id']]);
        /** @var User $currentUser */
        $currentUser = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['appId' => $requestData['id'], 'appType' => $requestData['app_type']]);


        if(!$flight) return [];
        $userFlights = $flight->getUserFlights();
        $flightUserIDs = [];
        /** @var UserFlight $userFlight */
        foreach($userFlights as $userFlight)
            $flightUserIDs[] = $userFlight->getUser()->getId();

        $users = $em->getRepository('ServiceServiceBundle:User')->findBy(['id' => $flightUserIDs]);
        $returnUsers = [];

        if(!$users) return [];

        /** @var User $user */
        foreach($users as $user){
            if($user->getId() != $currentUser->getId()) {
                /** @var Profile $profile */
                $profile = $em->getRepository('ServiceServiceBundle:Profile')->findOneBy(['userId' => $user->getId()]);

                if ($profile) {
                    $usr = [];
                    $usr['id'] = $user->getId();
                    $usr['name'] = $profile->getName();
                    $usr['age'] = $profile->getAge();
                    $usr['city'] = $profile->getCity();
                    $usr['chat_id'] = $user->getChatId();
                    $usr['ava'] = $this->getUserImage($user->getId());

                    $returnUsers[] = $usr;
                }
            }

        }
        return $returnUsers;
    }

    /**
     * gets flights with users on selected date and direction
     * @param $requestData
     * @return array
     */
    public function getFlightsWithUsers($requestData){

        $data = json_decode($requestData['data']);
        if(empty($data)) return ['Empty data'];

        if(is_array($data))
            $data = array_shift($data);
        $data = (array)$data;

        $from = strtoupper($data['from']);
        $to = strtoupper($data['to']);

        $em = $this->getDoctrine()->getManager();
        /** @var Flight $flight */
        $flights = $em->getRepository('ServiceServiceBundle:Flight')->getFlightsByDateAndDir($from, $to, new \DateTime($data['date']));

        /** @var User $currentUser */
        $currentUser = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['appId' => $requestData['id'], 'appType' => $requestData['app_type']]);

        if(empty($flights)) return [];

        $returnFlights = [];
        
        foreach($flights as $flight){
            if(count($flight->getUserFlights()) > 0) {

//                $break = false;
//                /** @var UserFlight[] $userFlights */
//                $userFlights = $em->getRepository('ServiceServiceBundle:UserFlight')->findBy(['flight' => $flight->getId()]);
//                if(count($userFlights) == 0) break;
//
//                if(count($userFlights) == 1){
//                    /** @var UserFlight $uFl */
//                    $uFl = array_shift($userFlights);
//                    if($uFl->getUser()->getId() == $currentUser->getId())
//                    $break = true;
//                }
//
//                if($break) break;

                $fl = [];
                $fl['id'] = $flight->getId();
                $fl['type'] = $flight->getType() ? 'train' : 'plane';
                $fl['fromCode'] = $flight->getFromCode();
                $fl['toCode'] = $flight->getToCode();
                $fl['no'] = $flight->getNo();
                $fl['airlineCode'] = $flight->getAirlineCode();
                $fl['code'] = $flight->getCode();
                $fl['from'] = $flight->getFrom();
                $fl['from_city'] = $flight->getFromCity();
                $fl['to'] = $flight->getTo();
                $fl['to_city'] = $flight->getToCity();
                $fl['fromDate'] = $flight->getFromDate()->format('Y-m-d');
                $fl['fromTime'] = $flight->getFromDate()->format('H:i');
                $fl['toDate'] = $flight->getToDate()->format('Y-m-d');
                $fl['toTime'] = $flight->getToDate()->format('H:i');
                $fl['user_count'] = count($flight->getUserFlights());

                $fl['link'] = $this->createPartnerLink($fl['fromDate'],$fl['fromCode'],$fl['toCode']);
                $returnFlights[] = $fl;
            }
        }

        return $returnFlights;
    }

    public function createPartnerLink($date, $from, $to){

        $marker = $this->container->getParameter('service_service.aviasales_marker');

//        SUtils::trace($marker,'xxx');

        $link = 'http://hydra.aviasales.ru/searches/new?origin_iata='.$from.'&destination_iata='.$to.'&depart_date='.$date.'&return_date&oneway&adults=1&children=0&infants=0&trip_class=0&marker='.$marker.'&with_request=true';

        return $link;
    }


    public function createPartnerTrainLink($date, $from, $to){

        $em = $this->getDoctrine()->getManager();
        $date = str_replace('-','',$date);
        /** @var City $cityFrom */
        /** @var City $cityTo */
        $cityFrom = $em->getRepository('ServiceServiceBundle:City')->findOneBy(['code' => $from]);
        $cityTo = $em->getRepository('ServiceServiceBundle:City')->findOneBy(['code' => $to]);

        if(!$cityFrom || !$cityTo)
            return 'no';


        $link = 'https://www.onetwotrip.com/ru/poezda/'.$cityFrom->getTrainCode().'_'.$cityTo->getTrainCode().'/?date='.$date.'&scp=60,affiliate,3886-10641-0-2&s';
        
//        SUtils::trace($link);

        return $link;
//
//
//        $q = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/Resources/station.js');
//
//        $q = json_decode($q);
//
////        SUtils::dump($q);
//
//        $i = 0;
//        $y = 0;
//
//        foreach($q as $key => $obj){
//
//            if($obj->country->en == 'Russia') {
//
//                /** @var City $city */
//                $city = $em->getRepository('ServiceServiceBundle:City')->findOneBy(['name_ru' => $obj->name->ru, 'country_code' => 'RU']);
//                if ($city) {
//                    $city->setTrainCode($key);
//                    $em->flush();
//                    $i++;
//                }
//                $y++;
//            }
//
//        }
//
//        SUtils::dump('Обновлено '.$i.' из '.$y);
//        SUtils::trace('done');

//        $marker = $this->container->getParameter('service_service.aviasales_marker');
//
////        SUtils::trace($marker,'xxx');
//
//        $link = 'http://hydra.aviasales.ru/searches/new?origin_iata='.$from.'&destination_iata='.$to.'&depart_date='.$date.'&return_date&oneway&adults=1&children=0&infants=0&trip_class=0&marker='.$marker.'&with_request=true';
//
//        return $link;
    }

    public function sendPush(User $userTo, $message){


//        $i = new \stdClass();
//        $i->type = 'text';
//        $i->text = 'test text';
//
//
//        $j = new \stdClass();
//        $j->media = [$i];
//
//        SUtils::dump($j);
//
//        $att = json_encode($j);
//
//        SUtils::dump($att);
//
////        $att = '{"media":[{"type":"text","text":"my text"}]}';
//        $secret = 'F0E47DD9BB65C81A2BE6C24A';
//
//        $sig = md5('st.attachment='.$att.$secret);
//
//
//        $str = 'https://connect.ok.ru/dk?st.cmd=WidgetMediatopicPost&st.app=1249141248&st.attachment='.$att.'&st.signature='.$sig;
//
//
//
//        echo $str;
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//        die('end e');

        $passPhrase = 'TRVL16';

        //$deviceToken = '6fe2352d7e5338344ea358da7000a6ffab72d89b5ae6e94d94d8f5e80b5e8dd6';
        $deviceToken = $userTo->getDeviceToken();
//        $deviceToken = '68d091953becf439ecefb9c969578b5da52b94c2bd74684c60410ae6b54261d9';
        $link = "ssl://gateway.sandbox.push.apple.com:2195";

        $keyPath = '/var/www/html/TRVL-temp/app/Resources/final.pem';
//        $keyPath = $_SERVER['DOCUMENT_ROOT'].'/Resources/PushCert.pem';


        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $keyPath);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passPhrase);

        $fp = stream_socket_client($link,
            $err,
            $errstr,
            60,
            STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT,
            $ctx);

//        SUtils::dump($err);
//        SUtils::dump($errstr);
//        SUtils::dump($fp);
//        var_dump($fp);

        $body['aps'] = array(
            'type' => "new",
            'alert' => $message,
            'sound' => 'default'
        );

        $payload = json_encode($body);

//        SUtils::dump($payload);

        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

        $result = fwrite($fp, $msg, strlen($msg));
        fclose($fp);

        //SUtils::trace($result);

//        die('end');


        //if (!$fp)
        //exit("Failed to connect amarnew: $err $errstr" . PHP_EOL);

        //echo 'Connected to APNS' . PHP_EOL;

        // Create the payload body
//        $body['aps'] = array(
//            'badge' => +1,
//            'alert' => $message,
//            'sound' => 'default'
//        );
//
//        SUtils::dump($body);
//
//        $payload = json_encode($body);
//
//        // Build the binary notification
//        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
//
//        // Send it to the server
//        $result = fwrite($fp, $msg, strlen($msg));
//
//        if (!$result)
//            echo 'Message not delivered' . PHP_EOL;
//        else
//            echo 'Message successfully delivered amar'.$message. PHP_EOL;
//
//        // Close the connection to the server
//        fclose($fp);



    }


    public function testPush(){
        $passPhrase = 'TRVL16';
        $message = 'test';
        //$deviceToken = '6fe2352d7e5338344ea358da7000a6ffab72d89b5ae6e94d94d8f5e80b5e8dd6';
//        $deviceToken = $userTo->getDeviceToken();
        $deviceToken = '68d091953becf439ecefb9c969578b5da52b94c2bd74684c60410ae6b54261d9';
        $link = "ssl://gateway.push.apple.com:2195";

//        $keyPath = '/var/www/html/TRVL-temp/app/Resources/final.pem';
        $keyPath = $_SERVER['DOCUMENT_ROOT'].'/Resources/final.pem';


        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $keyPath);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passPhrase);

        $fp = stream_socket_client($link,
            $err,
            $errstr,
            60,
            STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT,
            $ctx);

//        SUtils::dump($err);
//        SUtils::dump($errstr);
//        SUtils::dump($fp);
//        var_dump($fp);

        $body['aps'] = array(
            'type' => "new",
            'alert' => $message,
            'sound' => 'default'
        );

        $payload = json_encode($body);

//        SUtils::dump($payload);

        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

        $result = fwrite($fp, $msg, strlen($msg));
        fclose($fp);
        die('end');
    }

    public function loadProfile($requestData){
        $em = $this->getDoctrine()->getManager();
        /** @var Profile $userProfile */
        $userProfile = $em->getRepository('ServiceServiceBundle:User')->findBy(['user_id' => $requestData['id']]);
        return $userProfile;
    }
    
    public function registration($requestData){
        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $user->setInserted(new \DateTime());
        $user->setActive(true);
        $user->setAppId($requestData['id']);
        $user->setToken($requestData['token']);
        $user->setAppType($requestData['app_type']);
        $user->setChatPass(substr(md5($user->getAppId().$user->getAppType()),0,12));
        $user->setBanned(false);
        $user->setDeviceToken($requestData['device_token']);

        $em->persist($user);
        $em->flush();

        $userProfile = new Profile();
        $userProfile->setUserId($user->getId());
        $userProfile->setLastVisit(new \DateTime());
        $em->persist($userProfile);
        $em->flush();

        $user->setProfile($userProfile);
        $em->flush();


        return ['message' => 'user registered', 'init_credentials' => 1,
            'data' => [
                'id' => $user->getId(),
                'token' => $user->getToken(),
                'app_type' => $user->getAppType(),
                'chat_pass' => $user->getChatPass()
            ]];

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
        $user = $em->getRepository('ServiceServiceBundle:User')->findBy(['id' => $id, 'active' => '1']);
        return $user;
    }

    public function user_show_token(){
        //todo Пока пропускаю все с токенами, в старом апи видимо все инче было, чем будет
    }

    public function user_del($id){
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findBy(['id' => $id, 'active' => '1']);
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
        $user = $em->getRepository('ServiceServiceBundle:Profile')->findBy(['id' => $fields['id'], 'active' => '1']);
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
			$imgLink = $imgUploadPath.$imgName.$imgExt;
			
			if($originalName !== false)
				$imgUrl = $imgUploadPath.$originalName.$imgExt;
			else
				$imgUrl = $imgUploadPath.$imgName.$imgExt;
			
			file_put_contents($imgUrl, $imgDecoded);
		
		}catch (\Exception $e){
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
        $requestData = (array)$requestData;

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

    public function politikaAction(){
        $path = '/var/www/html/TRVL-temp/app/Resources/politika.html';
        $politics = file_get_contents($path);
        echo $politics;
        die();
    }

    public function supportAction(){
        return $this->render('ServiceServiceBundle:Service:support.html.php', []);
    }

}