<?php

  require "vendor/autoload.php";

  $app = new \Slim\App;

  $app->get('/', function () {

    echo "hello world";

  });

  $app->get('/cincoestrellas', function () {

    echo "hoteles";

  });

  $app->run();

?>
