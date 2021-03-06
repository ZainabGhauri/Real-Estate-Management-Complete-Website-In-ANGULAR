<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

$servername = "localhost";
$username = "root";
$password = "";
$mydatabase="mydbcourseswala1";

// Create connection
$conn = new mysqli($servername,$username, $password,$mydatabase);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


// Get the posted data.
$postdata = file_get_contents("php://input");

//echo $postdata;
if(isset($postdata) && !empty($postdata))
{
  // Extract the data.
  $request = json_decode($postdata);

  //echo $request;
  // Validate.
  if((int)$request->P_id < 0 || trim($request->Title) === '' || trim($request->Address) === '' || trim($request->Price)  < 0 ||   trim($request->Is_sold) === ''  )
  {
    return http_response_code(400);
  }

  // Sanitize.
  
  $P_id = mysqli_real_escape_string($conn, (int)$request->P_id);
  $Title= mysqli_real_escape_string($conn, trim($request->Title));
  $Address = mysqli_real_escape_string($conn, trim($request->Address));
  $Price= mysqli_real_escape_string($conn, trim($request->Price));
  $Is_sold= mysqli_real_escape_string($conn, trim($request->Is_sold));

  


  // Create.
  $sql = "UPDATE property SET P_id = {$P_id}, Title = '{$Title }', Address = '{$Address}' , Price ='{$Price}' , Is_sold = '{$Is_sold}'";

  if($result = $conn->query($sql))
  {
    http_response_code(201);
    $properties = [
      'P_id' => $P_id,
      'Title' => $Title,
       'Address' => $Address,
      'Price'    => $Price,
       'Is_sold' => $Is_sold
    ];
    echo json_encode($properties);
  }
  else
  {
    http_response_code(422);
  }
}
$conn->close();
?>