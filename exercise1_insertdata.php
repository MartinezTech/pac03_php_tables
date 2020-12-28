<?php
//connect to MySQL
$db_hostname = 'localhost';
$db_user = 'root';
$db_passwd = 'P@ssw0rd';
$db = mysqli_connect($db_hostname, $db_user, $db_passwd) or
    die ('Unable to connect. Check your connection parameters.');

mysqli_select_db($db, 'moviesite') or die(mysqli_error($db));

$query = <<<ENDSQL
INSERT INTO reviews
    (review_movie_id, review_date, reviewer_name, review_comment,
        review_rating)
VALUES 
    (2, "2020-12-24", "Alex Martinez", "Horrible film, I don't like it", 1),
    (3, "2020-12-24", "Sergio Martinez", "Once again seizing control of the medium, Nolan attempts to alter the fabric of reality, or at least blow the roof off the multiplexes. Big, bold, baffling and bonkers. ", 4),
    (3, "2020-12-24", "Luca Martinez", "a big, brashly beautiful, grandiosely enjoyable one that will provide succor to audiences long-starved for escapist spectacle on this beefy, made-for-Imax scale.", 5),
    (3, "2020-12-24", "Soraya Martinez", "The bestselling novel 'World War Z' by Max Brooks (Mel Brooks' son) started a bidding war right out of the gate, and unfortunately it's been turned into a sloppy, dull summer action flick. ", 5),
    (3, "2020-12-24", "Maria Martinez", "Slavering, lightning-quick, bloodthirsty hordes of 'modern' zombies are taking over the world yet again. It's Brad Pitt's turn to save the day.", 4)
ENDSQL;
mysqli_query($db, $query) or die(mysqli_error($db));

echo 'Movie database successfully updated!';

?>