<?php
// bootstrap.php
require_once "vendor/autoload.php";

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths = array("module/Despesa/src/Despesa/Entity/");
$isDevMode = true;

// the connection configuration
$dbParams = array(
          'driver' => 'pdo_pgsql',
          'host' => 'localhost',
          'port' =>     5432,
          'dbname' =>   'despesas',
          'user' =>     'postgres',
          'password'=> '7xeiz5qw',
          'charset'=>  'UTF8'
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);
