<?php
/**
 * @link https://github.com/borodulin/yii2-oauth2-server
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-oauth2-server/blob/master/LICENSE
 */

namespace conquer\oauth2;

use conquer\oauth2\Oauth2Server;
use conquer\oauth2\Oauth2Exception;
use conquer\oauth2\models\Oauth2AccessToken;
use yii\base\Action;
use yii\web\Response;

/**
 * Oauth2Bearer is an action filter that supports the authentication method based on HTTP Bearer token.
 *
 * You may use Oauth2Bearer by attaching it as a behavior to a controller or module, like the following:
 *
 * ```php
 * public function behaviors()
 * {
 *     return [
 *         'bearerAuth' => [
 *             'class' => \conquer\oauth2\Oauth2Bearer::className(),
 *         ],
 *     ];
 * }
 * ```
 *
 * @author Andrey Borodulin
 */
class BearerAuth extends \yii\filters\auth\AuthMethod
{
    /**
     * @var string the HTTP authentication realm
     */
    public $realm;


    /**
     * @param \yii\base\Action $action
     * (non-PHPdoc)
     * @see \yii\filters\auth\AuthMethod::beforeAction()
     */
    public function beforeAction($action)
    {
        $response = $this->response ? : \Yii::$app->getResponse();
        
        $response->format = Response::FORMAT_JSON;

        return parent::beforeAction($action);
    }
    
    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        /* @var $oauth2Server \conquer\oauth2\Oauth2Server */
        $oauth2Server = \Yii::createObject(Oauth2Server::className());
        
        /* @var $accessToken Oauth2AccessToken */
        $accessToken = $oauth2Server->validateBearerToken();
        
        /* @var $user \yii\web\User */
        
        $identity = $oauth2Server->identity->findIdentity($accessToken->user_id);
        
        if (empty($identity))
            throw new Oauth2Exception('User is not found', self::ERROR_USER_DENIED);
        
        $user->setIdentity($identity);

        return $identity;
    }

    /**
     * @inheritdoc
     */
    public function challenge($response)
    {
        $realm =  empty($this->realm) ? $this->owner->getUniqueId() : $this->realm; 
        $response->getHeaders()->set('WWW-Authenticate', "Bearer realm=\"{$realm}\"");
    }
    
    /**
     * @inheritdoc
     */
    public function handleFailure($response)
    {
        throw new Oauth2Exception('You are requesting with an invalid credential.');
    }
}