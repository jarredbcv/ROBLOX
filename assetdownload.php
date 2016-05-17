<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
?>

<?php
 $options  = array('http' => array('user_agent' => 'Roblox/WinInet'));
 $context  = stream_context_create($options);
 
 $xml = file_get_contents('http://www.roblox.com/asset/?id='.$_GET["Id"],false,$context);
 echo htmlentities($xml);
?>
