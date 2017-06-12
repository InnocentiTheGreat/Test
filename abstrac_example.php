<?php

/*
Abstract Classes
An abstract class can provide some functionality and leave the rest for derived class

    The derived class may or may not override the concrete functions defined in base class

    The child class extended from an abstract class should logically be related

Interface
An interface cannot contain any functionality. It only contains definitions of the methods

    The derived class must provide code for all the methods defined in the interface

    Completely different and non-related classes can be logically be grouped together using an interface
 */

abstract class person {

    public $LastName;
    public $FirstName;
    public $BirthDate;

    abstract protected function write_info();
}

final class employee extends person{

    public $EmployeeNumber;
    public $DateHired;

    public function write_info(){
        //sql codes here
        echo "Writing ". $this->LastName . "'s info to emloyee dbase table <br>";
    }
}

final class student extends person{

    public $StudentNumber;
    public $CourseName;

    public function write_info(){
        //sql codes here
        echo "Writing ". $this->LastName . "'s info to student dbase table <br>";
    }
}

///----------
$personA = new employee;
$personB = new student;