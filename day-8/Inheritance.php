<?php

class school {
    public string $eventName;
    public string $eventOrganizer;
    public string $sponsor;
    public int $eventBudget;


    function __construct($eventName,$eventOrganizer, $eventBudget, $sponsor ){
        $this->eventName = $eventName;
        $this->eventOrganizer = $eventOrganizer;
        $this->eventBudget = $eventBudget;
        $this->sponsor = $sponsor;
    }

    function events() {
        echo "The Events is " ."<strong>". $this->eventName."</strong>" . " and the organizer is " ."<strong>".$this->eventOrganizer."</strong>";
    }

    function showBudget() {
        echo "<br>".$this->eventBudget; 
    }

    function showSponsor(){
        echo "<br>".$this->sponsor; 
    }
}

class management extends school {
    function event() {
        $this->events();
    }
    function eventBudget() {
        echo "<br> Event budget is ".$this->eventBudget;
    }
}
$s1 = new school("Comedy Show With Kapil", "Kapil Sharma",50000, "Salman Khan");

// echo $s1->eventBudget;
// echo $s1->sponsor;
// $s1->events();

$mgmt = new management("Comedy Show With Kapil", "Kapil Sharma",50000, "Salman Khan");
$mgmt->event();
$mgmt->eventBudget();
$mgmt->showSponsor();