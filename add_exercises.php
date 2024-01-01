<?php
    error_reporting(E_ERROR);

    // Get JSON as a string
    $json_str = file_get_contents("php://input");

    // Decode the JSON string into a PHP associative array
    $data = json_decode($json_str, true);

    $name = $data["name"];
    $muscle = $data["muscle"];
    $equipment = $data["equipment"];
    $type = $data["type"];
    $difficulty = $data["difficulty"];
    $instructions  = $data["instructions"];
    $workout_id = $data["workout_id"];

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

    try{
        $sql_muscle = "SELECT muscle_group_id FROM muscle_groups WHERE muscle_group = '$muscle';";
        $sql_type = "SELECT type_id FROM types WHERE type_name = '$type';";
        $sql_difficulty = "SELECT difficulty_id FROM difficulty WHERE difficulty = '$difficulty';";

        

        $results_sql_muscle = $mysqli->query($sql_muscle);//->fetch_assoc()["muscle_group_id"];
        //var_dump($results_sql_muscle);
        $results_sql_type = $mysqli->query($sql_type);
        $results_sql_difficulty = $mysqli->query($sql_difficulty);
        //check if all successful
    if(!$results_sql_muscle||!$results_sql_type||!$results_sql_difficulty){
        echo "Database Error in subtable. Good luck.";
        $mysqli->close();
        return;
    }
    //fetch the id
    $muscle_id = $results_sql_muscle->fetch_assoc()["muscle_group_id"];
    $type_id = $results_sql_type->fetch_assoc()["type_id"];
    $difficulty_id = $results_sql_difficulty->fetch_assoc()["difficulty_id"];

    if(!isset($workout_id) || empty($workout_id)){
        $error_select = "Please select a workout";
        echo $error_select;
    }else{
    
        $sql = "INSERT INTO exercises (name, muscle_group_id, equipment, difficulty_id, type_id, instructions)
                VALUES (?,?,?,?,?,?);";
        
        $ps = $mysqli->prepare($sql);
        $ps->bind_param("sisiis", $name, $muscle_id, $equipment, $difficulty_id, $type_id, $instructions);

        $results = $ps->execute();

        if (!$results) {
            echo $mysqli->error;
            echo $ps->error;
            $mysqli->close();
            exit();
        }
        $exercise_id = $mysqli->insert_id;
    
        $sql_insert_workout_exercise = "INSERT INTO workouts_exercises (workout_id, exercise_id) VALUES (?, ?);";
        $ps_workout_exercise = $mysqli->prepare($sql_insert_workout_exercise);
        $ps_workout_exercise->bind_param("ii", $workout_id, $exercise_id);
        if(!$ps_workout_exercise->execute()) {
            echo $ps_workout_exercise->error;
            $mysqli->close();
            exit();
        }

        echo "success";
        $mysqli->close();
    }

    }
    catch(Exception $e){
        echo $e->getMessage();
    }


   

    

?>
