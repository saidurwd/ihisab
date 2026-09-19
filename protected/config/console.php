<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Optimo CMS',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
    ),
    // application components
    'components' => array(
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=ihisab',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
            'tablePrefix' => 'os_'
        ),
        'mailer' => array(
            'class' => 'GmailMailer',
            'username' => 'info@saidur-rahman.com',
            'password' => 'your-app-password',
            'fromEmail' => 'info@saidur-rahman.com',
            'fromName' => 'iHisab',
            'port' => 465,
            'secure' => 'ssl',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            ),
        ),
    ),
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminName' => 'Optimo CMS',
        'adminEmail' => 'info@optimosolution.com',
    ),
);