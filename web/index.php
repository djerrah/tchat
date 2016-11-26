<?php
/**
 * Created by PhpStorm.
 * User: djerrah
 * Date: 26/11/16
 * Time: 11:01
 */

require dirname(__DIR__) . "/vendor/autoload.php";

use Config\App;

$app = new App();


return $app->getResponse();