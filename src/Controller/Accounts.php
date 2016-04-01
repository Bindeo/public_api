<?php

namespace PublicApi\Controller;

use Bindeo\Util\ApiConnection;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use PublicApi\Entity\User;
use PublicApi\Entity\UserIdentity;
use PublicApi\Model\OAuth\OAuthRegistry;

class Accounts
{
    private $api;

    public function __construct(ApiConnection $api)
    {
        $this->api = $api;
    }

    /**
     * Create a new account
     *
     * @param Request|\Slim\Http\Request   $request
     * @param Response|\Slim\Http\Response $response
     * @param array                        $args [optional]
     *
     * @return \Slim\Http\Response
     * @throws \Exception
     */
    public function create(Request $request, Response $response, $args)
    {
        // Populate the user object and create a new account
        $user = (new User($request->getParams()))->setType(2)
                                                 ->setIp(OAuthRegistry::getInstance()->getIp())
                                                 ->setLang($request->getParam('locale'));
        $data = $this->api->postJson('account', $user->toArray());

        if ($data->getError()) {
            // Error
            throw new \Exception($data->getError()['message'], $data->getError()['code']);
        } else {
            // Correct answer
            $res = [
                'data' => [
                    'type'       => 'users',
                    'attributes' => $data->getNumRows() > 0 ? $data->getRows()[0]->setIp(null)
                                                                                 ->setPassword(null)
                                                                                 ->setIdGeonames(null)
                                                                                 ->toArray() : []
                ]
            ];

            return $response->withJson($res, 201);
        }
    }

    /**
     * Modify an account locale
     *
     * @param Request|\Slim\Http\Request   $request
     * @param Response|\Slim\Http\Response $response
     * @param array                        $args [optional]
     *
     * @return \Slim\Http\Response
     * @throws \Exception
     */
    public function modify(Request $request, Response $response, $args)
    {
        // Populate the user object and create a new account
        $user = (new User())->setIdUser(OAuthRegistry::getInstance()->getUserId())
                            ->setIp(OAuthRegistry::getInstance()
                                                 ->getIp())
                            ->setLang($request->getParam('locale'));
        $data = $this->api->putJson('account', $user->toArray());

        if ($data->getError()) {
            // Error
            throw new \Exception($data->getError()['message'], $data->getError()['code']);
        } else {
            // Correct answer
            $res = [
                'data' => [
                    'type'       => 'users',
                    'attributes' => $data->getRows()[0]->setPassword(null)->toArray()
                ]
            ];

            return $response->withJson($res, 200);
        }
    }

    /**
     * Cancel an account
     *
     * @param Request|\Slim\Http\Request   $request
     * @param Response|\Slim\Http\Response $response
     * @param array                        $args [optional]
     *
     * @return \Slim\Http\Response
     * @throws \Exception
     */
    public function delete(Request $request, Response $response, $args)
    {
        // Populate the user object and create a new account
        $user = (new User())->setIdUser(OAuthRegistry::getInstance()->getUserId())->setIp(OAuthRegistry::getInstance()
                                                                                                       ->getIp());
        $data = $this->api->deleteJson('account', $user->toArray());

        if ($data->getError()) {
            // Error
            throw new \Exception($data->getError()['message'], $data->getError()['code']);
        } else {
            // Correct answer
            return $response->withJson('', 204);
        }
    }

    /**
     * Reset an account password
     *
     * @param Request|\Slim\Http\Request   $request
     * @param Response|\Slim\Http\Response $response
     * @param array                        $args [optional]
     *
     * @return \Slim\Http\Response
     * @throws \Exception
     */
    public function resetPassword(Request $request, Response $response, $args)
    {
        // Populate the user object and create a new account
        $user = (new User($request->getParams()))->setIp(OAuthRegistry::getInstance()->getIp());
        $data = $this->api->getJson('account_password', $user->toArray());

        if ($data->getError()) {
            // Error
            throw new \Exception($data->getError()['message'], $data->getError()['code']);
        } else {
            // Correct answer
            return $response->withJson('', 204);
        }
    }

    /**
     * Modify an account password
     *
     * @param Request|\Slim\Http\Request   $request
     * @param Response|\Slim\Http\Response $response
     * @param array                        $args [optional]
     *
     * @return \Slim\Http\Response
     * @throws \Exception
     */
    public function modifyPassword(Request $request, Response $response, $args)
    {
        // Populate the user object and modify the password
        $user = (new User($request->getParams()))->setIdUser(OAuthRegistry::getInstance()->getUserId())
                                                 ->setIp(OAuthRegistry::getInstance()->getIp());

        $data = $this->api->putJson('account_password', $user->toArray());

        if ($data->getError()) {
            // Error
            throw new \Exception($data->getError()['message'], $data->getError()['code']);
        } else {
            // Correct answer
            $res = [
                'data' => [
                    'type'       => 'users',
                    'attributes' => $data->getRows()[0]->setPassword(null)->toArray()
                ]
            ];

            return $response->withJson($res, 200);
        }
    }

    /**
     * Modify an account type
     *
     * @param Request|\Slim\Http\Request   $request
     * @param Response|\Slim\Http\Response $response
     * @param array                        $args [optional]
     *
     * @return \Slim\Http\Response
     * @throws \Exception
     */
    public function changeType(Request $request, Response $response, $args)
    {
        // Populate the user object and modify the password
        $user = (new User($request->getParams()))->setIdUser(OAuthRegistry::getInstance()->getUserId())
                                                 ->setIp(OAuthRegistry::getInstance()->getIp());

        $data = $this->api->putJson('account_type', $user->toArray());

        if ($data->getError()) {
            // Error
            throw new \Exception($data->getError()['message'], $data->getError()['code']);
        } else {
            // Correct answer
            $res = [
                'data' => [
                    'type'       => 'users',
                    'attributes' => $data->getRows()[0]->setPassword(null)->toArray()
                ]
            ];

            return $response->withJson($res, 200);
        }
    }

    /**
     * Resend initial validation token
     *
     * @param Request|\Slim\Http\Request   $request
     * @param Response|\Slim\Http\Response $response
     * @param array                        $args [optional]
     *
     * @return \Slim\Http\Response
     * @throws \Exception
     */
    public function resendToken(Request $request, Response $response, $args)
    {
        // Populate the user object and create a new account
        $user = (new User($request->getParams()))->setIp(OAuthRegistry::getInstance()->getIp());
        $data = $this->api->getJson('account_password', $user->toArray());

        if ($data->getError()) {
            // Error
            throw new \Exception($data->getError()['message'], $data->getError()['code']);
        } else {
            // Correct answer
            return $response->withJson('', 204);
        }
    }

    /**
     * Get user active identities
     *
     * @param Request|\Slim\Http\Request   $request
     * @param Response|\Slim\Http\Response $response
     * @param array                        $args [optional]
     *
     * @return \Slim\Http\Response
     * @throws \Exception
     */
    public function getIdentities(Request $request, Response $response, $args)
    {
        // Populate the user object and modify the password
        $user = (new User())->setIdUser(OAuthRegistry::getInstance()->getUserId());

        $data = $this->api->getJson('account_identities', $user->toArray());

        if ($data->getError()) {
            // Error
            throw new \Exception($data->getError()['message'], $data->getError()['code']);
        } else {
            // Correct answer
            $res = ['data' => $data->toArray('user_identities'), 'total_pages' => 1];

            return $response->withJson($res, 200);
        }
    }

    /**
     * Modify or create an identity
     *
     * @param Request|\Slim\Http\Request   $request
     * @param Response|\Slim\Http\Response $response
     * @param array                        $args [optional]
     *
     * @return \Slim\Http\Response
     * @throws \Exception
     */
    public function saveIdentity(Request $request, Response $response, $args)
    {
        // Populate the user object and create a new account
        $identity = (new UserIdentity($request->getParams()))->setIp(OAuthRegistry::getInstance()->getIp())
                                                             ->setIdUser(OAuthRegistry::getInstance()->getUserId());
        $data = $this->api->putJson('account_identities', $identity->toArray());

        if ($data->getError()) {
            // Error
            throw new \Exception($data->getError()['message'], $data->getError()['code']);
        } else {
            // Correct answer
            $res = [
                'data' => [
                    'type'       => 'users',
                    'attributes' => $data->getNumRows() > 0 ? $data->getRows()[0]->setPassword(null)->toArray() : []
                ]
            ];

            return $response->withJson($res, 201);
        }
    }
}