<?php
$serverName = 'localhost:3306';
$userName = 's5MovieMadness';
$password = 'qqqqqq6';
$databaseName = 'student5_MovieMadness';
$connection = mysql_connect($serverName, $userName, $password) or die (mysql_error());
$database = mysql_select_db($databaseName, $connection);

if (!(empty($_POST['DateWatched'])) && !(empty($_POST['DirectorFirstName']))  && !(empty($_POST['DirectorLastName'])) && !(empty($_POST['Title'])))
{
	$Title = $_POST['Title'];
	$DirectorFirstName = $_POST['DirectorFirstName'];
	$DirectorLastName = $_POST['DirectorLastName'];
	$DirectorID = $_POST['DirectorID'];
	$ActorCount = $_POST['ActorCount'];
	$MovieID = $_POST['MovieID'];
	$DateWatched = $_POST['DateWatched'];
	$RatingID = $_POST['RatingID'];
	$updateMoviesQuery = sprintf("UPDATE Movies, Directors, UserViewings
	SET Movies.Title='%s', Movies.RatingID = '%d', Directors.FirstName = '%s', Directors.LastName = '%s', UserViewings.DateWatched = '%s'
	WHERE Movies.MovieID = '%d' AND Directors.DirectorID = '%d' AND UserViewings.MovieId = '%d'",
		$Title, $RatingID, $DirectorFirstName, $DirectorLastName, $DateWatched, $MovieID, $DirectorID, $MovieID);
	$failed = false;
	for($i=0; $i<=$ActorCount-1; $i++) {
		if (!(empty($_POST['ActorFirstName'.$i])) && !(empty($_POST['ActorLastName'.$i])))
		{
			${"ActorFirstName".$i} = $_POST['ActorFirstName'.$i];
			${"ActorLastName".$i} = $_POST['ActorLastName'.$i]; 
			${"ActorID".$i} = $_POST['ActorID'.$i];
			$ActorQuery = sprintf("UPDATE  `student5_MovieMadness`.`Actors` 
			SET  `FirstName` =  '%s', `LastName` =  '%s'  
			WHERE  `Actors`.`ActorID` = %d",
				${"ActorFirstName".$i}, ${"ActorLastName".$i}, ${"ActorID".$i});
			if(!(mysql_query($ActorQuery)))
				{$failed = true;}
		}
		else
		{
			header( 'Location: http://student5.upj.pitt.edu/moviemadness/index.php?errorMessage=Missing%20Actor%20Data' ) ;
			die;		
		}
	}
	if(mysql_query($updateMoviesQuery) && !($failed))
	{
		header( 'Location: http://student5.upj.pitt.edu/moviemadness/index.php' ) ;
		die;
	}
	else
	{
		echo "ERROR UPDATING!";
		die;
	}
}
else
{
	header( 'Location: http://student5.upj.pitt.edu/moviemadness/index.php?errorMessage=Missing%20Movie%20Data' ) ;
	die;
}
?>