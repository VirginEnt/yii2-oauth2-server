<?php
/**
 * @link https://github.com/virginent/yii2-oauth-server
 * @copyright Copyright (c) 2021 Daniel Lucas
 * @license https://github.com/virginent/yii2-oauth-server/blob/master/LICENSE
 */

namespace virginent\oauth2;

use Yii;

/**
 *
 * @see http://tools.ietf.org/html/draft-ietf-oauth-v2-20#section-4.1
 * @author Daniel Lucas
 */
class RedirectException extends Exception
{

    /**
     * @param string $redirect_uri
     * @param string $error_description (optional)
     * @param string $error A single error code
     * @param string $state Cross-Site Request Forgery
     * @see http://tools.ietf.org/html/draft-ietf-oauth-v2-20#section-4.1.2.1
     */
    public function __construct($redirect_uri, $error_description = null, $error = self::INVALID_REQUEST, $state = null)
    {
        parent::__construct($error_description, $error);

        $query = ['error' => $error];

        if ($error_description) {
            $query['error_description'] = $error_description;
        }

        if ($state) {
            $query['state'] = $state;
        }
        Yii::$app->response->redirect(http_build_url($redirect_uri, [
            'query' => http_build_query($query)
        ], HTTP_URL_JOIN_QUERY));
    }
}
