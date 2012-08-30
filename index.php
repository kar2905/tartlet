<?php
// basic sequence with LDAP is connect, bind, search, interpret search
// result, close connection

//echo "<h3>LDAP query test</h3>";
//echo "Connecting ...";
$ds=ldap_connect("ldaps://ldap.cmu.edu");  // must be a valid LDAP server!
echo "connect result is " . $ds . "<br />";

if ($ds) { 
  //  echo "Binding ..."; 
    $r=ldap_bind($ds);     // this is an "anonymous" bind, typically
                           // read-only access
    echo "Bind result is " . $r . "<br />";

    //echo "Searching for (sn=S*) ...";
    // Search surname entry
    $sr=ldap_search($ds, "dc=cmu, dc=edu", "(cn=*Kartik Mandaville*)");  
    //echo "Search result is " . $sr . "<br />";

    //echo "Number of entries returned is " . ldap_count_entries($ds, $sr) . "<br />";

    //echo "Getting entries ...<p>";
    $info = ldap_get_entries($ds, $sr);
    //echo "Data for " . $info["count"] . " items returned:<p>";

    for ($i=0; $i<$info["count"]; $i++) {
        //echo "dn is: " . $info[$i]["dn"] . "<br />";
        //echo "first cn entry is: " . $info[$i]["cn"][0] . "<br />";
        //echo "first email entry is: " . $info[$i]["mail"][0] . "<br /><hr />";
        $dept =  $info[$i]["cmudepartment"][0] ;
        //print_r($info[$i]);
        
    }

    //echo "Closing connection";
    ldap_close($ds);

} else {
    $dept = "<h4>Unable to connect to LDAP server</h4>";
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

