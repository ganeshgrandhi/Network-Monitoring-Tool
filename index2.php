<?php

session_start();

$path=realpath(dirname(__FILE__) . '/..');
#echo $path;
$final_path="$path/db.conf";

$handle = fopen($final_path, "r");

while (!feof($handle))
         {
            $line = fgets($handle);
 
            $data = explode('"',$line);
 
                if($data[0]=='$host=')
                {
                  $host= $data[1];
                }
								elseif($data[0]=='$port=')
                {
                 $port= $data[1];
                }
								elseif($data[0]=='$database=')
                {
                 $database= $data[1];
                }
                elseif($data[0]=='$username=')
                {
                 $username= $data[1];
                }
                elseif($data[0]=='$password=')
                {
                 $password= $data[1];
                }   
         }

$link0 = mysqli_connect("$host","$username","$password");

    if($link0 === false)
    {

        die("ERROR: . " . mysqli_connect_error());

    }

$link10 = mysqli_connect("$host","$username","$password","$database");

    if($link10 === false)
    {

        die("ERROR: . " . mysqli_connect_error());

    }

	  $f1=$_GET['ip'];
    $f2=$_GET['port'];
    $f3=$_GET['community'];

    if(isset($_GET['submit']))
    {
    $sqlq="INSERT INTO DEVICES(IP, PORT, COMMUNITY) values('$f1','$f2','$f3')"; 
    mysqli_query($link10, $sqlq);
    echo "<script>alert('Credentials Inserted');</script>";
	  echo "<script>alert('Click on view the devices list');</script>";
    }
?>

<!DOCTYPE html>
<html>

<head>
<link href="style.css" rel="stylesheet">
</head>

<body>
<form method="GET">

<div style = "position: absolute;right:500px;top:80px;">
	<h1 >Enter IP</h1>
	<input type="text" name="ip"><br>
	<h1 >Enter Port</h1>
	<input type="text" name="port"><br>
	<h1 >Enter Community</h1>
	<input type="text" name="community"><br><br>
	
	<input type="submit" name="submit" value="submit">
  <br> <br>
  <a href="index1.php"> View the devices list </a> 	
</div>
		
</form>
</body>
</html>
