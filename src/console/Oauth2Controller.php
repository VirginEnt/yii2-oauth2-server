<?php
/**
 * @link https://github.com/virginent/yii2-oauth2-server
 * @copyright Copyright (c) 2021 Daniel Lucas
 * @license https://github.com/virginent/yii2-oauth2-server/blob/master/LICENSE
 */

namespace virginent\oauth2\console;

use yii\console\Controller;
use virginent\oauth2\models\AuthorizationCode;
use virginent\oauth2\models\RefreshToken;
use virginent\oauth2\models\AccessToken;

/**
 * @Author Daniel Lucas
 */
class Oauth2Controller extends Controller
{
    public function actionIndex()
    {
    }

    public function actionClear()
    {
        AuthorizationCode::deleteAll(['<', 'expires', time()]);
        RefreshToken::deleteAll(['<', 'expires', time()]);
        AccessToken::deleteAll(['<', 'expires', time()]);
    }
}
