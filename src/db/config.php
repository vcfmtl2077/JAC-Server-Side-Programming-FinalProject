<?php
$db_host = getenv('DB_HOST');
$db_name = getenv('DB_NAME');
$db_user = getenv('DB_USER');
// Read the password file path from an environment variable
$password_file_path = getenv('PASSWORD_FILE_PATH');

// Read the password from the file
$db_pass = trim(file_get_contents($password_file_path));

// $con=mysqli_connect("localhost", "root", "", "jac");
$con = mysqli_connect($db_host, $db_user, $db_pass) or die(" can not establish connection ");
mysqli_select_db($con, $db_name);
if(mysqli_connect_errno())
{
echo "Connection Fail".mysqli_connect_error();
}

function inputFilter($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>