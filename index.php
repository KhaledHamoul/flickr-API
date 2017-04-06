<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script src="https://www.google.com/jsapi"></script>
</head>
<body>

<form action="index.php" method="GET">
KEY WORD : <input type="text" name="mcle"/><br><br>
ID : <input type="text" name="num"/>
<input type="submit">
</form>
<br>
<h2>REQUEST : </h2>




<?php

include 'randomStr.php'; 

$params = array(
	'api_key'	=> 'a86a09102c9b5e270532f70b1ad84958',
	'method'	=> 'flickr.photos.search',
	'nojsoncallback'   => '1',
	'text'  =>   '',
	'format'	=> 'json',
);

if ($_SERVER["REQUEST_METHOD"] == 'GET')
{ 
	if ( isset($_GET["mcle"])&&$_GET["mcle"]!=null) $params['text']  = $_GET["mcle"];
		else $params['text']  = RandomString();

	if ( isset($_GET["num"])&&$_GET["num"]!=null) $num = $_GET["num"];
		else $num = 0 ;
} else
{
	$params['text']  = RandomString();
	$num = 0 ;
}


$encoded_params = array();

foreach ($params as $k => $v){

	$encoded_params[] = urlencode($k).'='.urlencode($v);
}


#
# appeler l'API et décoder la réponse
#

$url = "https://api.flickr.com/services/rest/?".implode('&', $encoded_params);
echo $url."<br>";

$rsp = file_get_contents($url);

$rsp_obj1 = json_decode(json_encode($rsp),true);
$rsp_obj = json_decode($rsp_obj1,true);
echo "<br><br><br><br>";
echo "<h2>TITLES OF FOUND IMAGES  ( 100 image ) : </h2>";



#
# afficher le titre de la photo (ou une erreur en cas d'échec)
#

if ($rsp_obj['stat'] == 'ok'){

	for ( $i=0 ; $i < count($rsp_obj['photos']['photo']) ; $i++)
{
	$photo_title = $rsp_obj['photos']['photo'][$i]['title'];
	echo '<span style="color:red;">PHOTO '. ($i+1)." :</span>                                        $photo_title  <br>";
}

}else{

	echo "Échec de l'appel !";
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////******************************************////////////////////////////

/////////////////////////           chercher l'url de la photo depuis son ID       //////////////////////////////////////////////////////////////////////////

$params = array(
	'api_key'	=> 'a86a09102c9b5e270532f70b1ad84958',
	'method'	=> 'flickr.photos.getInfo',
	'nojsoncallback'   => '1',
	'photo_id'	=> '',
	'format'	=> 'json',
);

$params['photo_id'] =  $rsp_obj["photos"]["photo"][$num]["id"];

$encoded_params = array();

foreach ($params as $k => $v){

	$encoded_params[] = urlencode($k).'='.urlencode($v);
}


#
# appeler l'API et décoder la réponse
#

$url = "https://api.flickr.com/services/rest/?".implode('&', $encoded_params);
$rsp = file_get_contents($url);
$rsp_obj1 = json_decode(json_encode($rsp),true);
$rsp_obj = json_decode($rsp_obj1,true);

//var_dump($rsp_obj);
$farm = $rsp_obj["photo"]["farm"];
$server = $rsp_obj["photo"]["server"];
$id = $rsp_obj["photo"]["id"] ;
$secret = $rsp_obj["photo"]["secret"];


echo "<br><br>";
echo '<a href="'.$rsp_obj["photo"]["urls"]["url"][0]["_content"].'" style="color:red">THE IMAGE LINK</a>';
echo "<br><br>";
echo '<img src="https://c'.$farm.'.staticflickr.com/'.$server.'/'.$id.'_'.$secret.'_c.jpg"'.'" style="width:500px;height:500px"/>';
echo "<br><br><br><br>";
//https://farm{farm-id}.staticflickr.com/{server-id}/{id}_{secret}.jpg
?>


</body>
</html>


