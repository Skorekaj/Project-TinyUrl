# create and run a container from a prebuilt mysql docker image from DockerHub MySQL
# copied the data from the local file store to my projects folder ~/mysql_docker , this is the oroiginal command: docker run -dit --rm --name mysql-server -p 3306:3306 --env MYSQL_ROOT_PASSWORD=jaas -v ~/private/mysql-data:/var/lib/mysql mysql:latest
docker run -dit --rm --name mysql-server -p 3306:3306 --env MYSQL_ROOT_PASSWORD=jaas -v ~/mysql_docker/mysql-data:/var/lib/mysql mysql:latest

docker exec -it mysql-server bash

# from client run mysql
#mysql -uroot -pjaas
mysql -uroot -pjaas -h ubuntu.local


# show all databses
show databases;

# create database <test>
create database test;

# change the database you rconnected to
use test;

# create table in the database your connected to 

CREATE TABLE Persons (
    PersonID int,
    LastName varchar(255),
    FirstName varchar(255),
    Address varchar(255),
    City varchar(255)
);

CREATE TABLE TinyUrl (
Mid int NOT NULL AUTO_INCREMENT KEY,
Url TEXT,
Tiny varchar(255)
);


# Insert values to table in database
INSERT INTO Persons (PersonID, LastName, FirstName, Address, City)
VALUES (1, 'Hansen', 'Jonas', 'sweden', 'helsingborg' );

# select all from persons 
select * from Persons;

####### massive problems as the mysql reset after being stoped in docker , until i found to put the -v and store the files in the local host instead of in the docker image

# install -> pip3 install mysql-connector-python

# create pyton code file.py
import mysql.connector

cnx = mysql.connector.connect(user='root', password='jaas', host='ubuntu.local', database='test')
with cnx.cursor() as cursor:
        result = cursor.execute("SELECT * FROM Persons LIMIT 5")
        rows = cursor.fetchall()
        for row in rows:
                print(row)
                print(row[0],row[2])
        cnx.close()

# run up a web server with ubuntu base image $(PWD)<- the directory your currently in
#docker run -dit --name "lighttpd-php" --rm -p 8000:80 -v $(pwd):/var/www alastairhm/alpine-lighttpd-php
#docker run -dit --name "lighttpd-php" --rm -p 8000:80 -v $(pwd):/var/www debian:latest
docker run -dit --name "ubuntu-lighttpd-php" --rm -p 8000:80 -v $(pwd):/var/www/html ubuntu:latest

# to get into the docker image shell BASH
docker exec -it ubuntu-lighttpd-php bash 

#install httpd and php etc 
apt update -y
apt upgrade -y
apt install apache2 -y
apt install php -y
apt install libapache2-mod-php -y
a2enmod php8.3 			# problem as the install doc said to run "a2enmod php"
apt install php-mysqlnd		# forgot/missed this dependancy , which will give you the 500 error in php
service apache2 start

#create a file in the workign directory locally on host drive/folder
nano phptest.php

#phptest.php content
<?php phpinfo(); ?>

#try to conenct in a browser like opera/IE
http://ubuntu.local:8000/phptest.php

# commit the docker image and save tro new image
docker commit ubuntu-lighttpd-php ubuntu-http-updated


#create a sql.php file to test access and pulling data from db
<?php
$conn = mysqli_connect("192.168.0.32", "root", "jaas", "test");				// Create connection
$sql = "SELECT * FROM Persons";								// SQL query
$result = mysqli_query($conn, $sql);							// 

while($row = mysqli_fetch_assoc($result)) {						// run through the results set
        echo "ID: " . $row['PersonID']. " - Name: " . $row['LastName']. " ">}		// important to note that you have to specify the $ro2['<the actual name of the table collumn>']

mysqli_close($conn);									// close connection
?>

#### finished, the problems to get the PHP to connecto the MySQL server was several hours and due to missing one of the dependancies.
#### another issue was tha the docker images didnt save the changes, this was resolved by doing a "Docker commit " save, once the image is saved into the state that you want its all good
### to troubleshoot php error 500 , go into the docker container with BAS and “php -f /var/www/html/phpsql.php”



################################### create code for the default index.php page ###############################
<?php
function redirect($url) {
	$url = str_replace("http://","",$url);
	$url = str_replace("https://","",$url);
	header('Location: http://' . $url);
	die();
}
?>

<!DOCTYPE html>
<html>
TinyUrl generator<br><br>

  <form method="GET" action="index.php">
  <label for="fname">URL to be Converted:</label>
   <br><input type="text" size="100" name="URL"><br><br>
    <input type="submit">
  </form>
<?php  
settype($MyRand, "integer");
settype($urll, "integer");  
//$MyRand = rand(0, 128);
$turl = "";
$urll = 1;
$curl = basename($_SERVER['REQUEST_URI']);

if($_GET['URL'] == ""){

}else{
	while ($urll < 6) {
		$MyRand = rand(0, 128);
//		echo "urll:" . $urll . "<br>";
		if ($MyRand >= 48 && $MyRand <= 57 || $MyRand >= 65 && $MyRand < 91 || $MyRand >= 97 && $MyRand <= 122){
//			echo "MyRand: " . $MyRand . "<br>";
			$turl = $turl . chr($MyRand);
//			echo "strlen: " . strlen($url);
			$urll = $urll + 1;
//			sleep(1);
		}
	}
}
// redirect ###############################################

//<meta http-equiv="refresh" content="0; url=http://example.com/" />
//echo "C-Url length: " . strlen($curl)."<br>" ;
if(substr($curl, 0, 1) == "?" && strlen($curl) == 6){
	echo substr($curl, 0, 1)."<br>";
	echo "LtriM : ". ltrim($curl, "?")."<br>";
	echo "C-Url length: " . strlen($curl)."<br>" ;
	$rurl = "";	

	$conn = mysqli_connect("192.168.0.32", "root", "jaas", "Tiny");				// Create connection
	$sql = "SELECT * FROM TinyUrl where Tiny = '" . ltrim($curl, "?") . "'";						// SQL query
	$result = mysqli_query($conn, $sql);							// 

	while($row = mysqli_fetch_assoc($result)) {						// run through the results set
        	echo "ID: " . $row['Mid']. " - Url: " . $row['Url']. " " . "Tiny Url: " . $row['Tiny']."<br>";
		$rurl = $row['Url'];
	}

	mysqli_close($conn);
	echo "$rurl" . $rurl . "<br>";
//	sleep(10);
	if($rurl == ""){
	
	}else {
		redirect($rurl) ;
	}
}
// ####################################################
// echo "<b>gen urlnum:</b> " . $turl. "<br>";
// echo "<b>Url to get tinyfied:</b> " .  $_GET['URL'] . "<br>";
//echo "<b>Current Url: </b>" . $curl . "<br>";
echo "<br> TinyUrl = ";
echo "<a href=http://ubuntu.local:8000/?".$turl.">".$turl."</a>";

    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
         $urln = "https://";   
    else  
         $urln = "http://";   
    // Append the host(domain name, ip) to the URL.   
    $urln.= $_SERVER['HTTP_HOST'];   
    
    // Append the requested resource location to the URL   
    $urln.= $_SERVER['REQUEST_URI'];    
      
   // echo "<b>Current url:</b> " . $urln . "<br>";   


// database update url in database
if ($_GET['URL'] == "" || $turl == ""){
//	echo "parameter missssing";   
}else{
        $conn = mysqli_connect("192.168.0.32", "root", "jaas", "Tiny");                         // Create connection
        $sqlupd = "INSERT INTO TinyUrl(Url,Tiny) VALUES('" . $_GET['URL'] . "','".$turl."')";    // SQL query
//        echo "INSERT INTO TinyUrl(Url,Tiny) VALUES('" . $_GET['URL'] . "','".$url."')";
        mysqli_query($conn, $sqlupd);                                                  // 
        mysqli_close($conn);   
}
?>
</html>
###################################  end code index.php ##########################################################

