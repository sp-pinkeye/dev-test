<?php
require_once '../vendor/autoload.php';

session_start() ;

//Load Twig templating environment
$loader = new Twig_Loader_Filesystem('../templates/');
$twig = new Twig_Environment($loader, ['debug' => true]);

//Get the episodes from the API
$client = new GuzzleHttp\Client();
$error = 0 ;
$data = array();
$seasons = array() ;
$message = "" ;
try{

	$res = 	$client->head( 'http://3ev.org/dev-test-api/' ) ; 

	// Check cache if not do a GET
	if( isset( $_SESSION['data'] ) ){
		$data = $_SESSION['data'] ;
		$message = "Cached data is being displayed" ;
			
	}else{
		$res = $client->request('GET', 'http://3ev.org/dev-test-api/');
		
		$data = json_decode($res->getBody(), true);

		$data = sort_multi_array( $data, "season","episode");

		$_SESSION['data'] = $data ;	
	}
		
	$res = $client->request('GET', 'http://3ev.org/dev-test-api/');
		
	$data = json_decode($res->getBody(), true);
	
	$seasons = array_unique( array_column( $data, "season" ) );

}catch( Exception $e){
	$error = 1 ;
	// Clear cache - may not be necessary
	unset($_SESSION['data']) ;
}

//Render the template

echo $twig->render('page.html', ["error"=>$error, "episodes" => $data, "seasons" => $seasons, "message"=>$message ]);


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