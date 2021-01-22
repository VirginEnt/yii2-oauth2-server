<?php
/**
 * @link https://github.com/virginent/yii2-oauth2-server
 * @copyright Copyright (c) 2021 Daniel Lucas
 * @license https://github.com/virginent/yii2-oauth2-server/blob/master/LICENSE
 */

namespace virginent\oauth2\granttypes;

use virginent\oauth2\BaseModel;
use virginent\oauth2\models\AccessToken;
use Yii;

/**
 * Class RefreshToken
 * @package virginent\oauth2\granttypes
 * @Author Daniel Lucas
 */
class RefreshToken extends BaseModel
{
    /**
     * @var \virginent\oauth2\models\RefreshToken
     */
    private $_refreshToken;

    /**
     * Value MUST be set to "refresh_token".
     * @var string
     */
    public $grant_type;
    /**
     * The refresh token issued to the client.
     * @var string
     */
    public $refresh_token;
    /**
     * The scope of the access request as described by Section 3.3.
     * @var string
     */
    public $scope;
    /**
     *
     * @var string
     */
    public $client_id;
    /**
     *
     * @var string
     */
    public $client_secret;

    public function rules()
    {
        return [
            [['client_id', 'grant_type', 'client_secret', 'refresh_token'], 'required'],
            [['client_id', 'client_secret'], 'string', 'max' => 80],
            [['refresh_token'], 'string', 'max' => 40],
            [['client_id'], 'validateClientId'],
            [['client_secret'], 'validateClientSecret'],
            [['refresh_token'], 'validateRefreshToken'],
        ];
    }

    /**
     * @return array
     * @throws \Exception
     * @throws \Throwable
     * @throws \virginent\oauth2\Exception
     * @throws \yii\base\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function getResponseData()
    {
        $refreshToken = $this->getRefreshToken();

        $acessToken = AccessToken::createAccessToken([
            'client_id' => $this->client_id,
            'user_id' => $refreshToken->user_id,
            'expires' => $this->accessTokenLifetime + time(),
            'scope' => $refreshToken->scope,
        ]);

        $refreshToken->delete();

        $refreshToken = \virginent\oauth2\models\RefreshToken::createRefreshToken([
            'client_id' => $this->client_id,
            'user_id' => $refreshToken->user_id,
            'expires' => $this->refreshTokenLifetime + time(),
            'scope' => $refreshToken->scope,
        ]);

        return [
            'access_token' => $acessToken->access_token,
            'expires_in' => $this->accessTokenLifetime,
            'token_type' => $this->tokenType,
            'scope' => $refreshToken->scope,
            'refresh_token' => $refreshToken->refresh_token,
        ];
    }

    /**
     * @throws \virginent\oauth2\Exception
     */
    public function validateRefreshToken()
    {
        $this->getRefreshToken();
    }

    /**
     * @return \virginent\oauth2\models\RefreshToken
     * @throws \virginent\oauth2\Exception
     */
    public function getRefreshToken()
    {
        if (is_null($this->_refreshToken)) {
            if (empty($this->refresh_token)) {
                $this->errorServer(Yii::t('virginent/oauth2', 'The request is missing "refresh_token" parameter.'));
            }
            if (!$this->_refreshToken = \virginent\oauth2\models\RefreshToken::findOne(['refresh_token' => $this->refresh_token])) {
                $this->errorServer(Yii::t('virginent/oauth2', 'The Refresh Token is invalid.'));
            }
        }
        return $this->_refreshToken;
    }

    // phpcs:disable PSR1.Methods.CamelCapsMethodName
    /**
     * @return array|mixed|string
     */
    public function getRefresh_token()
    {
        return $this->getRequestValue('refresh_token');
    }
    // phpcs:enable
}
