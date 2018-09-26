<?php

  require "vendor/autoload.php";

  $app = new \Slim\App;

  $app->get('/', function () {

    echo "hello world";

  });

  $app->get('/cincoestrellas', function () {

    echo "hoteles";

  });

  $app->get('/awita', function () {

    echo "papi";

  });

  $app->get('/cat', function () {

    echo "c'est une prueve";

  });


  $app->run();

?>
