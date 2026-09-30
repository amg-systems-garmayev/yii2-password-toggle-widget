# Yii2 Password Toggle Widget

Виджет для Yii2, добавляющий кнопку переключения видимости для полей пароля.

## Установка

Установите через Composer:

```bash
composer require garmayev/yii2-password-toggle-widget
```

## Настройка
Добавьте в конфигурацию приложения:

```php
// config/web.php
return [
    // ...
    'params' => [
        'passwordToggle' => [
            'toggleButtonClass' => 'btn btn-outline-secondary',
            'openEyeIconClass' => 'fa fa-eye',
            'closedEyeIconClass' => 'fa fa-eye-slash',
            'inputGroupClass' => 'input-group',
            'buttonWidthClass' => 'col-1', // опционально
        ],
    ],
    // ...
];
```

## Использование
### Способ 1: Использование виджета

```php
use garmayev\passwordtoggle\widgets\PasswordFieldWidget;

echo PasswordFieldWidget::widget([
    'field' => $form->field($model, 'password'),
    'fieldOptions' => ['class' => 'col-md-6'],
    'inputOptions' => [
        'class' => 'form-control',
        'placeholder' => 'Enter password',
    ],
    'buttonOptions' => [
        'class' => 'btn btn-outline-primary',
    ],
]);
```

### Способ 2: Использование кастомного поля формы
```php
use garmayev\passwordtoggle\fields\PasswordToggleField;

// В конфигурации формы
$form = ActiveForm::begin([
    'fieldClass' => PasswordToggleField::class,
]);

// Или для конкретного поля
echo $form->field($model, 'password')->passwordToggle([
    'class' => 'form-control',
    'placeholder' => 'Enter password',
]);
```

### Способ 3: Использование трейта (опционально)
```php
use garmayev\passwordtoggle\traits\PasswordToggleTrait;

class MyForm extends ActiveForm
{
    use PasswordToggleTrait;
    
    public function passwordToggleField($model, $attribute, $options = [])
    {
        // ...
    }
}
```

## Настройки
### Глобальные настройки (params)

`toggleButtonClass` - CSS класс для кнопки (по умолчанию: 'btn btn-outline-secondary')

`openEyeIconClass` - CSS класс для открытого глаза (по умолчанию: 'fa fa-eye')

`closedEyeIconClass` - CSS класс для закрытого глаза (по умолчанию: 'fa fa-eye-slash')

`inputGroupClass` - CSS класс для группы input (по умолчанию: 'input-group')

`buttonWidthClass` - CSS класс для ширины кнопки (опционально)

### Опции виджета
`field` - ActiveField объект (обязательно)

`fieldOptions` - опции для контейнера поля

`inputOptions` - опции для input элемента

`buttonOptions` - опции для кнопки переключения

`inputGroupOptions` - опции для группы input

`useFontAwesome` - использовать Font Awesome (true/false)

## Требования

- PHP >= 7.4
- Yii2 >= 2.0.0
- Font Awesome 4/5/6 (опционально)
- Bootstrap 3/4/5 (опционально)

## Лицензия
### MIT