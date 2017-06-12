<?php


//meh

class Weight
{

    protected $weight;

    public function __construct($weight)
    {
        $this->weight = $weight;
    }

    public function gain($pounds)
    {
        return new static($this->weight + $pounds);
    }

    public function lose($pounds)
    {
        return new static($this->weight - $pounds);
    }
}

class WorkOutPlaceMember
{
    protected $name;

    public function __construct($name, Weight $weight)
    {
        $this->name = $name;
    }

    public function workOutFor(TimeLength $length)
    {
        $length->inSeconds();
        $length->inHours();
    }

}

class TimeLength
{
    protected $seconds;

    public function __construct($seconds)
    {
        $this->seconds = $seconds;
    }

    public static function fromMinutes($minutes)
    {
        return new static($minutes * 60);
    }

    public static function fromHours($hours)
    {
        return new static($hours * 60 * 60);
    }

    public function inSeconds()
    {
        return $this->seconds;
    }

    public function inHours()
    {
        return $this->seconds / 60 / 60;
    }
}

$john = new WorkOutPlaceMember('John doe', new Weight(160));
echo $john->workOutFor(TimeLength::fromMinutes(45));