<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class AppAsset
 * @package app\assets
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/bootstrap.css',
        'css/jquery.dataTables.min.css',
        'css/style.css',
        'css/dataTables.tableTools.min.css',
        'themes/blue/style.css',
    ];
    public $js = [
        'js/bootstrap.min.js',
        'js/keyword.js',
        'js/analyzer.js',
        'js/median.js',
        'js/lightsTabs.js',
        'js/jquery.dataTables.min.js',
        'js/dataTables.tableTools.min.js',
        'js/Chart.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
