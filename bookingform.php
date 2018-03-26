<?php
    session_start();
    include('./my_connect.php');
    $con=get_mysqli_conn();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>Preferences</title>

        <!-- Required meta tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href = "css/global.css" type="text/css" rel = "stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Work+Sans" rel="stylesheet">

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        
        <style>
            h1{
                /*font-family: 'Work Sans', sans-serif;*/
                text-align: center;
                
            }
            
            .form-container{
                
                padding: 30px 45px;
            }
            
            /* h2{
                font-family: 'Work Sans', sans-serif;

                
            }*/
        </style>
    </head>

    <body>
        
        <nav class="navbar navbar-expand-lg justify-content-between">
            <span class="nav-text">
                    <a>
                    
                        <?php
   
                            print_r("Welcome " . ' ' . $_SESSION["username"]);
  
                        ?>
                    
                    </a>
            </span>
            <a class="navbar-brand">
                <h3>
                <img src="images/uw-shield-logo.svg" width="30" height="30" class="d-inline-block align-top" alt="">

                UW BookMi </h3>
            </a>
            <span class="nav justify-content-end">
                <li class="nav-item">
                        
                    <a  class="btn btn-primary" href='logout.php' role="button" class="nav-link" href="index.html">Sign Out</a>
                            
                </li>
            </span>
        </nav>
        <div class="container-fluid">
            <div class="row">
                <div class="col-8">
                <div class ="form-container">
                
                    <h2>Preferences</h2>
                    <form action="testpage.php" method="GET" class="bg-light">
                        <div class="card">
                        <div class="card-body">
                        <div class="form-group">
                            <label for="date">Check-In Date</label>
                            <input type="date" class="form-control" id="date" name="date" required/>
                        </div>
                        <!-- Different Timeslot 
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="start time">Start Time</label>
                                    <input type="time" class="form-control" id="start time"/>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="end time">End Time</label>
                                    <input type="time" class="form-control" id="end time"/>
                                </div>
                            </div>
                        </div>
                        -->
                        <div class="form-group">
                            <label for="time">Timeslots</label>
                            <select multiple size="5" id="time" name="time[]" class="form-control" required>
                                <option value="" selected disabled hidden/>
                                <?php $result = $con->query("SELECT * FROM Timeslot");
                                while($rows = $result->fetch_assoc()) {
                                ?>
                                <option value="<?php echo $rows['SlotId']; ?>">
                                <?php $formatted_datetime1 = date("g a", strtotime($rows['StartTime']));
                                $formatted_datetime2 = date("g a", strtotime($rows['EndTime']));
                                echo ($formatted_datetime1 . ' to ' . $formatted_datetime2); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="capacity"># of People</label>
                            <input type="number" class="form-control" min="1" max="99" id="capacity" name="capacity" required>
                        </div>
                        <div class="form-group">
                            <label for="building">Building (optional)</label>
                            <select id="building" class="form-control" name="building">
                                <option selected value="">No preference (default)</option>
                                <?php $result = $con->query("SELECT * FROM Building");
                                    while($rows = $result->fetch_assoc()) {
                                ?>
                                <option value="<?php echo $rows['BuildingId']; ?>"><?php echo $rows['BuildingName']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" id="submit" value="Submit"/>
                        </div>
                            </div>
                        </div>
                    </form>
                </div>
                </div>
                
                <div class="col-sm-4">
                
                <div class="form-container">
                 <h2>Current Booking(s)</h2>  
                        <?php   $newdate = date('Y-m-d');
                                        $result = $con->query("SELECT  b.BuildingName, r.RoomNumber, r.Capacity, r.Equipment, o.Date, COUNT(o.SlotId), r.RoomId
                                            FROM Booking o NATURAL JOIN Room r NATURAL JOIN Building b
                                            WHERE UserId='".$_SESSION['username']."' 
                                            GROUP BY b.BuildingName, r.RoomNumber, r.Capacity, r.Equipment, o.Date ,r.RoomId ");
                                            $i = 1;
                                            $resultCheck = mysqli_num_rows($result);
                                            while ($resultCheck > 0) {
                                                $rows = $result->fetch_assoc();
                                        ?>
                                            <div class="row">
                                            <div class="col">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title">
                                                            <?php
                                                                echo $rows['BuildingName'] ."<br>"; 
                                                                echo 'Room: ', $rows['RoomNumber']."<br>"
                                                            ?>
                                                        </h5>
                                                            <div>
                                                                <?php 
                                                                    echo 'Capacity: ', $rows['Capacity'] ."<br>"; 
                                                                    echo 'Equipment: ', $rows['Equipment'] . "<br>";
                                                                    echo  'Date of Booking: ', $rows['Date']; 
                                                                ?>
                                                            </div>
                                                            <form action="deleteBooking.php" method = "post"> 
                                                             <button  name="<?php echo  $i ?>" class="btn btn-primary btn-sm" type="submit" >
                                                                Cancel Booking 
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                                                <?php $i = $i + 1; $resultCheck = $resultCheck -1;} ?>
                    
                </div>
                </div>
            </div>
        </div>
    </body>

    <!-- Javascript files -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</html>
