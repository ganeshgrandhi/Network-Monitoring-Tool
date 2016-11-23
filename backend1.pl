#!/usr/local/bin/perl

##################List of Perl Modules used###############################################
use strict;
use warnings;
use FindBin qw($Bin);
use File::Basename qw(dirname);
use File::Spec::Functions qw(catdir);
use File::fgets;			
use Data::Dumper;
use DBI;
use DBD::mysql;
use Net::SNMP;
use Net::SNMP::Interfaces;
use Net::SNMP::Interfaces::Details;
use RRD::Simple();

#######################Initialize variables to connect to MySQL database######################
my $host;				
my $port;
my $database;
my $username;
my $password;
my @row;
my @values;
my $session;
my $error;

#print $Bin, "\n";                                
my $file = dirname($Bin); 
#print "\n";
my $file_path=$file."/db.conf";

open (FILE, "$file_path") || die "File not found";

my @configuration = <FILE>;

foreach my $row (@configuration) 
{
	my @credentials = split /[="";]/,$row;
	#print "@credentials";

		if($credentials[0] eq '$host')
    {		
		$host="$credentials[2]";
    }
		if($credentials[0] eq '$port')
    {		
		$port="$credentials[2]";
    }
		if($credentials[0] eq '$database')
    {		
		$database="$credentials[2]";
    }
		if($credentials[0] eq '$username')
    {		
		$username="$credentials[2]";
    }
		if($credentials[0] eq '$password')
    {		
		$password="$credentials[2]";
    }
}

##############################Connecting to Database##################################
my $dsn = "DBI:mysql:host=$host;port=$port";
my $dbh = DBI->connect($dsn,"$username","$password") or die "Could not connect to ".$host.": ". $DBI::errstr ."\n";

#$dbh->do("Create database if not exists $database") or die "Could not create the: ".$database." error: ". $dbh->errstr ."\n";
#$dbh->disconnect();

$dsn = "DBI:mysql:database=$database;host=$host;port=$port";
$dbh = DBI->connect($dsn, "$username", "$password",{'RaiseError' => 1}) or die "Could not connect to ".$database.": ". $DBI::errstr ."\n";

my $query="CREATE TABLE IF NOT EXISTS ASSIGNMENT1 (id int (11) NOT NULL AUTO_INCREMENT,IP tinytext NOT NULL,PORT int (11) NOT NULL,COMMUNITY tinytext NOT NULL, INTERFACES VARCHAR(200), INTERFACES_NAMES VARCHAR (5800), SYSTEM_NAME VARCHAR(4000), SYSTEM_CONTACT VARCHAR(4000), SYSTEM_LOCATION VARCHAR(4000), PRIMARY KEY ( id )) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
my $query1 = $dbh->prepare( $query );
$query1->execute;

my $query2="SELECT * from DEVICES";

my $sth = $dbh->prepare( $query2 );

$sth->execute;

##################################Declaring OID's##########################################
my $OID_ifoperationalstatus = '1.3.6.1.2.1.2.2.1.8';
my $OID_iftype = '1.3.6.1.2.1.2.2.1.3';
my $OID_ifSpeed = '1.3.6.1.2.1.2.2.1.5';
my $OID_ifAdminStatus = '1.3.6.1.2.1.2.2.1.7';
my $OID_ifInOctets='1.3.6.1.2.1.2.2.1.10';
my $OID_ifOutOctets='1.3.6.1.2.1.2.2.1.16';
my $OID_ifName='1.3.6.1.2.1.2.2.1.2';
my $OID_syscontact='1.3.6.1.2.1.1.4.0';
my $OID_sysName='1.3.6.1.2.1.1.5.0';
my $OID_syslocation='1.3.6.1.2.1.1.6.0';
#my $OID_sysDescr='1.3.6.1.2.1.1.1';

my $RRD;
my %hash=();
my @octets=();

while(@row = $sth->fetchrow_array()) {

my @dsnames=();
my @filtered_indices=();
my @array=();
my @ifnames=();
my @in_traffic=();
my @out_traffic=();
my @int_names =();
my @all=();
my @adc=();
my @up=();
my $counter_interfaces;

#########################Creating session and sending get requests####################################
		my ($session, $error) = Net::SNMP->session(
         				-hostname => $row[1],
         				-port => $row[2],
         				-community => $row[3],
      					);

	 #my $result_a = $session->get_request(-varbindlist => [$OID_sysName]);
	 #my $list_a = $session->var_bind_list();

			#print Dumper \$list_a;


				my $interfaces = Net::SNMP::Interfaces->new(Hostname => $row[1],
				                                            Community => $row[3],
																										Port => $row[2]);

				if(!defined $interfaces) {
						print ("$row[1], $row[2], $row[3] not responding\n");
						goto label1;
				}

				 #my $result_a = $session->get_request(-varbindlist => [$OID_sysName]);
	 			 #my $list_a = $session->var_bind_list();

			#print Dumper \$list_a;

				@ifnames = $interfaces->if_indices();

				#print "@ifnames\n";

				foreach my $indices (@ifnames) {

					my $OID_1=$OID_ifoperationalstatus.".".$indices;
					my $OID_2=$OID_iftype.".".$indices;
					my $OID_3=$OID_ifSpeed.".".$indices;
					my $OID_4=$OID_ifAdminStatus.".".$indices;

					#my $OID_5=$OID_sysDescr.".".$indices;


					my $a;
					my $b;

					my $result_1 = $session->get_request(-varbindlist => [$OID_1]);
					my $list_1 = $session->var_bind_list();

					my $result_2 = $session->get_request(-varbindlist => [$OID_2]);
					my $list_2 = $session->var_bind_list();

					my $result_3 = $session->get_request(-varbindlist => [$OID_3]);
					my $list_3 = $session->var_bind_list();

					my $result_4 = $session->get_request(-varbindlist => [$OID_4]);
					my $list_4 = $session->var_bind_list();

					#my $result_5 = $session->get_request(-varbindlist => [$OID_5]);
					#my $list_5 = $session->var_bind_list();

					#print Dumper $list_5;

						if($list_1->{$OID_1}==1 && $list_2->{$OID_2} != 24 && $list_3->{$OID_3} > 0 && $list_4->{$OID_4}==1) {

							push @filtered_indices,$indices;
							#print @filtered_indices."\t";

							$a="in".$indices;
							$b="out".$indices;

							push @dsnames,$a,$b;

						}

			 	}

$counter_interfaces = scalar @filtered_indices; 
print "$counter_interfaces\n";
print "$row[1]-$row[2]-$row[3]-@filtered_indices\n";
		
				foreach my $x (@filtered_indices) {

										my $OID_5=$OID_ifInOctets.".".$x;
										my $OID_6=$OID_ifOutOctets.".".$x;
										my $OID_7=$OID_ifName.".".$x;

										#my $OID_8=$OID_sysName.".".$x;
										#print $OID_8;

							my $result_5 = $session->get_request(-varbindlist => [$OID_5]);
							my $list_5 = $session->var_bind_list();
							#print Dumper \$list_5;
							push @in_traffic,$list_5->{$OID_5};
				
							my $result_6 = $session->get_request(-varbindlist => [$OID_6]);
							my $list_6 = $session->var_bind_list();
							push @out_traffic,$list_6->{$OID_6};

							push @all,$x;

							my $result_7 = $session->get_request(-varbindlist => [$OID_7]);
							my $list_7 = $session->var_bind_list();
							#push @intnames,$list_7->{$OID_7};

							push @int_names,$list_7->{$OID_7}

							#$result_8 = $session->get_request(-varbindlist => [$OID_8]);
							#my $list_8 = $session->var_bind_list();

							#my $result_8 = $session->get_request(-varbindlist => [$OID_8]);
							#my $list_8 = $session->var_bind_list();

							#print Dumper \$list_8;
							#my $asc=$x."-".$list_7->{$OID_7};
							#print $asc;
							#push @adc,$asc;
				}

		#print "@int_names-$row[1]-$row[2]-$row[3]\n";
		my $COUNT;

		my $count=0;
		
							foreach my $n (@filtered_indices) {
																			push @up, "in$n" => "$in_traffic[$count]", "out$n" => "$out_traffic[$count]";
																			$count++;
							}

						
							if($counter_interfaces != 0) {

												my $result_a = $session->get_request(-varbindlist => [$OID_sysName]);
	 			 								my $list_a = $session->var_bind_list();
				
												my $result_b = $session->get_request(-varbindlist => [$OID_syscontact]);
	 			 								my $list_b = $session->var_bind_list();

												my $result_c = $session->get_request(-varbindlist => [$OID_syslocation]);
	 			 								my $list_c = $session->var_bind_list();

												#print Dumper \$list_a;
												my $aa=$list_a->{$OID_sysName};
												my $bb=$list_b->{$OID_syscontact};
												my $cc=$list_c->{$OID_syslocation};
												#print $aa;

												my $sthaa = $dbh->prepare("SELECT * FROM ASSIGNMENT1 WHERE IP='$row[1]' and PORT='$row[2]' and COMMUNITY='$row[3]'");
												$sthaa->execute();
												$COUNT= $sthaa->rows;

												if ($COUNT==0){		
												my $sqli="INSERT INTO ASSIGNMENT1(IP,PORT,COMMUNITY,INTERFACES,INTERFACES_NAMES,SYSTEM_NAME,SYSTEM_CONTACT,SYSTEM_LOCATION) values('$row[1]','$row[2]','$row[3]','@filtered_indices','@int_names','$aa','$bb','$cc')";
												my $sth22 = $dbh->prepare( $sqli );
												$sth22->execute;
												}


									my $RRD = RRD::Simple->new( file => "$row[1]_$row[2]_$row[3].rrd",
																											cf => [ qw(AVERAGE) ],
																											default_dstype => "COUNTER",
																											on_missing_ds => "add", );

									unless (-e "$row[1]_$row[2]_$row[3].rrd"){ 
																														$RRD->create( "$row[1]_$row[2]_$row[3].rrd","mrtg",
																														DS=>"COUNTER");

																					foreach my $name_ds (@dsnames) {
																	 									 $RRD->add_source("$row[1]_$row[2]_$row[3].rrd",
																										 $name_ds => "COUNTER"
																			 							 );
																					}
									}

														$RRD->update("$row[1]_$row[2]_$row[3].rrd",@up);
							}

		
		#my $info = $RRD->info("$row[1].$row[2].$row[3].rrd");
		#print Dumper $info;

		$session->close();	

		label1: print "Moving to next device \n";
}

print "Working\n";
