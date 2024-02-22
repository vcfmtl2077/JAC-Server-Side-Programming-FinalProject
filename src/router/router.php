<?php
require('../api/profile.php');

$requestURI = explode('/', $_SERVER['REQUEST_URI']);
$scriptName = explode('/', $_SERVER['SCRIPT_NAME']);

for ($i= 0; $i < sizeof($scriptName); $i++) {
    if ($requestURI[$i] == $scriptName[$i]) {
        unset($requestURI[$i]);
    }
}

$command = array_values($requestURI);

switch($command[0] ?? '') {
    case 'profile':
        if (function_exists('profile') && isset($command[1])) {
            profile($command[1]);
        } else {
            echo "404 Error: Function not found or wrong parameters.";
        }
        break;
    default:
        echo "404 Error: Wrong page.";
        break;
}
?>
