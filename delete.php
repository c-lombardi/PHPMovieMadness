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
	$MovieID = $_GET['movieID'];
	$ActorID = $_GET['actorID'];
	$deleteMovieActorsQuery = sprintf("DELETE FROM MovieActors 
			WHERE MovieActors.ActorID = '%d' AND MovieActors.MovieID = '%d'", $ActorID, $MovieID);
	$deleteMovieActors = mysql_query($deleteMovieActorsQuery);
	$deleteActorsQuery = sprintf("DELETE FROM Actors
	WHERE Actors.ActorID = '%d'",$ActorID);
	$deleteActors = mysql_query($deleteActorsQuery);
	if ($deleteActors && $deleteMovieActors)
	{
		header( 'Location: http://student5.upj.pitt.edu/moviemadness/index.php' ) ;
	}
	else{
		header( 'Location: http://student5.upj.pitt.edu/moviemadness/index.php?errorMessage=Error%20Deleting%20Movie' ) ;
		die;
	}
}
else
{
	$MovieID = $_GET['movieID'];
	$deleteUserViewingsQuery = sprintf("DELETE FROM UserViewings
	WHERE UserViewings.MovieID = '%d'",$MovieID);
	$deleteUserViewings = mysql_query($deleteUserViewingsQuery);
	
	$selectMovieDirectorsQuery = sprintf("SELECT d.DirectorID 
FROM Directors AS d
INNER JOIN MovieDirectors AS md 
ON md.MovieID = '%d'
AND d.DirectorID = md.DirectorID", $MovieID);
	$selectMovieDirectors = mysql_query($selectMovieDirectorsQuery);
	while ($mds = mysql_fetch_assoc($selectMovieDirectors)) {
		echo $mds['DirectorID'];
		$deleteMovieDirectorsQuery = sprintf("DELETE FROM MovieDirectors 
			WHERE MovieDirectors.DirectorID = '%d' AND MovieDirectors.MovieID = '%d'", $mds['DirectorID'], $MovieID);
		$deleteMovieDirectors = mysql_query($deleteMovieDirectorsQuery);
		$deleteDirectorsQuery = sprintf("DELETE FROM Directors
	WHERE Directors.DirectorID = '%d'",$mds['DirectorID']);
		$deleteDirectors = mysql_query($deleteDirectorsQuery);
		if (!$deleteDirectors)
		{
			header( 'Location: http://student5.upj.pitt.edu/moviemadness/index.php?errorMessage=Error%20Deleting%20Actors' ) ;
			die;
		}
	}
	
	$selectMovieActorsQuery = sprintf("SELECT d.ActorID 
FROM Actors AS d
INNER JOIN MovieActors AS md 
ON md.MovieID = '%d'
AND d.ActorID = md.ActorID", $MovieID);
	$selectMovieActors = mysql_query($selectMovieActorsQuery);
	while ($mds = mysql_fetch_assoc($selectMovieActors)) {
		$deleteMovieActorsQuery = sprintf("DELETE FROM MovieActors 
			WHERE MovieActors.ActorID = '%d' AND MovieActors.MovieID = '%d'", $mds['ActorID'], $MovieID);
		$deleteMovieActors = mysql_query($deleteMovieActorsQuery);
		$deleteActorsQuery = sprintf("DELETE FROM Actors
	WHERE Actors.ActorID = '%d'",$mds['ActorID']);
		$deleteActors = mysql_query($deleteActorsQuery);
		if (!$deleteActors)
		{
			header( 'Location: http://student5.upj.pitt.edu/moviemadness/index.php?errorMessage=Error%20Deleting%20Actors' ) ;
			die;
		}
	}
	
	$deleteMoviesQuery = sprintf("DELETE FROM Movies
		WHERE Movies.MovieID = '%d'", $MovieID);
	$deleteMovies = mysql_query($deleteMoviesQuery);
	if ($deleteMovies && $deleteUserViewings)
	{
		header( 'Location: http://student5.upj.pitt.edu/moviemadness/index.php' ) ;
		die;
	}
	else{
		header( 'Location: http://student5.upj.pitt.edu/moviemadness/index.php?errorMessage=Error%20Deleting%20Movie' ) ;
		die;
	}
}
?>
