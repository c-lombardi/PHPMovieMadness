<?php
$serverName = 'localhost:3306';
$userName = 's5MovieMadness';
$password = 'qqqqqq6';
$databaseName = 'student5_MovieMadness';
$connection = mysql_connect($serverName, $userName, $password) or die (mysql_error());
$database = mysql_select_db($databaseName, $connection);

$type = $_GET['type'];

if ($type == "actor")
{
	if (!(empty($_POST['FirstName'])) && !(empty($_POST['LastName'])))
	{
		$MovieID = $_POST['MovieID'];
		$firstName = $_POST['FirstName'];
		$lastName = $_POST['LastName'];
		$insertActorQuery = sprintf("INSERT INTO Actors (ActorID, FirstName, LastName)
		VALUES (NULL, '%s', '%s')", $firstName, $lastName);
		$insertActors = mysql_query($insertActorQuery);
		$ActorID = mysql_insert_id($connection);
		$insertMovieActorQuery = sprintf("INSERT INTO MovieActors (MovieActorID, ActorID, MovieID)
		VALUES (NULL, '%d', '%d')", $ActorID, $MovieID);
		if($insertActors && mysql_query($insertMovieActorQuery))
		{
			header( 'Location: http://student5.upj.pitt.edu/moviemadness/index.php' ) ;
			die;
		}
		else
		{
			header( 'Location: http://student5.upj.pitt.edu/moviemadness/index.php?errorMessage=%20Actor%20Update%20Failed' ) ;
			die;
		}
	}
	header( 'Location: http://student5.upj.pitt.edu/moviemadness/index.php?errorMessage=Missing%20Actor%20Data' ) ;
	die;
}
else
{
	if (!(empty($_POST['DateWatched'])) && !empty($_POST['directorFirstName'])  && !empty($_POST['directorLastName']) && !empty($_POST['Title']))
	{
		$DateWatched = $_POST['DateWatched'];
		$DirectorFirstName = $_POST['directorFirstName'];
		$DirectorLastName = $_POST['directorLastName'];
		$MovieTitle = $_POST['Title'];
		$RatingID = $_POST['RatingID'];
		$insertMovieQuery = sprintf("INSERT INTO Movies(MovieID, Title, RatingID, GenreID)
		VALUES (NULL, '%s', '%d', '%d')", $MovieTitle, $RatingID, 1);
		$insertMovie = mysql_query($insertMovieQuery);
		$MovieID = mysql_insert_id($connection);
		$insertDirectorQuery = sprintf("INSERT INTO Directors(DirectorID, FirstName, LastName)
		VALUES (NULL, '%s', '%s')", $DirectorFirstName, $DirectorLastName);
		$insertDirector = mysql_query($insertDirectorQuery);
		$DirectorID = mysql_insert_id($connection);
		$insertMovieDirectorQuery = sprintf("INSERT INTO MovieDirectors(MovieDirectorID, DirectorID, MovieID)
		VALUES (NULL, '%d', '%d')", $DirectorID, $MovieID);
		$insertMovieDirector = mysql_query($insertMovieDirectorQuery);
		$insertUserViewingsQuery = sprintf("INSERT INTO UserViewings(ViewingId, UserId, MovieId, DateWatched)
		VALUES (NULL, '%d', '%d', '%s')", 1, $MovieID, $DateWatched);
		$insertUserViewings = mysql_query($insertUserViewingsQuery);
		if ($insertMovie && $insertDirector && $insertMovieDirector && $insertUserViewings)
		{
			header( 'Location: http://student5.upj.pitt.edu/moviemadness/index.php' ) ;
			die;
		}
		else
		{
			header( 'Location: http://student5.upj.pitt.edu/moviemadness/index.php?errorMessage=Missing%20Movie%20Data' ) ;
			die;
		}
	}
	else
	{
		header( 'Location: http://student5.upj.pitt.edu/moviemadness/index.php?errorMessage=Missing%20Movie%20Data' ) ;
		die;
	}
}
?>
