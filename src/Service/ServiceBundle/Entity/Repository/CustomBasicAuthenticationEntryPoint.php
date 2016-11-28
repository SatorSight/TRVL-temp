<?php

namespace Service\ServiceBundle\Entity\Repository;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Service\ServiceBundle\Resources\ServiceException;

class CustomBasicAuthenticationEntryPoint implements AuthenticationEntryPointInterface {

    private $realmName;

    public function __construct($realmName) {
        $this->realmName = $realmName;
    }

    public function start(Request $request, AuthenticationException $authException = null) {
        throw new ServiceException('Auth error', 401);

        /*$content = array('success' => false, 'error' => 401);

        $response = new Response();
        $response->headers->set('WWW-Authenticate', sprintf('Basic realm="%s"', $this->realmName));
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($content))
                ->setStatusCode(401);*/
       // return $response;
    }
}