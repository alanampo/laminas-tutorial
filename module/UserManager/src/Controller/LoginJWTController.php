<?php

namespace Api\Controller;

use Application\Service\JWTService;
use Laminas\Authentication\Result;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\Authentication\AuthenticationService;


class LoginJWTController extends AbstractRestfulController
{
    /** @var AuthenticationService  */
    private $authenticationService;
    public function __construct($authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    /**
     * Default Action for post
     *
     * @param array $data
     * @return void
     */
    public function create(array $data)
    {
        $response = $this->getResponse();

        $response->getHeaders()->addHeaders([
            'Content-Type' => 'application/json',
        ]);

        if (empty($data['email'])) {
            $response->setStatusCode(Response::STATUS_CODE_406);

            $response->setContent(json_encode([
                "message" => 'Email non fornita',
            ]));

            return $response;
        }

        if (empty($data['password'])) {
            $response->setStatusCode(Response::STATUS_CODE_406);

            $response->setContent(json_encode([
                "message" => 'Password non fornita',
            ]));

            return $response;
        }

        $authResult = $this->authenticationService->login(
            $data['email'],
            $data['password']
        );

        if ($authResult->getCode() == Result::SUCCESS) {

            $token = JWTService::createToken($authResult->getIdentity());

            $response->setStatusCode(Response::STATUS_CODE_200);

            $response->setContent(json_encode([
                "user" => $authResult->getIdentity(),
                "token" => $token
            ]));

            return $response;
        }

        $response->setStatusCode(Response::STATUS_CODE_401);

        $response->setContent(json_encode([
            "message" => 'Credenziali non valide',
        ]));

        return $response;

    }
}