<?php

//http://code.tutsplus.com/tutorials/dependency-injection-in-php--net-28146
//https://laracasts.com/series/object-oriented-bootcamp-in-php/episodes/5

error_reporting(E_ALL);
ini_set("display_errors", 1);

class Author
{
    private $firstName;
    private $lastName;

    public function __construct($firstName, $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }
}

class Question
{
    private $author;
    private $question;

    public function __construct($question, Author $author)
    {
        $this->author = $author;
        $this->question = $question;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getQuestion()
    {
        return $this->question;
    }
}

$q = new Question("Hello?", new Author("Foo", "Bar"));
var_dump($q->getAuthor());