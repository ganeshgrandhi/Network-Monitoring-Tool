#############################################################################################################################################
																														ASSIGNMENT1
#############################################################################################################################################

The purpose of this assignment is to develop a RRD tool similar to MRTG and configure MRTG. The results are presented through a web dashboard.

This document describes information about various files in this folder, modules/software needed and steps to run this assignment.

This folder consists of 14 files in total:
-----------------------------------------

1. ff.jpg
2. backend.pl
3. device.php
4. image.php
5. img-interface.php
6.index.php
7.index1.php
8.index2.php
9.index3.php
10.interface.php
11.mrtgconf.pl
12.readme.txt
13.style.css
14.backend
 
Steps to run this assignment:
-----------------------------

PART-A
1)Replace db.conf file with your own db file or change the credentials accordingly.
2)Use "cd" command to change directory to assignment1 folder.
3)Run command "sudo perl mrtgconf.pl".
4)To view the MRTG statistics, use the URL:
   http://localhost/mrtg/

PART-B
1)To run the developed tool, run the SHELL script "backend" in the terminal with the command "sh backend".
2) Now, open a web browser and type the following URL: (It is assummed that the folder is in /var/www/html/)
	 http://localhost/et2536-vegr15/assignment1/index.php

4. Choose the desired device to view the graphs and statistics.

NOTE:
-----
1. Make sure to create "DEVICES" table in the MySQL database prior to running the backend script in the terminal. 
	 It can be created by following the steps in the major readme.txt file, for all the 4 assignments.

2. Add the following lines to the file "apache2.conf" before configuring the MRTG.
   The path for file is "/etc/apache2/apache2.conf";

Alias /mrtg "/var/www/mrtg/"
 
<Directory "/var/www/mrtg/">
        Options None
        AllowOverride None
        Require all granted
</Directory>

ServerName localhost:80

3. Restart Apache after adding the above lines to apache2.conf
   sudo service apache2 restart

