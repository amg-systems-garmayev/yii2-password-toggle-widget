<?php

namespace garmayev\passwordtoggle\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\InputWidget;

/**
 * Виджет поля пароля с переключателем видимости
 *
 * Пример использования:
 *
 * ```php
 * $form->field($model, 'password')->widget(PasswordFieldWidget::class, [
 *     'buttonOptions' => ['class' => 'btn btn-outline-secondary'],
 *     'inputOptions' => ['class' => 'form-control', 'placeholder' => 'Введите пароль'],
 * ]);
 * ```
 */
class PasswordFieldWidget extends InputWidget
{
    /**
     * @var array опции для кнопки переключения
     */
    public $buttonOptions = [];

    /**
     * @var array опции для группы input
     */
    public $inputGroupOptions = [];

    /**
     * @var array опции для иконки
     */
    public $iconOptions = [];

    /**
     * @var string текст для кнопки (альтернатива иконке)
     */
    public $buttonText;

    /**
     * @var bool показывать ли иконку Font Awesome
     */
    public $useFontAwesome = true;

    /**
     * @var array настройки по умолчанию
     */
    private $_defaultConfig = [
        'button' => [
            'class' => 'btn btn-outline-secondary',
            'type' => 'button',
            'title' => 'Показать/скрыть пароль',
        ],
        'icon' => [
            'class' => 'fa fa-eye',
        ],
        'inputGroup' => [
            'class' => 'input-group',
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // Устанавливаем опции по умолчанию
        $this->buttonOptions = ArrayHelper::merge($this->_defaultConfig['button'], $this->buttonOptions);
        $this->iconOptions = ArrayHelper::merge($this->_defaultConfig['icon'], $this->iconOptions);
        $this->inputGroupOptions = ArrayHelper::merge($this->_defaultConfig['inputGroup'], $this->inputGroupOptions);

        // Убедимся, что input имеет тип password
        if (!isset($this->options['type'])) {
            $this->options['type'] = 'password';
        }

        // Добавляем класс для идентификации
        if (!isset($this->options['class'])) {
            $this->options['class'] = 'form-control';
        }

        // Устанавливаем ID для поля если не задан
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        // Добавляем атрибут data-toggle для совместимости с JS
        $this->options['data-toggle'] = 'password';
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        // Регистрируем JS
        $this->registerClientScript();

        // Определяем, как рендерить: с полем формы или без
        if ($this->hasModel()) {
            return $this->renderWithModel();
        } else {
            return $this->renderWithoutModel();
        }
    }

    /**
     * Рендерит виджет для модели
     *
     * @return string
     */
    protected function renderWithModel()
    {
        // Создаем кнопку переключения
        $toggleButton = $this->renderToggleButton();

        // Создаем input с кнопкой
        $input = Html::activePasswordInput($this->model, $this->attribute, $this->options);
        $inputWithButton = Html::tag('div', $input . $toggleButton, $this->inputGroupOptions);

        return $inputWithButton;
    }

    /**
     * Рендерит виджет без модели
     *
     * @return string
     */
    protected function renderWithoutModel()
    {
        // Создаем кнопку переключения
        $toggleButton = $this->renderToggleButton();

        // Создаем input с кнопкой
        $input = Html::passwordInput($this->name, $this->value, $this->options);
        $inputWithButton = Html::tag('div', $input . $toggleButton, $this->inputGroupOptions);

        return $inputWithButton;
    }

    /**
     * Рендерит кнопку переключения
     *
     * @return string
     */
    protected function renderToggleButton()
    {
        $buttonContent = '';

        if ($this->buttonText !== null) {
            $buttonContent = Html::encode($this->buttonText);
        } elseif ($this->useFontAwesome) {
            $buttonContent = Html::tag('i', '', $this->iconOptions);
        } else {
            $buttonContent = '👁️';
        }

        // Добавляем атрибут data-target
        $this->buttonOptions['data-target'] = $this->options['id'];
        $this->buttonOptions['class'] .= ' password-toggle-btn';

        return Html::button($buttonContent, $this->buttonOptions);
    }

    /**
     * Регистрирует JavaScript
     */
    protected function registerClientScript()
    {
        $inputId = $this->options['id'];
        $iconClass = ArrayHelper::getValue($this->iconOptions, 'class', 'fa fa-eye');
        $iconOpenClass = $iconClass;
        $iconClosedClass = str_replace('fa-eye', 'fa-eye-slash', $iconClass);

        // Если не Font Awesome, используем текстовые метки
        if (!$this->useFontAwesome && $this->buttonText === null) {
            $iconOpenClass = '👁️';
            $iconClosedClass = '👁️';
        }

        $js = <<<JS
(function($) {
    // Обработчик клика по кнопке переключения
    $(document).on('click', '[data-target="{$inputId}"]', function() {
        var button = $(this);
        var input = $('#' + button.data('target'));
        var icon = button.find('i');
        
        if (input.attr('type') === 'password') {
            // Показываем пароль
            input.attr('type', 'text');
            
            // Обновляем иконку если используется Font Awesome
            if (icon.length) {
                icon.removeClass('{$iconOpenClass}').addClass('{$iconClosedClass}');
                button.attr('title', 'Скрыть пароль');
            } else if (!button.find('i').length) {
                // Если нет иконки, обновляем текст
                button.html('🙈');
                button.attr('title', 'Скрыть пароль');
            }
        } else {
            // Скрываем пароль
            input.attr('type', 'password');
            
            // Обновляем иконку если используется Font Awesome
            if (icon.length) {
                icon.removeClass('{$iconClosedClass}').addClass('{$iconOpenClass}');
                button.attr('title', 'Показать пароль');
            } else if (!button.find('i').length) {
                // Если нет иконки, обновляем текст
                button.html('👁️');
                button.attr('title', 'Показать пароль');
            }
        }
        
        // Фокус на поле ввода после переключения
        input.focus();
    });
    
    // Инициализация при загрузке страницы
    $(document).ready(function() {
        var button = $('[data-target="{$inputId}"]');
        var input = $('#' + button.data('target'));
        
        if (input.attr('type') === 'text') {
            // Если поле уже открыто (например, при редактировании)
            var icon = button.find('i');
            if (icon.length) {
                icon.removeClass('{$iconOpenClass}').addClass('{$iconClosedClass}');
                button.attr('title', 'Скрыть пароль');
            }
        }
    });
})(jQuery);
JS;

        $this->view->registerJs($js);
    }
}