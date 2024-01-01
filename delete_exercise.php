<?php
    //DB Credentials
    $host = "303.itpwebdev.com";
    $user = "valani_db_user";
    $pass = "ITPShoaib303";
    $db = "valani_workouts_db";

    //connect DB
    $mysqli = new mysqli($host, $user, $pass, $db);

    // Check for MySQL Connection Errors
	if ($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}
    // Create SQL 
	$sql_workouts = "SELECT * FROM workouts;";
    
    // echo $sql_workouts;

    // Run SQL
	$results_workouts = $mysqli->query($sql_workouts);

    // Check for SQL Errors
	if ($results_workouts == false) {
		echo $mysqli->error;
		$mysqli->close();
		exit();
	}

    // Initialize variables
    $selected_workout_id = null;
    $results_exercises = null;
    $sql_add_exercises = "";
    

    // Check if workout ID is set and get exercises for that workout
    if (isset($_GET["workout_id"]) && !empty($_GET["workout_id"])) {
        $selected_workout_id = $_GET["workout_id"];
        if (isset($_GET['select_workout'])) {
            $sql_add_exercises = $sql_add_exercises . "SELECT 
                                exercises.name AS name, 
                                muscle_groups.muscle_group AS muscle_group, 
                                difficulty.difficulty AS difficulty,
                                exercises.equipment AS equipment
                            FROM workouts_exercises
                            LEFT JOIN exercises ON workouts_exercises.exercise_id = exercises.exercise_id
                            LEFT JOIN muscle_groups ON exercises.muscle_group_id = muscle_groups.muscle_group_id
                            LEFT JOIN difficulty ON exercises.difficulty_id = difficulty.difficulty_id
                            WHERE workouts_exercises.workout_id = " . $selected_workout_id . ";";
            $results_exercises = $mysqli->query($sql_exercises);
            if ($results_exercises == false) {
                echo $mysqli->error;
                $mysqli->close();
                exit();
            }
        }
    } else{
        // echo "No workout selected.";
    }

    // echo $sql_exercises;
    

     // Run SQL
	
    // Check for SQL Errors
	
    $new_workout = null;
    if (isset($_POST['new_workout']) && !empty($_POST['new_workout'])) {
        // Sanitize the input
        $new_workout = $mysqli->real_escape_string($_POST['new_workout']);

        // Insert the new workout into the database
        $insert_sql = "INSERT INTO workouts(workout_id, workouts) VALUES (null, '$new_workout');";
        if (!$mysqli->query($insert_sql)) {
            echo $mysqli->error;
            $mysqli->close();
            exit();
        }

    }

    // Close DB Connection
	$mysqli->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoaib Valani | Workout Log</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
      /*  @import url('https://fonts.googleapis.com/css2?family=Mooli&family=Zeyada&display=swap');
        img{
            width: 150px;
        }
        #navbar {
            z-index: 10; 
        }
        body {
            padding-top: 80px; 
        }
        #header {
            padding-top: 60px; 
            background-color: papayawhip;
            text-align: center;
            color: white;
        }
        #search-form .form-control, #search-form .btn {
            height: 38px;
        }
        .btn-block {
            display: block; 
            width: 100%; 
            /* padding: .375rem .75rem;  
            margin-bottom: 10px;
        }
        .logo {
            width: 150px;
            margin-right: 20px; 
            margin-top: 20px;
        }
        thead{
            color: black;
            margin-top: 10px; 
            background-color: papayawhip;
            text-align: center;
            list-style: none;
        }
        .container-fluid .row{
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }  
        li.list-group-item {
            display: flex;
            align-items: center;
            justify-content: space-around;
            list-style: none;
        }
        .container-fluid {
            width: 100%;
            max-width: 1400px; /* or any other value you prefer 
        }
        #content, #new-workout-form {
            width: 100%;
            /* display: none; 
            padding-bottom: 90px;
        }
        #footer {
            position: fixed;
            bottom: 0;

        }*/
        @import url('https://fonts.googleapis.com/css2?family=Mooli&family=Zeyada&display=swap');
        body {
            padding-top: 40px; /* Adjust for navbar height */
        }
        .logo {
            width: 150px;
            margin-top: 20px;
        }
        .container-fluid {
            max-width: 1400px;
        }
        #footer {
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .form-section {
            margin-bottom: 30px; /* Spacing between forms */
            margin-left: 20px;
        }
        #new-workout-form{
            padding: auto;
            margin: 30px;
            margin-left: 50px;
            z-index: 100;
            margin-bottom: 100px;
        }
        thead{
            color: black;
            margin-top: 10px; 
            background-color: papayawhip;
            text-align: center;
            list-style: none;
        }
    </style>
</head>
<body>

    <ul id="navbar">
        <li>
            <div class="navbar-left">
                <h2 id="name">NuFit</h2>
            </div>
        </li>
        <li>
            <div class="navbar-right">
                <ul>
                    <li><a href="home.html">Home</a></li>
                    <li><a id="active-menu" href="my_workout.php">My Workout</a></li>
                    <li><a href="explore.html">Explore</a></li>
                </ul>
            </div>
        </li>
    </ul>

    <div class="container-fluid mt-5">
        <div class="row align-items-center">
            <div class="col-auto">
                <img class="logo" src="img/Final_Project_logo1.png" alt="Logo">
            </div>
            <div class="col">
                <h1>Add an Exercise</h1>
            </div>
        </div> <!-- .row -->

        <div class="form-section mt-3">
        <h4>Please select a workout to which you would like to add this exercise.</h4>
        <br>
        <form action="add_confirmation.php" method="GET" class="w-100">
            <div class="row">
                <div class="col-12 col-md-4">
                    <select name="workout_id" id="workout-selector" class="form-control"> 
                        <option value="" disabled selected>Select Workout</option>
                            <?php while ($row = $results_workouts->fetch_assoc()) : ?>
                                <option value='<?php echo $row["workout_id"]; ?>'>
                                    <?php echo $row["workouts"]; ?>
                                </option>
                            <?php endwhile; ?>
                    </select>
                </div>
            
                <div class="col-6 col-md-2">
                    <button id="select-workout" name="select_workout" type="submit" class="btn btn-primary btn-block">Select Workout</button>
                </div>
            </div>
        </form>
        </div> <!-- row--> 
        <div class="col-6 col-md-2">
            <a href="explore.php" class="btn btn-primary" id="back_explore">Back to Explore</a>               
        </div>
    </div> <!-- .container -->
    <div id="footer">
        Contact Shoaib Valani at valani@usc.edu for inquiries and feedback!
    </div>


<script src="http://code.jquery.com/jquery-3.5.1.min.js"></script>

</body>
