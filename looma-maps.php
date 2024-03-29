 <!doctype html>
<!--
Author: Sophie, Henry, Morgan, Kendall
Email:
Filename: looma-histories.php
Date: July 2016
Description: Initial "maps" page. Takes the user to the different maps.
-->

    <h1 class="credit"> Created by Sophie, Morgan, Henry, Kendall</h1>

	<?php  $page_title = 'Looma Maps';
	include ("includes/header.php");
    require ('includes/mongo-connect.php');
    require('includes/looma-utilities.php');

    $mapDir = "../maps2018/mapThumbs/";
    $urlBegin = "looma-map.php?id=";

		function makeButton($file, $thumb, $dn) {
                    echo "<a href='" . $file . "'>";

                    echo "<button class='map  img'>";
                    //text and tooltip for BUTTON
                    echo "<span class='displayname'
                             class='btn btn-default'
                             data-toggle='tooltip'
                             data-placement='top'
                             title='" . $file . "'>" .
                              "<img src='" . $thumb . "'>" .
                             $dn . "</span>";
                    //finish BUTTON
                    echo "</button></a>";
              };  //end makeButton()
	?>

	<body>
	<div id="main-container-horizontal" class='scroll'>
		<h2 class="title"> <?php keyword("Looma Maps"); ?> </h2>
		<div class="center">
			<br>
     <?php
       //modifications for maps
        //***************************
        //make buttons for maps directory -- virtual folder, populated from maps collection in mongoDB
            $buttons = 1;
            $maxButtons = 3;

            echo "<table><tr>";

            $maps = $maps_collection->find();

             foreach ($maps as $map) {
                    echo "<td>";
                    if (isset($map['title'])) $dn = $map['title']; else $dn = "Map";
                    if (isset($map['thumb'])) $thumb = $mapDir . $map['thumb']; else $thumb = 'images/maps.png';
                    $id = $map['_id'];  //mongoID of the descriptor for this lesson
                    $link = $urlBegin.$id;
                    makeButton($link, $thumb, $dn);
                    echo "</td>";
                    $buttons++; if ($buttons > $maxButtons) {$buttons = 1; echo "</tr><tr>";};

            } //end FOREACH history
             echo "</tr></table>";
        ?>
<!--
		<a href="looma-history.php?chapterToLoad=1EN03">
			<button type="button" class="navigate"><?php keyword('Sports') ?>  </button>
		</a>
-->

		</div>
	</div>

	<?php include ('includes/toolbar.php'); ?>
   	<?php include ('includes/js-includes.php'); ?>

  </body>
</html>
