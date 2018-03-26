<?php

session_start();
include('my_connect.php');
$id = 0;

foreach($_POST as $name => $content) { // Most people refer to $key => $value
   $id = $name;
   
}
 
//print_r($id);
$user = $_SESSION["username"];
$date = $_SESSION["date"];
$capacity = $_SESSION["capacity"];
$time = $_SESSION["time"];
$building = $_SESSION["building"];
//print_r($date);
//print_r($capacity);
//print_r($time);
//print_r($building);


$con=get_mysqli_conn();
$newdate = date('Y-m-d');
  // Ultimate Query
 $query = "SELECT b.BuildingName, r.RoomNumber, r.Capacity, r.Equipment, r.Jurisdiction, r.RoomId, COUNT(o.SlotId) 
      FROM Building b NATURAL JOIN Room r
      NATURAL JOIN Booking o WHERE NOT EXISTS (
          SELECT * FROM Building NATURAL JOIN Room
          NATURAL JOIN Booking WHERE o.Date = '$date'
          AND o.SlotId IN (".implode(',', $time).")
      ) AND r.Available = '1' AND r.Capacity >= '$capacity'
      AND b.BuildingId LIKE '$building' AND o.date= '$date'
      GROUP BY b.BuildingName, r.RoomNumber, r.Capacity, r.Equipment, r.Jurisdiction, r.RoomId";

  $result = $con->query($query);

  $resultCheck = mysqli_num_rows($result);
  if (0 == $resultCheck){
    $result = $con->query("SELECT b.BuildingName, r.RoomNumber, r.Capacity, r.Equipment, r.Jurisdiction, r.RoomId
    FROM Building b NATURAL JOIN Room r WHERE r.Available = '1' AND r.Capacity >= '$capacity'
    AND b.BuildingId LIKE '$building'");
    $resultCheck = mysqli_num_rows($result);
  }

  $row = mysqli_fetch_assoc($result);
                      
  $i = 1;


   //print_r($resultCheck);
    
  if($resultCheck > 0){
      while ($row = $result->fetch_assoc()) {
          if($id == $i){
              
                $_SESSION["BuildingName"] = $row['BuildingName'];
                $putRoom = $row['RoomId'];
                $_SESSION["roomNumber"] = $row['RoomNumber'];
                $length = count($time);
                for ($j = 0; $j < $length; $j++) {
                    
                    
                    print_r("SlotId is " . ' ' . $time[$j]);
                    print_r("RoomId is " . ' ' . $putRoom);
                    print_r("User is " . ' ' . $user);
                   
                    $insert = "INSERT INTO `Booking` (`Date`, `SlotId`, `RoomId`, `UserId`, `Approved`) VALUES ('$date', '" . $time[$j] . "', '$putRoom', '$user', '1')";
                     
                  mysqli_query($con, $insert);
             
                }
              
                
          }
        
          
          $i = $i + 1;

      }
  }

    echo '<script language="javascript">';
    echo 'alert("Room is Booked!");';
    echo 'window.location.href="confirmation.html";';
	echo '</script>'; 


  
?>