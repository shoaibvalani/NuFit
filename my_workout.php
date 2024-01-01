<?php
    session_start();
    // Notify User of Exercise Deletion Outcome
    if (isset($_SESSION['exercise_deletion_success'])) {
        // echo "<p>Exercise deleted successfully!</p>";
        unset($_SESSION['exercise_deletion_success']);
    }
    if (isset($_SESSION['exercise_deletion_error'])) {
        echo "<p>Error deleting exercise.</p>";
        unset($_SESSION['exercise_deletion_error']);
    }
    if (isset($_SESSION['deletion_success'])) {
        echo "<p>Exercise deleted successfully!</p>";
        unset($_SESSION['deletion_success']);
    }
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
    $sql_exercises = "";
    $delete_workout_id = null;
    $delete_workout = "";

    // Check if workout ID is set and get exercises for that workout
    if (isset($_GET["workout_id"]) && !empty($_GET["workout_id"])) {
        $selected_workout_id = $_GET["workout_id"];
        if (isset($_GET['load_workout'])) {
            $sql_exercises = $sql_exercises . "SELECT 
                                workouts_exercises.exercise_id AS exercise_id,
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
        } elseif (isset($_GET['delete_workout'])){
            $deletion_was_successful;
            $delete_exercises_from_workouts = "DELETE FROM workouts_exercises WHERE workout_id = ?";
            if ($stmt = $mysqli->prepare($delete_exercises_from_workouts)) {
                $stmt->bind_param("i", $selected_workout_id);
                $stmt->execute();
                $stmt->close();
            }

            $delete_workout = $delete_workout . "DELETE FROM workouts WHERE workout_id = $selected_workout_id;";
            $delete_result = $mysqli->query($delete_workout);
            if ($delete_result == false) {
                echo $mysqli->error;
                $mysqli->close();
                exit();
            }else{
                $deletion_was_successful = true;
            }
            
            if ($deletion_was_successful) {
                $_SESSION['deletion_success'] = true;
                header("Location: my_workout.php"); // Refresh the page
                exit();
            }
        }
    } else{

    }

if (isset($_POST['delete_exercise_id']) && isset($_POST['delete_from_workout_id'])) {

    $exercise_id_to_delete = $_POST['delete_exercise_id'];
    $delete_from_workout_id = $_POST['delete_from_workout_id'];
    
    var_dump($_POST);

    $delete_sql = "DELETE FROM workouts_exercises WHERE exercise_id = ? AND workout_id = ?;";
    
    if ($stmt_del_exercise = $mysqli->prepare($delete_sql)) {
        $stmt_del_exercise->bind_param("ii", $exercise_id_to_delete, $delete_from_workout_id);

        if ($stmt_del_exercise->execute()) {
            $_SESSION['exercise_deletion_success'] = true;
        } else {
            $_SESSION['exercise_deletion_error'] = true;
        }

        $stmt_del_exercise->close();

        header("Location: my_workout.php?workout_id=" . $delete_from_workout_id);
        exit();
    } else {
        echo "Error preparing statement: " . $mysqli->error;
    }
}


	
    $new_workout = null;
    if (isset($_POST['new_workout']) && !empty($_POST['new_workout'])) {
        // Sanitize the input
        $new_workout = $mysqli->real_escape_string($_POST['new_workout']);

        // Insert the new workout into the database
        $insert_sql = "INSERT INTO workouts(workout_id, workouts) VALUES (null, '$new_workout');";
        if(($mysqli->query($insert_sql))) {
            header("Location: my_workout.php");
            exit();
        }else{
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
                    <li><a href="explore.php">Explore</a></li>
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
                <h1>My Workout Log</h1>
            </div>
        </div> <!-- .row -->

        <div class="form-section mt-3">
        <form action="my_workout.php" method="GET" class="w-100">
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
                    <button id="load-workout" name="load_workout" type="submit" class="btn btn-primary btn-block">Load Workout</button>
                </div>
                <div class="col-6 col-md-2">
                    <button id="prio-delete" name="delete_workout" class="btn btn-outline-danger btn-block" onclick="return confirm('Are you sure you want to delete this workout?')"
                    >Delete Workout</button>
                </div>
            </div>
        </form>
        </div> <!-- row--> 
        
        <?php if ($selected_workout_id && $results_exercises): ?>
        <div id="content" class="row col-12 mt-4">
            <div class="col-12 mt-4">

                <!-- Showing <span id="shown-results" class="font-weight-bold">0</span> of <span id="num-results" class="font-weight-bold">0</span> result(s). -->

            </div>
            <div id="main-content" class="col-12 col-sm-8 mt-3">
                <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Exercise</th>
                        <th>Primary Muscle Group</th>
                        <th>Difficulty</th>
                        <th>Equipment</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <ul id="exercises-list" class="list-group">
                        <?php while ($row = $results_exercises->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo $row["name"]; ?></td>
                                <td><?php echo $row["muscle_group"]; ?></td>
                                <td><?php echo $row["difficulty"]; ?></td>
                                <td><?php echo $row["equipment"]; ?></td>
                                <td>
                                <form action="my_workout.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this exercise?')">
                                    <input type="hidden" name="delete_exercise_id" value="<?php echo $row['exercise_id']; ?>">
                                    <input type="hidden" name="delete_from_workout_id" value="<?php echo $selected_workout_id; ?>">
                                    <button type="submit" class="btn btn-outline-danger">
                                        Delete
                                    </button>
                                </form>
							</td>
                            </tr>
                        <?php endwhile; ?>
                </tbody>
                </table>
                  
                <?php else: ?>
                    <!-- <p>No exercises found for the selected workout.</p -->
                <?php endif; ?>

            </div> <!-- .col -->
        </div> <!-- .row -->

        <div id="new-workout-form" class="form-section mt-4">
            <h3>Add New Workout</h3>
            <form action="my_workout.php" method="POST"> 
                <div class="row"> <!-- Add a row to contain the columns -->
                    <div class="col-9 col-md-4 form-group">
                        <input type="text" name="new_workout" class="form-control" id="workout-name" placeholder="Enter workout name">
                    </div>
                    <div class="col-6 col-md-2">
                        <button id="add-workout" type="submit" class="btn btn-primary">Add Workout</button>
                    </div>
                </div>
            </form>
        </div>

        


    </div> <!-- .container -->
    <div id="footer">
        Contact Shoaib Valani at valani@usc.edu for inquiries and feedback!
    </div>


<script src="http://code.jquery.com/jquery-3.5.1.min.js"></script>

</body>
</html>