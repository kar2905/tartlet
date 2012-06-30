<?php
include('simple_html_dom.php');
//Parameters in the request from Rapportive to our Raplet
$callback = $_GET['callback'];
 
if(isset($_GET['name']))
{
   $name=$_GET['name'];
}
else
{
   $name="";
}
 
if(isset($_GET['email']))
{
   $email=$_GET['email'];
}
else
{
   $email="";
}
 
if(isset($_GET['twitter_username']))
{
   $twitter=$_GET['twitter_username'];
}
else
{
   $twitter="Twitter account not found.";
}
$name = "mandaville";
$qry_str = "?all=0&SIMPLE=" . $name ;

// get DOM from URL or file
$html = file_get_html('http://metadir.andrew.cmu.edu/ldap/search'.$qry_str);
$i=0;
foreach($html->find('td[valign=top]') as $e){
  $i++;
  if($i == 4){
    $dept = $e->innertext;
    break;
  }
}
/*
$ch = curl_init();

// Set query data here with the URL
curl_setopt($ch, CURLOPT_URL, 'http://metadir.andrew.cmu.edu/ldap/search' . $qry_str); 

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, '3');
$content = trim(curl_exec($ch));
curl_close($ch);
var_dump($content);
*/ 
//Our response
$parameters = array();
$parameters['html'] = "<p>Department.</p><p>".htmlentities($dept)."</p>";
$parameters['css'] = "p{margin:0; padding:0; color:#444; font-size: 13px; }";
$parameters['status'] = 200;
 
//We encode our response as JSON and prepend the callback to it
$object = $callback."(".json_encode($parameters).")";
echo $object;

?>
