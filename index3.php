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

		$b=htmlspecialchars($_GET['device2']);
		
		$devices2 = split ("\-", $b);

		$x=$devices2[0];
		$y=$devices2[1];
		$z=$devices2[2];

		if(isset($_GET['delete']))
    {
		$result = mysqli_query($link10,"DELETE FROM DEVICES where IP='$x' and PORT='$y' and COMMUNITY='$z'");
		$result11 = mysqli_query($link10,"DELETE FROM ASSIGNMENT1 where IP='$x' and PORT='$y' and COMMUNITY='$z'");
    echo "<script>alert('Device removed');</script>";
	  echo "<script>alert('Click on view the devices list');</script>";
    }
?>

<html>

<head>
<link href="style.css" rel="stylesheet">
</head>

<body>
<form method="GET">

<center><h1 style="font-family:Trebuchet MS;font-size:250%">Select a device to delete</h1></center>
<div style = "position: absolute; right: 370px;top:100px;">


<?php

print "<table align=center border cellpadding=10>"; 
print "<tr>";
print "<td>IP</td>";
print "<td>PORT</td> ";
print "<td>COMMUNITY</td> ";
print "<td>SELECT</td> ";
print "<td>DELETE</td> ";
print "</tr>";


$data1 = mysqli_query( $link10,"SELECT IP,PORT,COMMUNITY FROM DEVICES") or die(mysqli_error()); 

while ($row = mysqli_fetch_array($data1))
			{

			print "<tr>"; 
			print "<td>'$row[0]'<br></td>";
			print "<td>'$row[1]'<br></td>";
			print "<td>'$row[2]'<br></td>";
			print "<td><input type='radio' name='device2' value='$row[0]-$row[1]-$row[2]'></td>";
			print "<td><input type='submit' name='delete' value='delete'></td>";
			#print "<td>'$row[0]'</td>";
			}


print"</table><br><br>";   
 
?>

<a href="index1.php"> View the devices list </a> 
		
</form>
</body>
</html>
