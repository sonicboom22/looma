<!doctype html>
<!--
Author: Alexa , Luke , Catie , Meg, Sun-Mi  (2018)
Filename: looma-game.php
Date: June 2018
Description: Creates a game with a scoreboard, timer, and prompts. Information accessed through database.
-->
  <?php $page_title = 'Looma Team Game';

    include ("includes/header.php");
    include ("includes/mongo-connect.php");
  ?>

    <link href='css/looma.css'         rel='stylesheet' type='text/css'>
    <link href='css/looma-gameNEW.css' rel='stylesheet' type='text/css'>
    <link href='css/leaflet.css'       rel='stylesheet' type='text/css'>


</head>

<body>

    <div class="container">
        <h1 class="credit"> Created by Luke, Meg, Catie, Alexa and Sun-Mi </h1>

        <?php include ('includes/looma-control-buttons.php');?>

        <?php 
        //include ('looma-game-utilities.php');
        ?>

      <?php 
        // $query = array('_id' => $_id);
        // $cursor = $sienna_collection->find($query);
        // foreach ($cursor as $game)
        // {
        //   $doc = $game;
        // }

        // $title = array_key_exists('name', $doc) ? $doc['name'] : null;
        // $game_type = array_key_exists('presentation_type', $doc) ? $doc['presentation_type'] : null;
        // $time_limit = array_key_exists('timeLimit', $doc) ? $doc['timeLimit'] : null;
        // $prompts = array_key_exists('prompts', $doc) ? $doc['prompts'] : null;

        // $numQuestions = sizeOf($prompts);
        // $currQuestion = 0;
        // $num_teams = 1;
        // $scores = array_fill(0, $num_teams, 0);

        // $numQuestions = 2;
        // $title = "TITLE";
        // $time_limit = 100;
        // $scores = [0];
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : null;
        $class = isset($_REQUEST['class']) ? $_REQUEST['class'] : null;
        $subj = isset($_REQUEST['subj']) ? $_REQUEST['subj'] : null;

        echo '<div class="header">';
        echo '<h1 id="gameTitle"</h1>';
        echo '</div>';

        echo '<div hidden class="thegameframe" id="thegameframe" data-gameid="' . $id .'" data-gametype="'.$type.'" data-randclass="'.$class.'" data-randsubj="'.$subj.'">';
            echo '<div id="top">';
                echo '<span id="current-team"></span>';
                echo '<span id="question-number"></span>';
                echo '<span id="question"></span>';
                echo '<button id="next"></button>';
            echo '</div>';
            echo '<div id="game"></div>';
            echo '<div id="gameOverFrame" hidden><h2 id="message"></h2><div id="scoreList"></div></div>';
          echo '</section>';
        echo '</div>';

        echo '<div id="timer" hidden>';
            echo '<h2 id="timer-message">Timer</h2>';
            echo '<h3 id="timer-count" title="ticking"></h3>';
        echo '</div>';

        echo '<div id="scoreboard" hidden>';
            echo '<h2 id="score-message">Score Board:</h2>';

            echo '<div id="teamscore-1" hidden>';
                echo '<p>Team 1: <span  class="teamscore"></span></p>';
                echo '<div id="progress-1" class="progress-bar"><div class="sienna-progress"></div></div>';
            echo '</div>';

              echo '<div id="teamscore-2" hidden>';
                  echo '<p>Team 2: <span  class="teamscore"></span></p>';
                  echo '<div  id="progress-2" class="progress-bar"><div class="sienna-progress"></div></div>';
              echo '</div>';

              echo '<div id="teamscore-3" hidden>';
                  echo '<p>Team 3: <span  class="teamscore"></span></p>';
                  echo '<div  id="progress-3" class="progress-bar"><div class="sienna-progress"></div></div>';
              echo '</div>';

              echo '<div id="teamscore-4" hidden>';
                  echo '<p>Team 4: <span  class="teamscore"></span></p>';
                  echo '<div id="progress-4" class="progress-bar"><div class="sienna-progress"></div></div>';
             echo '</div>';
        echo '</div>';

      echo '<section id="optionsframe">';
          echo '<h2 id="numTeamsHeader"> Select Number of Teams</h2>';
          echo '<div id="teamoptions">';
          for ($i = 1; $i <= 4; $i++) {
              echo '<button class="teamnumber button-8" data-team="' . $i . '">' . $i . '</button>';
          };
          echo '</div>';
      echo '</section>';
      ?>

    <div class = "toolbar">
      <?php include ('includes/toolbar.php'); ?>
    </div>

    </div>


</body>
    <?php include ('includes/js-includes.php'); ?>
    <script type="text/javascript" src="js/looma-gameNEW.js"></script>
    <script type="text/javascript" src="js/looma-timer.js"></script>
    <script type="text/javascript" src="js/looma-scoreboard.js"></script>
    <script type="text/javascript" src="js/leaflet.js"></script>



</html>
