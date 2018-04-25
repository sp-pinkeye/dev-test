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

	$seasons = array_unique( array_column( $data, "season" ) );

}
catch( GuzzleHttp\Exception\ServerException $e ){
	$error = 1 ;
}

	//Render the template

echo $twig->render('page.html', ["error"=>$error, "episodes" => $data, "seasons" => $seasons]);


function sort_multi_array ($array, $key)
{
  $keys = array();
  for ($i=1;$i<func_num_args();$i++) {
    $keys[$i-1] = func_get_arg($i);
  }
  // create a custom search function to pass to usort
  $func = function ($a, $b) use ($keys) {
    for ($i=0;$i<count($keys);$i++) {
      if ($a[$keys[$i]] != $b[$keys[$i]]) {
        return ($a[$keys[$i]] < $b[$keys[$i]]) ? -1 : 1;
      }
    }
    return 0;
  };
  
  usort($array, $func);
  
  return $array;
} 