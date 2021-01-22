<?php
/**
 * @link https://github.com/virginent/yii2-oauth-server
 * @copyright Copyright (c) 2021 Daniel Lucas
 * @license https://github.com/virginent/yii2-oauth-server/blob/master/LICENSE
 */

namespace virginent\oauth2;

use Yii;
use yii\base\Action;
use yii\web\Response;

/**
 * @Author Daniel Lucas
 */
class TokenAction extends Action
{
    /** Format of response
     * @var string
     */
    public $format = Response::FORMAT_JSON;

    public $grantTypes = [
        'authorization_code' => 'virginent\oauth2\granttypes\Authorization',
        'refresh_token' => 'virginent\oauth2\granttypes\RefreshToken',
        'client_credentials' => 'virginent\oauth2\granttypes\ClientCredentials',
//         'password' => 'virginent\oauth2\granttypes\UserCredentials',
//         'urn:ietf:params:oauth:grant-type:jwt-bearer' => 'virginent\oauth2\granttypes\JwtBearer',
    ];

    public function init()
    {
        Yii::$app->response->format = $this->format;
        $this->controller->enableCsrfValidation = false;
    }

    public function run()
    {
        if (!$grantType = BaseModel::getRequestValue('grant_type')) {
            throw new Exception(Yii::t('virginent/oauth2', 'The grant type was not specified in the request.'));
        }
        if (isset($this->grantTypes[$grantType])) {
            $grantModel = Yii::createObject($this->grantTypes[$grantType]);
        } else {
            throw new Exception(Yii::t('virginent/oauth2', 'An unsupported grant type was requested.'), Exception::UNSUPPORTED_GRANT_TYPE);
        }

        $grantModel->validate();

        Yii::$app->response->data = $grantModel->getResponseData();
    }
}
