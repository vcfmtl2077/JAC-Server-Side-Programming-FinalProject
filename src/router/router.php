<?php
//get uri path array
$requestURI = explode('/', $_SERVER['REQUEST_URI']);
$scriptName = explode('/', $_SERVER['SCRIPT_NAME']);

for ($i = 0; $i < sizeof($scriptName); $i++) {
    if ($requestURI[$i] == $scriptName[$i]) {
        unset($requestURI[$i]);
    }
}
$pathArray = array_values($requestURI);

//get json body parms
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($pathArray[0]) || $pathArray[0] !== 'api') {
    echo json_encode(array("message" => "Invalid API Request.", "status" => false, "code" => "5100"));
}

if (!isset($pathArray[1]) || $pathArray[1] !== 'v1') {
    echo json_encode(array("message" => "Invalid API Version.", "status" => false, "code" => "5101"));
}


// regist your api path and controller file, append your controller to this map
$controllerRegistry = [
    'customer' => 'CustomerController',
    'user' => 'UserController',
];

$parms = array_slice($pathArray, 3);
if(array_key_exists($pathArray[2],$controllerRegistry)) {
    $controllerFile = '../api/'.$pathArray[1].'/'.$controllerRegistry[$pathArray[2]].'.php';
    $controllerName = $controllerRegistry[$pathArray[2]];
    if (file_exists($controllerFile)) {
        include($controllerFile);
        if (function_exists($controllerName)) {
            // Invoke the function with parameters
            call_user_func_array($controllerName, [$parms, $_SERVER['REQUEST_METHOD'], $data]);
        } else {
            echo json_encode(array("message" => "Invalid API Controller Function.", "status" => false, "code" => "5103"));
        }

    }else{
        echo json_encode(array("message" => "Invalid API Controller File.", "status" => false, "code" => "5104"));
    }
}else{
    echo json_encode(array("message" => "Invalid API function or wrong parameters..", "status" => false, "code" => "5105"));
}

?>