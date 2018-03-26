<?php

session_start();
include('my_connect.php');
$id = 0;

foreach($_POST as $name => $content) { // Most people refer to $key => $value
   $id = $name;
   
}
 
//print_r($id);
$user = $_SESSION["username"];



$con=get_mysqli_conn();

  // Ultimate Query
 $result = $con->query("SELECT  b.BuildingName, r.RoomNumber, r.Capacity, r.Equipment, o.Date, COUNT(o.SlotId), r.RoomId
                                            FROM Booking o NATURAL JOIN Room r NATURAL JOIN Building b
                                            WHERE UserId='".$_SESSION['username']."' 
                                            GROUP BY b.BuildingName, r.RoomNumber, r.Capacity, r.Equipment, o.Date ,r.RoomId ");


  $resultCheck = mysqli_num_rows($result);

  
                      
  $i = 1;

   

   
      while ($resultCheck > 0) {
             $row = $result->fetch_assoc();
             // print_r($row);
          if($id == $i){         
              
              
               
               $date = $row['Date'];
               $roomId = $row['RoomId'];
              
                       
            $delete = "DELETE FROM `Booking` WHERE `Booking`.`Date` = '$date' AND `Booking`.`RoomId` = '$roomId' AND `Booking`.`UserId` = '$user'";
            mysqli_query($con, $delete);

                   
             
                
              
                
          }
        
          
          $i = $i + 1;
          $resultCheck = $resultCheck - 1;
       

      }
  

 
   echo '<script language="javascript">';
   echo 'alert("Cancelled Booking");';
   echo 'window.location.href="bookingform.php";';
   echo '</script>'; 

  
?>