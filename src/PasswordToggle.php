<?php
namespace garmayev\passwordtoggle;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Application;

/**
 * PasswordToggle компонент
 *
 * @package yourvendor\passwordtoggle
 */
class PasswordToggle implements BootstrapInterface
{
    /**
     * @var array настройки по умолчанию
     */
    public static $defaultOptions = [
        'toggleButtonClass' => 'btn btn-outline-secondary',
        'openEyeIconClass' => 'fa fa-eye',
        'closedEyeIconClass' => 'fa fa-eye-slash',
        'inputGroupClass' => 'input-group',
        'buttonWidthClass' => '',
    ];

    /**
     * @var array конфигурация виджетов
     */
    public static $widgetConfig = [];

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        // Регистрация переводов если есть
        if (!isset($app->i18n->translations['passwordtoggle'])) {
            $app->i18n->translations['passwordtoggle'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => __DIR__ . '/messages',
                'fileMap' => [
                    'passwordtoggle' => 'passwordtoggle.php',
                ],
            ];
        }
    }

    /**
     * Возвращает настройки с учетом значений по умолчанию
     *
     * @param array $options пользовательские настройки
     * @return array
     */
    public static function getOptions($options = [])
    {
        return array_merge(static::$defaultOptions, static::$widgetConfig, $options);
    }
}