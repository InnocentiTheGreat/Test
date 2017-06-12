<?php

try {
    $mysqli = new PDO("mysql:host=localhost;dbname=reddit_sorter", "root", "zgl0F4uHcAyaJ43LmTAnluc71JhKK9");
} catch (Exception $ex) {
    die("Error: " . $ex->getMessage());
}