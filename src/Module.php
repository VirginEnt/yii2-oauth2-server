<?php
/**
 * @link https://github.com/virginent/yii2-oauth2-server
 * @copyright Copyright (c) 2021 Daniel Lucas
 * @license https://github.com/virginent/yii2-oauth2-server/blob/master/LICENSE
 */

namespace virginent\oauth2;

use virginent\oauth2\console\Oauth2Controller;
use yii\base\BootstrapInterface;

/**
 * @author Andrey virginent
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    public $behaviors;

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $app->controllerMap[$this->id] = [
                'class' => Oauth2Controller::class,
            ];
        }
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        if (!empty($this->behaviors)) {
            return $this->behaviors;
        } else {
            return parent::behaviors();
        }
    }
}
