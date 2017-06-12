<?php

include "db.php";

class reddit_class
{

    public function showSubreddit($subreddit) {

        global $mysqli;
        $stmt = $mysqli->prepare("SELECT * FROM user_upvoted WHERE subreddit = :subreddit");
        $stmt->execute([":subreddit" => $subreddit]);

        return $stmt->fetchAll(PDO::FETCH_OBJ);

    }

    public function showAll()
    {

        global $mysqli;
        $stmt = $mysqli->prepare("SELECT * FROM user_upvoted ORDER BY created");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);

    }

    public function showAllSubreddits()
    {

        global $mysqli;
        $stmt = $mysqli->prepare("SELECT DISTINCT subreddit FROM user_upvoted ORDER BY subreddit");
        $stmt->execute();

        return $stmt->fetchAll();

    }

}
