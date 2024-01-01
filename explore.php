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

    $sql_workouts = "SELECT * FROM workouts;";

    $results_workouts = $mysqli->query($sql_workouts);

    if ($results_workouts == false) {
        echo $mysqli->error;
        $mysqli->close();
        exit();
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
        img{
            width: 150px;
        }
        #header {
            padding-top: 60px; /* Adjusted to prevent overlap with fixed navbar */
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
            /* padding: .375rem .75rem;  */
            margin-bottom: 10px;
        }
        .container-fluid .row{
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }  
        .container-fluid {
            width: 100%;
            max-width: 1400px; /* or any other value you prefer */ 
        }
        #content {
            width: 100%;
            padding-bottom: 90px;
        }
        #info-row {
            width: auto; 
            max-width: 1200px;
            height: auto;
            margin: auto;
            display: flex;
            /* justify-content: space-between; */
            align-items: flex-start;
            background-color: papayawhip;
            flex-wrap: nowrap;
            margin-bottom: 20px;
        }
        .image-container, .info-container{
            /* display: flex; */
            padding: 10px;
            max-height: 400px;
        }
        .info-container{
            overflow-y: auto; 
            max-height: 600px;
        }
        #instructions-text{
            font-size: small;
        }
        #exercise-img img {
            width: 250px; 
            object-fit: contain;
        }
        #results-list{
            list-style: none;
            display: block;
        }
        #exercise-list-item{
            margin: 10px
        }
        #search-result{
            margin-bottom: 60px;
        }
        .exercise-list-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background-color: papayawhip;
            padding: 20px;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
            width: 100%;
        }
        .instructions-text {
            font-size: small;
        }

        .add-button {
            height: 40px;
            align-self: flex-start;
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
                    <li><a href="my_workout.php">My Workout</a></li>
                    <li><a id="active-menu" href="explore.php">Explore</a></li>
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
                <h1>Explore</h1>
            </div>
        </div> <!-- .row -->

        <div class="row mt-3">
            <form class="col-12" id="search-form">
                <div class="form-row">
                    <div class="col-8">
                        <input type="text" class="form-control" id="search-query" placeholder="Search Exercises by muscle...">
                    </div>
                    <div>
                        <select id="parameter" class="form-control">
                            <option value="" disabled selected>Select Parameter</option>
                            <option value="name">Name</option>
                            <option value="type">Type</option>
                            <option value="muscle">Muscle</option>
                            <option value="difficulty">Difficulty</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <button id="load-workout" type="submit" class="btn btn-primary btn-block">Search</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <select id="workout-selector" class="form-control">
                    <option value="" disabled selected>Select workout to add exercises to...</option>
                    <?php while ($row = $results_workouts->fetch_assoc()) : ?>
                        <option value="<?php echo $row['workout_id']; ?>">
                            <?php echo $row['workouts']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>

        <div id="search-result" class="row col-12 col-md-9 mt-4">
                <ul id="results-list">

                    <li id="exercise-list-item">
                        <h3 class="exercise-name">Incline Hammer Curl</h3>
                        <div id="info-row" class="d-flex">
                            
                            <div id="exercise-img" class="image-container">
                                <img src="https://static.strengthlevel.com/images/illustrations/incline-hammer-curl-1000x1000.jpg" alt="incline-hammer-curl-1000x1000">
                            </div> <!--IMAGE CONTAINER-->
                        
                            <div class="info-container">
                                <div id="info">
                                    <h4 id="info-head">Info</h4>
                                    <p id="muscle-group">Muscle group: Biceps</p>
                                    <p id="type">Type: Strength</p>
                                    <p id="equipment">Equipment: Dumbbell</p>
                                    <p id="difficulty">Difficulty: Beginner</p>
                                </div>
                                <div id="instructions">
                                    <h4 id="instructions-header">Instructions</h4>
                                    <p id="instructions-text">Choose a flat bench and place a dumbbell on each side of it. Place the right leg on top of the end of the bench, bend your torso forward from the waist until your upper body is parallel to the floor, and place your right hand on the other end of the bench for support. Use the left hand to pick up the dumbbell on the floor and hold the weight while keeping your lower back straight. The palm of the hand should be facing your torso. This will be your starting position. Pull the resistance straight up to the side of your chest, keeping your upper arm close to your side and keeping the torso stationary. Breathe out as you perform this step. Tip: Concentrate on squeezing the back muscles once you reach the full contracted position. Also, make sure that the force is performed with the back muscles and not the arms. Finally, the upper torso should remain stationary and only the arms should move. The forearms should do no other work except for holding the dumbbell; therefore do not try to pull the dumbbell up using the forearms. Lower the resistance straight down to the starting position. Breathe in as you perform this step. Repeat the movement for the specified amount of repetitions. Switch sides and repeat again with the other arm.  
                                        Variations: One-arm rows can also be performed using a high pulley or a low pulley instead of a dumbbell.</p>
                                </div>
                            </div><!--INFO CONTAINER-->
                        </div><!--INFO ROW-->
                    </li>

                </ul>
        </div> <!-- SEARCH RESULT -->        
    </div> <!-- .container -->
    <div id="footer">
        Contact Shoaib Valani at valani@usc.edu for inquiries and feedback! 
    </div>


<script src="http://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    var exercises;
    const apiKey = "sgmr9+T9/NgHqL63ArHM7g==ABGaFW1qtXnGdD56"
    function ourCallback(result) {
        console.log(result)
        console.log(result.exampleKey)
    }

    document.querySelector('#search-form').onsubmit = function(event){  
        event.preventDefault();         
        const query = document.querySelector('#search-query').value.trim();
        const parameter = document.querySelector('#parameter').value.trim();
        if (parameter.value === ''){
            document.querySelector('#parameter').value.trim() = 'muscle';
        }
        console.log(query) 

        const endpoint = "https://api.api-ninjas.com/v1/exercises?" + parameter +"=" + query;

        if (query.length > 0){
            $.ajax({
                method: 'GET',
                url: endpoint,
                headers: { 'X-Api-Key': apiKey},
                contentType: 'application/json',
                success: function(results) {
                    const resultList = document.querySelector('#results-list');
                    resultList.innerHTML = ''; // Clear existing results
                    console.log(results);
                    exercises = results;
                        for(var i=0, max=results.length; i<max; ++i)
                        {
                            resultList.appendChild(createExerciseListItem(exercises[i],i));
                        }
                        
                    
                },
                error: function ajaxError(jqXHR) {
                    console.error('Error: ', jqXHR.responseText);
                }
            });  
        return false;
                
        } else if(query.length === 0){
            document.querySelector("#search-error").innerHTML = "Please enter an exercise name, exercise type, muscle group, or difficulty level."
            return false;
        }else{
            document.querySelector("#search-error").innerHTML = ""
        }
    }
    function createExerciseListItem(exercise, index) {
        const li = document.createElement("li");
        li.className = "exercise-list-item";
        li.style.display = 'flex';
        li.style.justifyContent = 'space-between';
        li.style.alignItems = 'center';
        li.style.marginBottom = '20px';
        li.style.backgroundColor = '#f7f7f7';
        li.style.padding = '20px';

        const infoRow = document.createElement("div");
        infoRow.className = 'info-row';
        infoRow.style.display = 'flex';
        infoRow.style.alignItems = 'flex-start';
        infoRow.style.width = '100%';
        
        // const imageContainer = document.createElement("div");
        // imageContainer.className = 'image-container';
        // imageContainer.style.flex = '1';

        // const img = document.createElement("img");
        // img.src = exercise.image || 'img/Final_Project_logo1.png';
        // img.style.width = '250px';
        // img.style.height = 'auto';
        // img.style.objectFit = 'contain';

        const infoContainer = document.createElement("div");
        infoContainer.className = 'info-container';
        infoContainer.style.flex = '2';
        infoContainer.style.paddingLeft = '20px';
        infoContainer.style.overflowY = 'auto';
        infoContainer.style.maxHeight = '600px';

        const pName = document.createElement("h3");
        pName.textContent = exercise.name;

        const pMuscle = document.createElement("p");
        pMuscle.textContent = "Muscle group: " + exercise.muscle;
        // <span class="muscle-group" ></span>

        const pEquipment = document.createElement("p");
        pEquipment.textContent = "Equipment: " + exercise.equipment;

        const pType = document.createElement("p");
        pType.textContent = "Type: " + exercise.type;

        const pDifficulty = document.createElement("p");
        pDifficulty.textContent = "Difficulty: " + exercise.difficulty;

        const h4Instructions = document.createElement("h4");
        h4Instructions.className = 'instructions-header';
        h4Instructions.textContent = "Instructions";

        const pInstructions = document.createElement("p");
        pInstructions.className = 'instructions-text';
        pInstructions.textContent = exercise.instructions;

        // Add button creation
        const addButton = document.createElement("button");
        addButton.textContent = "Add";
        addButton.className = 'btn btn-primary';
        addButton.type = "button";
        addButton.style.height = '40px';
        addButton.style.marginLeft = '20px';
        addButton.setAttribute("onclick","handleAdd("+index+");");
        

        // imageContainer.appendChild(img);
        infoContainer.appendChild(pName);
        infoContainer.appendChild(pMuscle);
        infoContainer.appendChild(pType);
        infoContainer.appendChild(pEquipment);
        infoContainer.appendChild(pDifficulty);
        infoContainer.appendChild(h4Instructions);
        infoContainer.appendChild(pInstructions);

    
        infoRow.appendChild(infoContainer);
        li.appendChild(infoRow);
        li.appendChild(addButton);

        return li;
    }
    function handleAdd(index)
    {
            var exercise = exercises[index];
            var workoutId = document.querySelector('#workout-selector').value;
            // [{
            //     "name": "Incline Hammer Curls",
            //     "type": "strength",
            //     "muscle": "biceps",
            //     "equipment": "dumbbell",
            //     "difficulty": "beginner",
            //     "instructions": "Seat yourself on an incline bench with a dumbbell in each hand. You should pressed firmly against he back with your feet together. Allow the dumbbells to hang straight down at your side, holding them with a neutral grip. This will be your starting position. Initiate the movement by flexing at the elbow, attempting to keep the upper arm stationary. Continue to the top of the movement and pause, then slowly return to the start position."
            // },
            // {
            //     "name": "Wide-grip barbell curl",
            //     "type": "strength",
            //     "muscle": "biceps",
            //     "equipment": "barbell",
            //     "difficulty": "beginner",
            //     "instructions": "Stand up with your torso upright while holding a barbell at the wide outer handle. The palm of your hands should be facing forward. The elbows should be close to the torso. This will be your starting position. While holding the upper arms stationary, curl the weights forward while contracting the biceps as you breathe out. Tip: Only the forearms should move. Continue the movement until your biceps are fully contracted and the bar is at shoulder level. Hold the contracted position for a second and squeeze the biceps hard. Slowly begin to bring the bar back to starting position as your breathe in. Repeat for the recommended amount of repetitions.  Variations:  You can also perform this movement using an E-Z bar or E-Z attachment hooked to a low pulley. This variation seems to really provide a good contraction at the top of the movement. You may also use the closer grip for variety purposes."
            // }]
            var name = exercise["name"];
            var type = exercise["type"];
            var muscle = exercise["muscle"];
            var equipment =exercise["equipment"];
            var difficulty = exercise["difficulty"];
            var instructions = exercise["instructions"];

            $.ajax({
                method: 'POST',
                url: 'add_exercises.php',
                contentType: 'application/json',
                data: JSON.stringify({      // Convert your data to a JSON string
                            name: exercise["name"],
                            type: exercise["type"],
                            muscle: exercise["muscle"],
                            equipment: exercise["equipment"],
                            difficulty: exercise["difficulty"],
                            instructions:  exercise["instructions"],
                            workout_id: workoutId
                        }),
                success: function(results) {
                 if(results === "success"){
                    Swal.fire({
                        title: "Success!",
                        text: "Exercise added!",
                        icon: "success"
                    });
                 }
                 else{
                    Swal.fire({
                        title: "Error.",
                        text: results,
                        icon: "error"
                    });
                 }
                },
                error: function ajaxError(jqXHR) {
                    console.error('Error: ', jqXHR.responseText);
                }
            });  
    }
    

</script>
</body>
</html>