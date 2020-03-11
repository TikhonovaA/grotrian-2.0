<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/960.css',
        'css/table.css',
        'css/spectrum.css',
        'css/main.css',
    ];
    public $js = [
        'js/jquery-1.11.2.min.js',
    ];
    public $img = [
        'img/panel_arrow.gif',
        'img/panel_bg.gif',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
