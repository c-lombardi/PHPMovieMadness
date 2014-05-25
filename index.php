<?php
$serverName = 'localhost:3306';
$userName = 's5MovieMadness';
$password = 'qqqqqq6';
$databaseName = 'student5_MovieMadness';
$connection = mysql_connect($serverName, $userName, $password) or die (mysql_error());
$database = mysql_select_db($databaseName, $connection);
$errorMessage ="";
echo "<style>tr:nth-child(odd){background-color:lightgray;}input[type=text]{float:right;}input[type=date]{float:right;}select{float:right;clear:both;}label{width:130px;}</style>";
echo "<div style='width:750px; margin-left:auto; margin-right:auto;'>";
if (isset($_GET['errorMessage']))
{
	$errorMessage = $_GET['errorMessage'];
	echo "<h1>ERROR:".$errorMessage."</h1>";
}
if (isset($_GET['searchTerm']) && $_GET['searchTerm']!="")
{
	$searchTerm = $_GET['searchTerm'];
	$movieAndDirectorQuery = sprintf("SELECT d.FirstName, d.LastName, m.Title, m.MovieID, r.RatingLetters, uv.DateWatched, a.FirstName, a.LastName
FROM Movies AS m
INNER JOIN Directors AS d
INNER JOIN MovieDirectors AS md 
INNER JOIN Ratings As r 
INNER JOIN UserViewings As uv
INNER JOIN Actors As a
INNER JOIN MovieActors as ma
ON m.MovieID = md.MovieID
AND r.RatingID = m.RatingID
AND uv.UserID = 1
AND uv.MovieID = m.MovieID
AND md.MovieID = m.MovieID
AND d.DirectorID = md.DirectorID
AND ma.MovieID = m.MovieID
AND a.ActorID = ma.ActorID
WHERE d.FirstName LIKE '%%%s%%'
OR d.LastName LIKE '%%%s%%'
OR a.FirstName LIKE '%%%s%%'
OR a.LastName LIKE '%%%s%%'
OR m.Title LIKE '%%%s%%'
GROUP BY m.Title
ORDER BY m.Title DESC",
		mysql_real_escape_string($searchTerm),mysql_real_escape_string($searchTerm),mysql_real_escape_string($searchTerm),mysql_real_escape_string($searchTerm),mysql_real_escape_string($searchTerm));
}
else{
	$movieAndDirectorQuery = sprintf("SELECT d.FirstName, d.LastName, m.Title, m.MovieID, r.RatingLetters, uv.DateWatched 
FROM Movies AS m
INNER JOIN Directors AS d
INNER JOIN MovieDirectors AS md 
INNER JOIN Ratings As r 
INNER JOIN UserViewings As uv
ON m.MovieID = md.MovieID
AND d.DirectorID = md.DirectorID
AND r.RatingID = m.RatingID
AND uv.UserID = 1
AND uv.MovieID = m.MovieID
ORDER BY m.Title DESC");
}
echo "<div style='width:100%;'>";
echo "<div style='float:left;width:50%;border:1px solid black;'>";
echo "<form action='search.php' method='post'>";
echo "<input type='text' name='searchTerm'></input>";
echo "<input type='submit' value='search'></form>";
echo "</div>";
echo "<div style='width:45%;float:right;border:1px solid black;'>";
echo "<form action='insert.php?type=movie' method='post'>
<div style='clear: both;'><label>Movie Title*</label><input type='text' name='Title'></input></div>";
echo "<div style='clear: both;'><label>Rating*</label><select name='RatingID'>";
$RatingsQuery = sprintf("SELECT r.RatingLetters, r.RatingID
		FROM Ratings As r");
$RatingsList = mysql_query($RatingsQuery);
while ($ratings = mysql_fetch_assoc($RatingsList))
{
	echo "<option value='".$ratings['RatingID']."'>".$ratings['RatingLetters']."</option>";
}
echo "</select></div>";
echo "<div style='clear: both;'><label>Director First Name*</label><input type='text' name='directorFirstName'></input></div>";
echo "<div style='clear: both;'><label>Director Last Name*</label><input type='text' name='directorLastName'></input></div>";
echo "<div style='clear: both;'><label>Date Watched*</label><input type='date' name='DateWatched'></input></div>";
echo "<input type='submit' value='add' style='float:right;'></input>";
echo "</form>";
echo "</div>";
echo "<div  style='margin-left:auto; margin-right:auto;'>";
echo "<form action='index.php' method='get'>";
echo "<input type='submit' value='Reset Data'></input></form>";
echo "</div>";
echo "</div>";
echo "<div style='width:100%;border:1px solid black;margin-top: 110px;'>";
$movieAndDirectors = mysql_query($movieAndDirectorQuery);
echo "<table style='width:100%;'><th>Title</th><th>Firstname</th><th>Lastname</th><th>Actors</th><th>Rating</th><th>DateLastWatched</th><th></th><th></th>";
while ($row = mysql_fetch_row($movieAndDirectors)) {
	echo "<tr><td>".$row[2]."</td>";
	echo "<td>".$row[0]."</td>";
	echo "<td>".$row[1]."</td><td><select size='2' Multiple>";
	
	$movieAndActorQuery = sprintf("SELECT a.FirstName, a.LastName
FROM Actors AS a
INNER JOIN MovieActors AS ma 
WHERE ma.MovieID = '%d'
AND a.ActorID = ma.ActorID;",
		$row[3]);
	$movieAndActors = mysql_query($movieAndActorQuery);
	while ($rows = mysql_fetch_assoc($movieAndActors)) {
		echo "<option>".$rows['FirstName']." ".$rows['LastName']."</option>";
	}
	echo "</select></td><td>".$row[4]."</td>";
	echo "<td>".$row[5]."</td>";
	echo "<td><a type='button' href='edit.php?movieID=".$row[3]."'>Edit</a></td>";
	echo "<td><a type='button' href='delete.php?movieID=".$row[3]."&type=movie'>Delete</a></td></tr>";
}
echo "</table>";
echo "</div>";
echo "</div>";
?>
