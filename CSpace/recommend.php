<?php
	session_start();
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
		$userID = $_SESSION['CSpace_userID'];
		$projectID = $_SESSION['CSpace_projectID'];
		require_once("connect.php");
		$title = $_GET['title'];
		$title = str_replace(" - Mozilla Firefox","",$title);
		$url = $_GET['page'];
		$query = "SELECT * FROM projects WHERE projectID='$projectID'";
		$results = mysql_query($query) or die(" ". mysql_error());
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$projTitle = $line['title'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="Coagmento icon" type="image/x-icon" href="../img/favicon.ico">
<title>Coagmento</title>
<link rel="stylesheet" href="css/styles.css" type="text/css" />
</head>
<body class="body">
<form action="recommendSubmit.php" method=get>
<table class="body" width=90%>
	<tr><td align="right"><img src="../img/recommend.jpg" height=40 style="vertical-align:middle;border:0" /> <span style="font-weight:bold;font-size:15px">Recommend a Webpage</span></td></tr>
	<tr><td><br/></td></tr>
	<tr><td>Select the collaborators from project <span style="font-weight:bold";><?php echo $projTitle;?></span> to recommend webpage<br/><a href="<?php echo $url;?>"><?php echo $title;?></a></td></tr>
	<tr><td><br/></td></tr>	
	<tr>
		<td>
			<table class="body">
		<?php
			$query = "SELECT * FROM memberships WHERE userID='$userID' AND projectID='$projectID' GROUP BY projectID";
			$results = mysql_query($query) or die(" ". mysql_error());
			while($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
				$projectID = $line['projectID'];
				$query1 = "SELECT * FROM memberships WHERE projectID='$projectID' AND userID!='$userID' GROUP BY userID";
				$results1 = mysql_query($query1) or die(" ". mysql_error());
				while($line1 = mysql_fetch_array($results1, MYSQL_ASSOC)) {
					$cUserID = $line1['userID'];
					$query2 = "SELECT * FROM users WHERE userID='$cUserID'";
					$results2 = mysql_query($query2) or die(" ". mysql_error());
					$line2 = mysql_fetch_array($results2, MYSQL_ASSOC);
					$userName = $line2['firstName'] . " " . $line2['lastName'];
					$avatar = $line2['avatar'];
					echo "<tr><td><input type=\"checkbox\" name=\"$cUserID\"/> </td><td> <img src=\"../img/$avatar\" width=30 height=30 /> </td><td> $userName</td></tr>";
				}
			}
		?>
			</table>
		</td>
	</tr>
	<tr><td><br/></td></tr>	
	<tr><td>You can also recommend this page to anyone else by entering their emails below.<br/>Multiple emails should be separated using commas(,).</td></tr>
	<tr><td><input type="text" size=60 name="emails" /></td></tr>
	<tr><td>Message (optional):</td></tr>	
	<tr><td><textarea rows="3" cols="55" name="message"></textarea></td></tr>		
	<tr><td><input type="hidden" name="title" value="<?php echo $title;?>"/><input type="hidden" name="url" value="<?php echo $url;?>"/><br/></td></tr>	
	<tr><td align="center"><input type="submit" value="Send Recommendation"/></td></tr>
</table>
</form>
</body>
</html>
<?php
	}
?>