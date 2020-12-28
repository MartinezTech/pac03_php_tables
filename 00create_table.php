<?php
//connect to MySQL
$db_hostname = 'localhost';
$db_user = 'root';
$db_passwd = 'P@ssw0rd';
$db = mysqli_connect($db_hostname, $db_user, $db_passwd) or
    die ('Unable to connect. Check your connection parameters.');

//create the main database if it doesn't already exist
$query = 'CREATE DATABASE IF NOT EXISTS moviesite';
mysqli_query($db,$query) or die(mysqli_error($db));

//make sure our recently created database is the active one
mysqli_select_db($db,'moviesite') or die(mysqli_error($db));

//create the movie table
$query = 'CREATE TABLE movie (
        movie_id        INTEGER UNSIGNED  NOT NULL AUTO_INCREMENT, 
        movie_name      VARCHAR(255)      NOT NULL, 
        movie_type      TINYINT           NOT NULL DEFAULT 0, 
        movie_year      SMALLINT UNSIGNED NOT NULL DEFAULT 0, 
        movie_leadactor INTEGER UNSIGNED  NOT NULL DEFAULT 0, 
        movie_director  INTEGER UNSIGNED  NOT NULL DEFAULT 0, 

        PRIMARY KEY (movie_id),
        KEY movie_type (movie_type, movie_year) 
    ) 
    ENGINE=InnoDB';
mysqli_query($db,$query) or die (mysqli_error($db));

//create the movietype table
$query = 'CREATE TABLE movietype ( 
        movietype_id    TINYINT UNSIGNED NOT NULL AUTO_INCREMENT, 
        movietype_label VARCHAR(100)     NOT NULL, 
        PRIMARY KEY (movietype_id) 
    ) 
    ENGINE=InnoDB';
mysqli_query($db,$query) or die(mysqli_error($db));

//create the people table
$query = 'CREATE TABLE people ( 
        people_id         INTEGER UNSIGNED    NOT NULL AUTO_INCREMENT, 
        people_fullname   VARCHAR(255)        NOT NULL, 
        people_isactor    TINYINT(1) UNSIGNED NOT NULL DEFAULT 0, 
        people_isdirector TINYINT(1) UNSIGNED NOT NULL DEFAULT 0, 

        PRIMARY KEY (people_id)
    ) 
    ENGINE=InnoDB';
mysqli_query($db,$query) or die(mysqli_error($db));

echo 'Movie database successfully created!';
?>