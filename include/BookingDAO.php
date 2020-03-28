<?php

class BookingDAO {

    public  function retrieveAll() {

    $connMgr = new ConnectionManager();
    $pdo = $connMgr->getConnection();

    $sql = 'SELECT bookingID FROM booking';
    $stmt = $pdo->prepare($sql);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    $result = [];
    if( $stmt->execute() ) {
        while($row = $stmt->fetch()) {
            $result[] = $row['bookingID'];
        }
    }

    $stmt = null;
    $pdo = null;

    return $result;
    }

    public function insertbookingdetails($bookingID,$scooterID,$parkingLotID,$startTime) {
        $sql = 'INSERT INTO booking (bookingID, scooterID, parkingLotID, startTime, endTime) VALUES (:bookingID, :scooterID, :parkingLotID, :startTime, NULL)';
        
        $connMgr = new ConnectionManager();       
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':bookingID', $bookingID, PDO::PARAM_STR);
        $stmt->bindParam(':scooterID', $scooterID, PDO::PARAM_STR);
        $stmt->bindParam(':parkingLotID', $parkingLotID, PDO::PARAM_STR);
        $stmt->bindParam(':startTime', $startTime, PDO::PARAM_STR);
        
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result=$stmt->execute();

        $stmt = null;
        $conn = null;
        return $result;
    }

}

?>