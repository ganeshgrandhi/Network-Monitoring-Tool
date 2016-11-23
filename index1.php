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
                 $username= $data[1];
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

#$a=htmlspecialchars($_GET['device1']);
#echo $a;
#echo "<br>";
#$devices = split ("\-", $a);
#$x=$devices[0];
#$y=$devices[1];
#$z=$devices[2];
#$inter=$devices[0]."-".$devices[1]."-".$devices[2];
#echo $inter;

		$ips=array();
		$ports=array();
		$communitys=array();
		$dev_name=array();
		$titles=array();

$result = mysqli_query($link10,"SELECT * FROM ASSIGNMENT1");

while($row = mysqli_fetch_array($result))
{

		$arr_num=array();
		$parts=array();

		$ips[]=$row[1];
		$ports[]=$row[2];
		$communitys[]=$row[3];
		$namess[]=$row[4];

		$original_namess[]=$row[5];

		$sys_name[]=$row[6];

		$devices_name=$row[1]."_".$row[2]."_".$row[3]."."."rrd";
		
		$nmd[]=$devices_name;
		$titled[]=$row[1]."-".$row[2]."-".$row[3];


}


$j=0;

#print_r($sys_name);

foreach($nmd as $nmd1)
{
		#echo $nmd1;
		$parts=array();
		$parts = preg_split('/\s/', $namess[$j]);

		$parts111 = preg_split('/\s/', $original_namess[$j]);

									$i=0;
									$items=array();
									$items1=array();
									$items2=array();

									$images=array();
									$interface_number=array();

									foreach($parts as $value)
									{
											$a="in".$value;
											$b="out".$value;		
											#$k11=$nmd1;	
															
											
											$image="weekly"."$j"."$i".".png";
											$interfaces='$opts'."$i";
											#echo $interfaces;
											#echo $a;

											$interfaces = array( "--start", "-1d", "--vertical-label=B/s",
													#"--title=$titled[$j] Interface $value,\n$parts111[$i]",
													"--title=Traffic Analysis for $titled[$j]\n\t\tInterface $value,$parts111[$i],$sys_name[$j]",
													"DEF:bps1=$nmd1:$a:AVERAGE",
													"AREA:bps1#00FF00:In traffic",
													"DEF:bps2=$nmd1:$b:AVERAGE",
													"LINE1:bps2#0000FF:Out traffic",
													"COMMENT:\\n",
													"GPRINT:bps1:AVERAGE:Avg In traffic\: %6.2lf %SBps",
													"COMMENT: ",
								         	"GPRINT:bps2:AVERAGE:Avg Out traffic\: %6.2lf %SBps\\r",
                 					"COMMENT:\\n",
								         	"GPRINT:bps1:MAX:Max In traffic\: %6.2lf %SBps",
													"COMMENT: ",
								         	"GPRINT:bps2:MAX:Max Out traffic\: %6.2lf %SBps\\r",
													"COMMENT:\\n",
								         	"GPRINT:bps1:LAST:Last In traffic\: %6.2lf %SBps",
								         	"COMMENT: ",
								         	"GPRINT:bps2:LAST:Last Out traffic\: %6.2lf %SBps\\r",
		       						);	
				
													$ret_f2 = rrd_graph("$image", $interfaces);

													if( $ret_f2 == 0 )
													{
															$err = rrd_error();
															echo "Create error: $err\n";
													}

													#echo ""

													#echo "<div class='img'>";
													#echo '<div class="img">'; 
													echo "<a href='interface.php?credentials=$titled[$j]-$value-$parts111[$i] style='float:left;margin-right:1%;'>";
													echo "<img src=image.php?value=$image>";
													echo "</a>";
													#echo '</div>';
													#echo "</div>"
													#echo "<br>";
													#echo "<br>";

													$i++;
											
									}


$j++;
}
			
?>

<!DOCTYPE html>
<html>

<head>
<link href="style.css" rel="stylesheet">
</head>

<body>
<form method="GET">
<!-- 
<br>
<img src="week.png">
<br>
<img src="week1.png">
<br>
<img src="week2.png">
-->
</form>
</body>
</html>
