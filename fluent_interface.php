<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

interface Logger
{
    public function execute($message);
}

class LogToFile implements Logger
{
    public function execute($message)
    {
        // TODO: Implement execute() method.
        var_dump("Logging to file:" . $message);
    }
}

class LogToDatabase implements Logger
{
    public function execute($message)
    {
        // TODO: Implement execute() method.
        var_dump("Logging to database:" . $message);
    }
}

class UsersController
{

    protected $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function show()
    {
        $user = "Marko";
        $this->logger->execute($user);
    }
}

$controller = new UsersController(new LogToFile);
$controller->show();


//  v2


interface Provider
{
    public function authorize();
}

function login(Provider $provider)
{
    $provider->authorize();
}


// v3

interface Shape
{
    public function getArea();
}

class Square implements Shape
{
    private $width;
    private $height;

    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function getArea()
    {
        return $this->width * $this->height;
    }
}

class Circle implements Shape
{
    private $radius;

    public function __construct($radius)
    {
        $this->radius = $radius;
    }

    public function getArea()
    {

        return M_PI * $this->radius * $this->radius;
    }
}

function calculateArea(Shape $shape)
{
    return $shape->getArea();
}

$square = new Square(5, 5);
$circle = new Circle(7);

echo calculateArea($square), "<br/>";
echo calculateArea($circle);


//https://en.wikipedia.org/wiki/Fluent_interface#PHP

interface Employs
{
    public function setName($name);

    public function setSurname($surname);

    public function setSalary($salary);
}

class Employee implements Employs
{
    public $name;
    public $surName;
    public $salary;

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function setSurname($surname)
    {
        $this->surName = $surname;

        return $this;
    }

    public function setSalary($salary)
    {
        $this->salary = $salary;

        //return $this;
    }

    public function getInfo()
    {
        return array($this->name, $this->surName, $this->salary);
    }

    /*public function __toString()
    {
        $employeeInfo = 'Name: ' . $this->name . PHP_EOL;
        $employeeInfo .= 'Surname: ' . $this->surName . PHP_EOL;
        $employeeInfo .= 'Salary: ' . $this->salary . PHP_EOL;

        return $employeeInfo;
    }*/
}

# Create a new instance of the Employee class:
$employee = new Employee();

# Employee Tom Smith has a salary of 100:
echo $employee->setName('Tom')->setSurname('Smith')->setSalary('100');

var_dump($employee->getInfo());

# Display:
# Name: Tom
# Surname: Smith
# Salary: 100