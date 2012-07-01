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
 
//$name = "mandaville";  Testing
$qry_str = "?all=0&SIMPLE=" . urlencode($name);

// get DOM from URL or file
$html = file_get_html('http://metadir.andrew.cmu.edu/ldap/search'.$qry_str);

$i=0;

//TODO: Implement a better selector
foreach($html->find('td[valign=top]') as $e){
  if(strpos($e->innertext,"Department(s) with which this person is affiliated") !== false){
    $dept = $e->parent()->next_sibling()->next_sibling()->last_child()->innertext;
    break;
  }

}

//Our response
$parameters = array();
if(isset($dept) && $dept != ""){
  $parameters['html'] = "<p>".htmlentities($dept)." @ CMU</p>";
  $parameters['css'] = "p{margin:0; padding:0; color:#444; font-size: 13px; }";
  $parameters['status'] = 200;
}
else{
  $parameters['html'] = "";
  $parameters['css'] = "";  
  $parameters['status'] = 404;
}

 
//We encode our response as JSON and prepend the callback to it
$object = $callback."(".json_encode($parameters).")";
echo $object;

?>
