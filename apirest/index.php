<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require '../composer/vendor/autoload.php';
require '../composer/vendor/paragonie/random_compat/psalm-autoload.php';
require_once dirname(__FILE__).'/classes/AccesoDatos.php';
require_once dirname(__FILE__).'/classes/userApi.php';
require_once dirname(__FILE__).'/classes/AuthJWT.php';
require_once dirname(__FILE__).'/classes/MWCORS.php';
require_once dirname(__FILE__).'/classes/MWAuth.php';

/* Errores
  ERROR Error: Uncaught (in promise): TypeError: Cannot read property 'jwt' of undefined

  Solucion:
  utilizar dirname(__FILE__). ya que la version del PHP instalada en la PC de donde se testeo este slim tiene v6.xx 
*/

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);


$app->group('/user', function () {
  $this->get('/', \userApi::class . ':getAll'); 
  $this->get('/{id}', \userApi::class . ':getById');
  $this->post('/insert', \userApi::class . ':insert')->add(\MWAuth::class . ':verifyUser');
  $this->post('/add', \userApi::class . ':insert')->add(\MWAuth::class . ':verifyUser');
  $this->post('/delete', \userApi::class . ':delete')->add(\MWAuth::class . ':verifyUser');
  $this->post('/delete/{id}', \userApi::class . ':borrarConID');
  $this->post('/update', \userApi::class . ':update')->add(\MWAuth::class . ':verifyUser');     
})->add(\MWCORS::class . ':enableCORS');

$app->group('/login', function () {
  $this->post('/signin', \userApi::class . ':validateUser');  
  $this->post('/signup', \userApi::class . ':registerUser'); 
 })->add(\MWCORS::class . ':enableCORS');


$app->run();