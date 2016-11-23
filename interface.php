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

$abc=($_GET['credentials']);
#echo $abc;

#echo "<br>";
#echo "hi";
$div = split ("\ ", $abc);

#print_r($div);

$div1=split("\-",$div[0]);

$w1=$div1[0];
#echo "<br>";
$x1=$div1[1];
$y1=$div1[2];
$z1=$div1[3];

$sa=$div1[4];

#print $sa;

$a1="in".$z1;
#echo $a1;
#echo "<br>";
$b1="out".$z1;	
#echo $b1;
#echo "<br>";

$i=0;
$inout=array();

$abb=$w1."_".$x1."_".$y1.".rrd";
#echo $abb;


$result = mysqli_query($link10,"SELECT SYSTEM_NAME FROM ASSIGNMENT1 WHERE IP='$w1' AND PORT='$x1' AND COMMUNITY='$y1'");

while($row = mysqli_fetch_array($result))
{
		$namell=$row[0];

}

#print $namell;

					#echo "Traffic Analysis for $z1";
					#echo "<br>";
					#echo "<br>";
					#echo "Daily Graph";

					$val1 =	array( "--start", "-1d 300", "--vertical-label=B/s",
													"--title=Daily Graph for Interface $z1,$sa,$namell",
													"DEF:bps1=$abb:$a1:AVERAGE",
													"DEF:bps2=$abb:$b1:AVERAGE",
													"AREA:bps1#00FF00:In traffic",
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
				
													$ret_f1 = rrd_graph("daily.png", $val1);

													if( $ret_f1 == 0 )
													{
															$err = rrd_error();
															echo "Create error: $err\n";
													}

					#echo "<br>";
					echo "<img src=img-interface.php?value1=daily.png style='float:left;margin-right:1%;'>";
					
					#echo "<br>";
					#echo "<br>";
					#echo "Weekly Graph";				

					$val2 =	array( "--start", "-1w", "--vertical-label=B/s",
													"--title=Weekly Graph for Interface $z1,$sa,$namell",
													"DEF:bps1=$abb:$a1:AVERAGE",
													"DEF:bps2=$abb:$b1:AVERAGE",
													"AREA:bps1#00FF00:In traffic",
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
				
													$ret_f2 = rrd_graph("weekly.png", $val2);

													if( $ret_f2 == 0 )
													{
															$err = rrd_error();
															echo "Create error: $err\n";
													}

					#echo "<br>";
					echo "<img src=img-interface.php?value1=weekly.png style='float:left;margin-right:1%;'>";

					#echo "<br>";
					#echo "<br>";
					#echo "<br>";
					#echo "Monthly Graph";	

				$val3 =	array( "--start", "-1m", "--vertical-label=B/s",
													"--title=Monthly Graph for Interface $z1,$sa,$namell",
													"DEF:bps1=$abb:$a1:AVERAGE",
													"DEF:bps2=$abb:$b1:AVERAGE",
													"AREA:bps1#00FF00:In traffic",
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
				
													$ret_f3 = rrd_graph("monthly.png", $val3);

													if( $ret_f3 == 0 )
													{
															$err = rrd_error();
															echo "Create error: $err\n";
													}

					#echo "<br>";
					echo "<img src=img-interface.php?value1=monthly.png style='float:left;margin-right:1%;'>";

					#echo "<br>";
					#echo "<br>";
					#echo "<br>";
					#echo "Yearly Graph";

				$val4 =	array( "--start", "-1y", "--vertical-label=B/s",
													"--title=Yearly Graph for Interface $z1,$sa,$namell",
													"DEF:bps1=$abb:$a1:AVERAGE",
													"DEF:bps2=$abb:$b1:AVERAGE",
													"AREA:bps1#00FF00:In traffic",
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
				
													$ret_f4 = rrd_graph("yearly.png", $val4);

													if( $ret_f4 == 0 )
													{
															$err = rrd_error();
															echo "Create error: $err\n";
													}

							#echo "<br>";
							echo "<img src=img-interface.php?value1=yearly.png style='float:left;margin-right:1%;'>";

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
