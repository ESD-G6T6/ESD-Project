<?php

class Booking {

    private $bookingID;    
    private $scooterID;
    private $parkingLotID;
    private $startTime;
    private $endTime;

    public function __construct($bookingID,$scooterID,$parkingLotID,$startTime,$endTime) {
        $this->bookingID = $bookingID;
        $this->scooterID = $scooterID;
        $this->parkingLotID = $parkingLotID;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }

    public function getbookingID() {
        return $this->bookingID;
    }

    public function getscooterID() {
        return $this->scooterID;
    }

    public function getparkingLotID() {
        return $this->parkingLotID;
    }

    public function getstartTime() {
        return $this->startTime;
    }

    public function getendTime() {
        return $this->endTime;
    }
}

?>