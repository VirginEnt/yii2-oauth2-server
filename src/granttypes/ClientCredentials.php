<?php
/**
 * @link https://github.com/virginent/yii2-oauth2-server
 * @copyright Copyright (c) 2021 Daniel Lucas
 * @license https://github.com/virginent/yii2-oauth2-server/blob/master/LICENSE
 */

namespace virginent\oauth2\granttypes;

use virginent\oauth2\BaseModel;
use virginent\oauth2\models\AccessToken;

/**
 * @Author Daniel Lucas
 */
class ClientCredentials extends BaseModel
{
    /**
     * Value MUST be set to "client_credentials"
     * @var string
     */
    public $grant_type;

    /**
     * Access Token Scope
     * @link https://tools.ietf.org/html/rfc6749#section-3.3
     * @var string
     */
    public $scope;

    /**
     * @var string
     */
    public $client_id;

    /**
     * @var string
     */
    public $client_secret;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['grant_type', 'client_id', 'client_secret'], 'required'],
            ['grant_type', 'required', 'requiredValue' => 'client_credentials'],
            [['client_id'], 'string', 'max' => 80],
            [['client_id'], 'validateClientId'],
            [['client_secret'], 'validateClientSecret'],
            [['scope'], 'validateScope'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getResponseData()
    {
        $accessToken = AccessToken::createAccessToken([
            'client_id' => $this->client_id,
            'expires' => $this->accessTokenLifetime + time(),
            'scope' => $this->scope,
        ]);

        return [
            'access_token' => $accessToken->access_token,
            'expires_in' => $this->accessTokenLifetime,
            'token_type' => $this->tokenType,
            'scope' => $this->scope,
        ];
    }
}
