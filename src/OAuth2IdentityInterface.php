<?php
/**
 * @link https://github.com/virginent/yii2-oauth2-server
 * @copyright Copyright (c) 2021 Daniel Lucas
 * @license https://github.com/virginent/yii2-oauth2-server/blob/master/LICENSE
 */

namespace virginent\oauth2;

use yii\web\IdentityInterface;

/**
 * Interface OAtuh2IdentityInterface
 * @package virginent\oauth2
 * @author Dmitry Fedorenko
 */
interface OAuth2IdentityInterface
{
    /**
     * Find idenity by username
     * @param string $username current username
     * @return IdentityInterface
     */
    public static function findIdentityByUsername($username);

    /**
     * Validates password
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password);
}
