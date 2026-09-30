// src/assets/js/password-toggle.js
(function($) {
    'use strict';

    /**
     * Плагин для переключения видимости пароля
     */
    $.fn.passwordToggle = function(options) {
        var settings = $.extend({
            openEyeClass: 'fa-eye',
            closedEyeClass: 'fa-eye-slash'
        }, options);

        return this.each(function() {
            var $button = $(this);
            var targetId = $button.data('target');
            var $passwordInput = $('#' + targetId);

            if (!$passwordInput.length) {
                console.warn('Password input with id "' + targetId + '" not found');
                return;
            }

            $button.off('click.passwordToggle').on('click.passwordToggle', function(e) {
                e.preventDefault();

                var type = $passwordInput.attr('type');
                var newType = type === 'password' ? 'text' : 'password';
                $passwordInput.attr('type', newType);

                // Обновляем иконку
                var $icon = $button.find('i');
                if ($icon.length) {
                    if (newType === 'text') {
                        $icon.removeClass(settings.closedEyeClass)
                            .addClass(settings.openEyeClass);
                    } else {
                        $icon.removeClass(settings.openEyeClass)
                            .addClass(settings.closedEyeClass);
                    }
                }

                // Триггерим событие
                $passwordInput.trigger('password:toggle', [newType]);
            });
        });
    };

    /**
     * Инициализация всех кнопок переключения на странице
     */
    $(document).on('ready passwordtoggle:init', function() {
        $('.password-toggle-btn').passwordToggle();
    });

    /**
     * Делегированная обработка для динамически добавленных элементов
     */
    $(document).on('click', '.password-toggle-btn', function(e) {
        if (!$(this).data('passwordToggle')) {
            $(this).passwordToggle();
            $(this).click();
            e.preventDefault();
        }
    });

})(jQuery);