<?php
/**
 * Created by PhpStorm.
 * User: famoser
 * Date: 22/05/2016
 * Time: 22:40
 */

session_start();

use Famoser\SyncApi\SyncApiApp;

$ds = DIRECTORY_SEPARATOR;
$oneUp = ".." . $ds;
$basePath = realpath(__DIR__ . "/" . $oneUp . $oneUp) . $ds;

$debugModel = !file_exists(".prod");

require '..' . $ds . '..' . $ds . 'vendor' . $ds . 'autoload.php';

$app = new SyncApiApp(
    [
        'displayErrorDetails' => $debugModel,
        'debug_mode' => $debugModel,
        'api_modulo' => 10000019,
        'db_path' => $basePath . "app" . $ds . "data" . $ds . "data.sqlite",
        'db_template_path' => $basePath . "app" . $ds . "data" . $ds . "data_template.sqlite",
        'file_path' => $basePath . "app" . $ds . "files",
        'cache_path' => $basePath . "app" . $ds . "cache",
        'log_path' => $basePath . "app" . $ds . "logs",
        'template_path' => $basePath . "app" . $ds . "templates",
        'public_path' => $basePath . "src" . $ds . "public"
    ]
);

$app->run();