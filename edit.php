<?php
	$MovieID = $_GET['movieID'];
	
$serverName = 'localhost:3306';
$userName = 's5MovieMadness';
$password = 'qqqqqq6';
$databaseName = 'student5_MovieMadness';
$connection = mysql_connect($serverName, $userName, $password) or die (mysql_error());
$database = mysql_select_db($databaseName, $connection);
echo "<style>tr:nth-child(odd){background-color:lightgray;}</style>";
echo "<style>li:nth-child(even){background-color:lightgray;}</style>";
$movieAndDirectorQuery = sprintf("SELECT d.FirstName, d.LastName, m.Title, r.RatingLetters, uv.DateWatched, d.DirectorID 
	FROM Movies AS m
	INNER JOIN Directors AS d
	INNER JOIN MovieDirectors AS md 
	INNER JOIN Ratings As r 
	INNER JOIN UserViewings As uv
	ON m.MovieID = '%d'
	AND md.MovieID = '%d'
	AND d.DirectorID = md.DirectorID
	AND r.RatingID = m.RatingID
	AND uv.UserID = 1
	AND uv.MovieID = '%d'", $MovieID, $MovieID, $MovieID
);
echo "<div style='margin-left:auto;margin-right:auto;width:200px;border:1px solid black;'><div style='text-align:center;'><u>Add Actor</u></label></div><br /><form action='insert.php?type=actor' method='post' style='margin-bottom:5px;'><label>First Name</label><br /><input type='text' name='FirstName'></input><br /><label>Last Name</label><br /><input type='text' name='LastName'></input><br /><input type='hidden' name='MovieID' value='".$MovieID."'></input><input type='submit' value='add'></input><a type='button' style='float:right;' href='edit.php?movieID=".$MovieID."'>ResetData</a></form></div>";
echo "<form action = 'modify.php' method = 'POST'>";
$movieAndDirectors = mysql_query($movieAndDirectorQuery);
echo "<div style='width:100%;border:1px solid black;'><table><th>Title</th><th>Firstname</th><th>Lastname</th><th>Actors</th><th>Rating</th><th>DateLastWatched</th>";

$ActorIDArray = array();
$ActorFirstNameArray = array();
$ActorLastNameArray = array();
$ActorCount = 0;
while ($row = mysql_fetch_assoc($movieAndDirectors)) {
	echo "<tr><td>Title: <input type='text' name='Title' value='".$row['Title']."'></input></td>";
	echo "<td>Director First Name: <input type='text' name='DirectorFirstName' value='".$row['FirstName']."'></input></td>";
	echo "<td>Director Last Name: <input type='text' name='DirectorLastName' value='".$row['LastName']."'></input></td><td><ul>";
	echo "<input type='hidden' name='MovieID' value='".$MovieID."'></input>";
	echo "<input type='hidden' name='DirectorID' value='".$row['DirectorID']."'></input>";
	$movieAndActorQuery = sprintf("SELECT a.FirstName, a.LastName, a.ActorID
		FROM Actors AS a
		INNER JOIN MovieActors AS ma 
		WHERE ma.MovieID = '%d'
		AND a.ActorID = ma.ActorID",
		$MovieID);
	$movieAndActors = mysql_query($movieAndActorQuery);
	while ($rows = mysql_fetch_assoc($movieAndActors)) {
		echo "<li>Actor First Name:<input type='text' name='ActorFirstName".$ActorCount."' value='".$rows['FirstName']."'></input>";
		echo "<br />Actor Last Name:<input type='text' name='ActorLastName".$ActorCount."' value='".$rows['LastName']."'></input>";
		echo "<input type='hidden' name='ActorID".$ActorCount."' value='".$rows['ActorID']."'></input>";
		echo "<a href='delete.php?movieID=".$MovieID."&actorID=".$rows['ActorID']."&type=actor'>DELETE</a></li>";
		$ActorCount += 1;
	}
	echo "</ul></td><td><select name='RatingID'>";
	$RatingsQuery = sprintf("SELECT r.RatingLetters, r.RatingID
		FROM Ratings As r");
	$RatingsList = mysql_query($RatingsQuery);
	while ($ratings = mysql_fetch_assoc($RatingsList))
	{
		if ($ratings['RatingLetters'] === $row['RatingLetters'])
		{
			echo "<option name='RatingID' value='".$ratings['RatingID']."' selected>".$ratings['RatingLetters']."</option>";
		}
		else
		{
			echo "<option name='RatingID' value='".$ratings['RatingID']."'>".$ratings['RatingLetters']."</option>";
		}
	}
	echo "</select></td><td><input type='date' name='DateWatched' value='".$row['DateWatched']."'></input></td></tr>";
}
echo "<input type='hidden' name='ActorCount' value='".$ActorCount."'></input>";
echo "<tr><td><input type='submit'></input></td></tr></form>";
echo "</table></div>";

?>

