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
    <input type="Submit" value="Convert!">
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
// redirect ########################################################

//<meta http-equiv="refresh" content="0; url=http://example.com/" />
//echo "C-Url length: " . strlen($curl)."<br>" ;
if(substr($curl, 0, 1) == "?" && strlen($curl) == 6){
	echo substr($curl, 0, 1)."<br>";
	echo "LtriM : ". ltrim($curl, "?")."<br>";
	echo "C-Url length: " . strlen($curl)."<br>" ;
	$rurl = "";	

	$conn = mysqli_connect("192.168.0.32", "root", "jaas", "Tiny");				// Create connection
	$sql = "SELECT * FROM TinyUrl where Tiny = '" . ltrim($curl, "?") . "'";								// SQL query
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
// #################################################################
// echo "<b>gen urlnum:</b> " . $turl. "<br>";
// echo "<b>Url to get tinyfied:</b> " .  $_GET['URL'] . "<br>";
//echo "<b>Current Url: </b>" . $curl . "<br>";
echo "<br> TinyUrl = ";
echo "<a href=http://tiny/?".$turl.">".$turl."</a>";

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
