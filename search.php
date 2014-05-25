<?php
$term = $_POST['searchTerm'];
$term = urlencode($term);
header( 'Location: http://student5.upj.pitt.edu/moviemadness/index.php?searchTerm='.$term.'' ) ;
?>