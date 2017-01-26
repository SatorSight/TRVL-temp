<?php

namespace Service\ServiceBundle\Controller;

use Doctrine\ORM\EntityManager;
use Service\ServiceBundle\Entity\City;
use Service\ServiceBundle\Entity\Flight;
use Service\ServiceBundle\Entity\Like;
use Service\ServiceBundle\Entity\Photo;
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
        $errors = RequestParser::checkApiKey($request, $this->container->getParameter('service_service.api_key'));

        $noAuthActions = ['auth', 'get_cities'];

        if(!in_array($requestData['action'], $noAuthActions) && !$errors)
            $errors = $this->checkToken($requestData) ? false : ['Token mismatch'];

        $return = [];

        $resCode = 'OK';

        if(!empty($requestData['action']) && !$errors) {

            switch ($requestData['action']){

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
            echo json_encode($errors);

        return $this->render('ServiceServiceBundle:Service:index.html.php', array(
            'result' => OutputHandler::handle(null, $return, null, '', $resCode)
        ));

//        $em = $this->getDoctrine()->getManager();
//        $airports = $em->getRepository('ServiceServiceBundle:AirportTest')->findAll();

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
            $flights[] = $fl;

        }

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
            if($this->authToken($user, $requestData, $em))
                return ['message' => 'user authenticated', 'data' =>
                    [
                        'id' => $user->getId(),
                        'token' => $user->getToken(),
                        'app_type' => $user->getAppType(),
                        'chat_id' => $user->getChatId(),
                        'chat_pass' => $user->getChatPass()
                    ]
                ];
            else return ['Failed to authorize'];
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
        $flights = $this->getFlightsFromData($requestData);

        if(empty($flights) || $flights[key($flights)] == 'Empty data') return ['Flight not found'];

        foreach($flights as $flight)
            if($flight['code'] == $code)
                return $flight;

        return ['Flight not found'];
    }

    /**
     *
     * @param $requestData
     * @return array
     */
    public function getFlightCodesFromData($requestData){
        $flights = $this->getFlightsFromData($requestData);

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
        $flights = $this->getFlightsFromData($requestData);
        $flightCodes = [];
        foreach($flights as $flight){
            $fl = [];
            $fl['code'] = $flight['airlineCode'] . $flight['no'];
            $fl['time'] =  substr($flight['fromTime'], 0, strrpos($flight['fromTime'], ':'));
            $flightCodes[] = $fl;
        }
        return $flightCodes;
    }


    /**
     *
     * @param $requestData
     * @return array
     */
    public function getFlightsFromData($requestData){
        $data = json_decode($requestData['data']);
        if(empty($data)) return ['Empty data'];
        $data = (array)$data;

        $date = $data['date'];
        $from = strtoupper($data['from']);
        $to = strtoupper($data['to']);

        $apiQuery =  'http://partners.ozon.travel/search_v1_0/flight/?Flight='.$from.$to.'&Date1='.$date.'&Dlts=1&OnlyDirect=true';

        $response = json_decode(file_get_contents($apiQuery));




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
                    $flights[] = $fl;
                }
            }
        }

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
        }

        return $userFlight;

    }

    public function addUserToFlight($requestData){

        $flightData = $this->getFlightDetails($requestData);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository('ServiceServiceBundle:User')->findOneBy(['appId' => $requestData['id'], 'appType' => $requestData['app_type']]);
        /** @var Flight $flight */
        $flight = $em->getRepository('ServiceServiceBundle:Flight')->findOneBy(['code' => $flightData['code'], 'fromDate' => new \DateTime($flightData['fromDate'].' '.$flightData['fromTime'])]);

        if(!$flight){

            $newFlight = new Flight();
            $newFlight->setCode($flightData['code']);
            $newFlight->setAirlineCode($flightData['airlineCode']);
            $newFlight->setNo($flightData['no']);

            $newFlight->setFrom($flightData['fromCode']);
            $newFlight->setTo($flightData['toCode']);

            $newFlight->setFromAirport($flightData['from']->airport);
            $newFlight->setFromCity($flightData['from']->city);
            $newFlight->setFromCode($flightData['from']->code);
            $newFlight->setFromCountry($flightData['from']->country);
            $newFlight->setFromDate(new \DateTime($flightData['fromDate'].' '.$flightData['fromTime']));

            $newFlight->setToAirport($flightData['to']->airport);
            $newFlight->setToCity($flightData['to']->city);
            $newFlight->setToCode($flightData['to']->code);
            $newFlight->setToCountry($flightData['to']->country);
            $newFlight->setToDate(new \DateTime($flightData['toDate'].' '.$flightData['toTime']));

            $em->persist($newFlight);
            $em->flush();

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
                $returnFlights[] = $fl;
            }
        }

        return $returnFlights;
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


}