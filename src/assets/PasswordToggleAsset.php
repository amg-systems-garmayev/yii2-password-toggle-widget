<?php

namespace garmayev\passwordtoggle\assets;

use yii\web\AssetBundle;

class PasswordToggleAsset extends AssetBundle
{
    public $sourcePath = '@garmayev/passwordtoggle/assets';

    public $js = [
        'js/password-toggle.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}