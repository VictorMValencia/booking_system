<?php
  session_start();
  include('my_connect.php');
  $date = $_GET['date'];
  $_SESSION["date"] = $date;
  $time = $_GET['time'];
  $_SESSION["time"] = $time;
  $capacity = $_GET['capacity'];
  $_SESSION["capacity"] = $capacity;
  if ($_GET['building'] === ''){
    $building = '%';
  } else {
    $building = $_GET['building'];
  }
  $_SESSION["building"] = $building;
    

  $con=get_mysqli_conn();

  // Ultimate Query
  $query = "SELECT b.BuildingName, r.RoomNumber, r.Capacity, r.Equipment, r.Jurisdiction, COUNT(o.SlotId) 
      FROM Building b NATURAL JOIN Room r
      NATURAL JOIN Booking o WHERE NOT EXISTS (
          SELECT * FROM Building NATURAL JOIN Room
          NATURAL JOIN Booking WHERE o.Date = '$date'
          AND o.SlotId IN (".implode(',', $time).")
      ) AND r.Available = '1' AND r.Capacity >= '$capacity'
      AND b.BuildingId LIKE '$building' AND o.date= '$date'
      GROUP BY b.BuildingName, r.RoomNumber, r.Capacity, r.Equipment, r.Jurisdiction";

  $result = $con->query($query);
  $resultCheck = mysqli_num_rows($result);
  if (0 == $resultCheck){
    $result = $con->query("SELECT b.BuildingName, r.RoomNumber, r.Capacity, r.Equipment, r.Jurisdiction
    FROM Building b NATURAL JOIN Room r WHERE r.Available = '1' AND r.Capacity >= '$capacity'
    AND b.BuildingId LIKE '$building'");
    $resultCheck = mysqli_num_rows($result);
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href = "css/global.css" type="text/css" rel = "stylesheet">
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
      
      
  </head>

<body>
    <nav class="navbar navbar-expand-lg justify-content-between" >
           <ul class="navbar-nav">
      <li class="nav-item">
      <a class="nav text-dark" href="bookingform.php"> &lt;Update Preferences</a>
        </li>
        </ul>
        <a class="navbar-brand">
                <h3>
                <img src="images/uw-shield-logo.svg" width="30" height="30" class="d-inline-block align-top" alt="">

                UW BookMi </h3>
            </a>
        <span class="nav justify-content-end">
                <li class="nav-item">
                    <a class="btn btn-primary" href="logout.php" role="button">Log Out</a>    
                            
                </li>
            </span>
    </nav>
  <div class="container-fluid">

              <h1 class="h1">Available Rooms</h1>
                <div class="form-container">
                <div class="row">  
                    <?php
                       
                        
                        $resultCheck = mysqli_num_rows($result);
                        
                        $row = mysqli_fetch_assoc($result);
                      
                        $i = 1;
                            
                        if($resultCheck > 0){
                            while ($row = $result->fetch_assoc()) {
                    ?>
                        
                            <div class="col-sm-4 ">
                            <div class="card">
                            <div class="card-body">

                                  <!--Query Results should be displayed below-->
                                  <h5 class="card-title"> 
                                      <?php echo $row['BuildingName']."<br>", 'Room: ', $row['RoomNumber']?> 
                                  </h5>
                                  <p class="card-text">
                                        <?php
                        
                                        echo 'Jurisdiction: ', $row['Jurisdiction']."<br>";
                                        echo 'Room Capacity: ', $row['Capacity']."<br>";
                                        echo 'Available Equipment: ', $row['Equipment']."<br>";
                                      
                                         ?>    
                                                
                                      <!--End of query results-->
                                 </p>
                                
                                <form action="get.php" method = "post"> 
                                <button  name="<?php echo  $i ?>" class="btn btn-primary btn-sm" type="submit" >
                                Book Room 
                                </button>
                                </form>
                             

                            </div>
                            </div>
                            </div>
				<!--<div id="myModal" class="modal">
				 <div class="modal-content">
   				 <div class="modal-header">
      				<span class="close">&times;</span>
   				 </div>
    				<div class="modal-body">
   				   <h2>Are you sure you want to book this room?</h2>
   				 </div>
    				<div class="modal-footer">
    				  <button id="yes" type="button">YES</button>
 				  <button id="no" type="button">NO</button>
 				</div>
  				</div>
				</div>


                                
				<script type = "text/javascript">
				// Get the modal
					var modal = document.getElementById('myModal');

					// Get the button that opens the modal
					var btns = document.querySelectorAll('.btn'); 

					// Get the <span> element that closes the modal
					var span = document.getElementsByClassName("close")[0];
                    
                    var roomId = 0;
                   
					[].forEach.call(btns, function(el) {
  					el.onclick = function() {
                        
                        roomId = el.id;
                		modal.style.display = "block";
 						 }
					})
                    
                    
					
					no.onclick = function() {
  			 		 modal.style.display = "none";
					}
                    
                    yes.onclick = function() {
                       
  		                //window.location.href = 'makeBooking.php';
					}
                    

                   

					// When the user clicks on <span> (x), close the modal
					span.onclick = function() {
    					modal.style.display = "none";
					}

					// When the user clicks anywhere outside of the modal, close it
					window.onclick = function(event) {
    					if (event.target == modal) {
      					  modal.style.display = "none";
   					    }
					}
                    
                    function post(){
                        var name = "roomId";
                        $.post('gethint.php', {postname:name}, 
                        function(data){
                            
                        $('#result').html(data);

                    });
            
                    }
				</script> -->

                                <?php
                                
                                $i = $i + 1;}
                            }
                        ?>
                            </div>
                        </div>
                        
               
</div>
</body>
    
    <script type = "text/javascript" src = "//code.jquery.com/jquery-1.12.0.min.js">
                        function post(){
                        var name = roomId;
                        console.log(name);
                        $.post('gethint.php', {postname:name}, 
                        function(data){

                        $('#result').html(data);

                    });
            
                    }
                    
    
    
    </script>
    
</html>
