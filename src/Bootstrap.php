<?php

namespace garmayev\passwordtoggle;

use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        // Можно добавить конфигурацию по умолчанию
        if (!isset($app->params['passwordToggle'])) {
            $app->params['passwordToggle'] = [
                'buttonOptions' => [
                    'class' => 'btn btn-outline-secondary',
                ],
                'inputGroupOptions' => [
                    'class' => 'input-group',
                ],
                'iconOptions' => [
                    'class' => 'fa fa-eye',
                ],
            ];
        }
    }
}