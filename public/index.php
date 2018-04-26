<?php
require_once '../vendor/autoload.php';

//Load Twig templating environment
$loader = new Twig_Loader_Filesystem('../templates/');
$twig = new Twig_Environment($loader, ['debug' => true]);

//Get the episodes from the API
$client = new GuzzleHttp\Client();

$error = 0 ;
try{
	$res = $client->request('GET', 'http://3ev.org/dev-test-api/');
	$data = json_decode($res->getBody(), true);

	//Sort the episodes
	$data = sort_multi_array( $data, "season","episode");

}
catch( GuzzleHttp\Exception\ServerException $e ){
	$error = 1 ;
}

//Render the template
echo $twig->render('page.html', ["error"=>$error,"episodes" => $data]);
