<?php
//function to get average ratings
function avg_ratings($movie_id){
    global $db;
    $query = 'SELECT ROUND(avg(review_rating),1) as average_rating from reviews where review_movie_id = ' . $movie_id;
    
    $result = mysqli_query($db, $query) or die(mysqli_error($db));
    $row = mysqli_fetch_assoc($result);
    extract($row);
    return $average_rating;
}

// function to generate ratings
function generate_ratings($rating) {
    $movie_rating = '';
    for ($i = 0; $i < $rating; $i++) {
        $movie_rating .= '<img src="star.png" height="10px" width="10px" alt="star"/>';
    }
    return $movie_rating;
}

// take in the id of a director and return his/her full name
function get_director($director_id) {

    global $db;

    $query = 'SELECT 
            people_fullname 
       FROM
           people
       WHERE
           people_id = ' . $director_id;
    $result = mysqli_query($db, $query) or die(mysqli_error($db));

    $row = mysqli_fetch_assoc($result);
    extract($row);

    return $people_fullname;
}

// take in the id of a lead actor and return his/her full name
function get_leadactor($leadactor_id) {

    global $db;

    $query = 'SELECT
            people_fullname
        FROM
            people 
        WHERE
            people_id = ' . $leadactor_id;
    $result = mysqli_query($db, $query) or die(mysqli_error($db));

    $row = mysqli_fetch_assoc($result);
    extract($row);

    return $people_fullname;
}

// take in the id of a movie type and return the meaningful textual
// description
function get_movietype($type_id) {

    global $db;

    $query = 'SELECT 
            movietype_label
       FROM
           movietype
       WHERE
           movietype_id = ' . $type_id;
    $result = mysqli_query($db, $query) or die(mysqli_error($db));

    $row = mysqli_fetch_assoc($result);
    extract($row);

    return $movietype_label;
}

// function to calculate if a movie made a profit, loss or just broke even
function calculate_differences($takings, $cost) {

    $difference = $takings - $cost;

    if ($difference < 0) {     
        $color = 'red';
        $difference = '$' . abs($difference) . ' million';
    } elseif ($difference > 0) {
        $color ='green';
        $difference = '$' . $difference . ' million';
    } else {
        $color = 'blue';
        $difference = 'broke even';
    }

    return '<span style="color:' . $color . ';">' . $difference . '</span>';
}

//connect to mysqli
$db_hostname = 'localhost';
$db_user = 'root';
$db_passwd = 'P@ssw0rd';
$db = mysqli_connect($db_hostname, $db_user, $db_passwd) or
    die ('Unable to connect. Check your connection parameters.');

mysqli_select_db($db, 'moviesite') or die(mysqli_error($db));

// retrieve information
$query = 'SELECT
        movie_id, movie_name, movie_year, movie_director, movie_leadactor,
        movie_type, movie_running_time, movie_cost, movie_takings
    FROM
        movie
    WHERE
        movie_id = ' . $_GET['movie_id'];
$result = mysqli_query($db, $query) or die(mysqli_error($db));

$row = mysqli_fetch_assoc($result);
$movie_id           = $row['movie_id'];
$movie_name         = $row['movie_name'];
$movie_director     = get_director($row['movie_director']);
$movie_leadactor    = get_leadactor($row['movie_leadactor']);
$movie_year         = $row['movie_year'];
$movie_running_time = $row['movie_running_time'] .' mins';
$movie_takings      = $row['movie_takings'] . ' million';
$movie_cost         = $row['movie_cost'] . ' million';
$movie_health       = calculate_differences($row['movie_takings'], $row['movie_cost']);
$movie_avg_rating = avg_ratings($movie_id);

// display the information
echo <<<ENDHTML
<html>
 <head>
  <title>Details and Reviews for: $movie_name</title>
 </head>
 <body>
  <div style="text-align: center;">
   <h2>$movie_name | Rating: $movie_avg_rating</h2>
   <h3><em>Details</em></h3>
   <table cellpadding="2" cellspacing="2"
    style="width: 70%; margin-left: auto; margin-right: auto;">
    <tr>
     <td><strong>Title</strong></strong></td>
     <td>$movie_name</td>
     <td><strong>Release Year</strong></strong></td>
     <td>$movie_year</td>
    </tr><tr>
     <td><strong>Movie Director</strong></td>
     <td>$movie_director</td>
     <td><strong>Cost</strong></td>
     <td>$movie_cost<td/>
    </tr><tr>
     <td><strong>Lead Actor</strong></td>
     <td>$movie_leadactor</td>
     <td><strong>Takings</strong></td>
     <td>$movie_takings<td/>
    </tr><tr>
     <td><strong>Running Time</strong></td>
     <td>$movie_running_time</td>
     <td><strong>Health</strong></td>
     <td>$movie_health<td/>
    </tr>
   </table>
ENDHTML;





// retrieve reviews for this movie
$query = 'SELECT
        review_movie_id, review_date, reviewer_name, review_comment,
        review_rating
    FROM
        reviews
    WHERE
        review_movie_id = ' . $_GET['movie_id'];

//ORDER ROW by user preference
if ($_GET['sort'] == 'review_date')
{
    $query .= " ORDER BY review_date DESC";
}
elseif ($_GET['sort'] == 'reviewer_name')
{
    $query .= " ORDER BY reviewer_name DESC";
}
elseif ($_GET['sort'] == 'review_comment')
{
    $query .= " ORDER BY review_comment DESC";
}
elseif($_GET['sort'] == 'review_rating')
{
    $query .= " ORDER BY review_rating DESC";
}

$result = mysqli_query($db, $query) or die(mysqli_error($db));
// display the reviews
echo <<< ENDHTML
   <h3><em>Reviews</em></h3>
   <table cellpadding="2" cellspacing="2"
    style="width: 90%; margin-left: auto; margin-right: auto;">
    <tr>
     <th style="width: 7em;"><a href="exercise3b.php?sort=review_date&movie_id=$movie_id">Date</a></th>
     <th style="width: 10em;"><a href="exercise3b.php?sort=reviewer_name&movie_id=$movie_id">Reviewer</a></th>
     <th><a href="exercise3b.php?sort=review_comment&movie_id=$movie_id">Comments</a></th>
     <th style="width: 5em;"><a href="exercise3b.php?sort=review_rating&movie_id=$movie_id">Rating</a></th>
    </tr>
ENDHTML;

$cont = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $date = $row['review_date'];
    $name = $row['reviewer_name'];
    $comment = $row['review_comment'];
    $rating = generate_ratings($row['review_rating']);
    
    //Exercise 4
    $cont += 1; 
    if (($cont%2) ==0){ //CHECKING ODD NUMBERS IN ORDER TO SET THE BACKGROUND COLOR
        echo <<< ENDHTML
            <tr style="background-color:rgb(240, 240, 240);">
        ENDHTML;
    }else{
        echo <<< ENDHTML
            <tr style="background-color:rgb(180, 180, 180);">
        ENDHTML;
    }

    
    echo <<<ENDHTML
    

      <td style="vertical-align:top; text-align: center;">$date</td>
      <td style="vertical-align:top;">$name</td>
      <td style="vertical-align:top;">$comment</td>
      <td style="vertical-align:top;">$rating</td>
    </tr>
ENDHTML;
}

echo <<<ENDHTML
  </div>
 </body>
</html>
ENDHTML;
?>
