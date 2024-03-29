 <!doctype html>
<!--
Name: Skip
Email: skip@stritter.com
Owner: VillageTech Solutions (villagetechsolutions.org)
Date: 2015 10
Revision: Looma 2.0.0
File: looma-chapters.php
Description:  displays a list of chapters (en and np) and an activities button for each chapter
for a textbook (class/subject) for Looma 2
-->

<?php $page_title = 'Looma Chapters';
	  include ('includes/header.php');
      include ('includes/mongo-connect.php');

?>
</head>

<body>

	<?php
			function thumbnail ($fn) {
				//given a CONTENT filename, generate the corresponding THUMBNAIL filename
				//find the last '.' in the filename, insert '_thumb.jpg' after the dot
				//returns "" if no '.' found
				//example: input 'aaa.bbb.mp4' returns 'aaa.bbb_thumb.jpg' - this is the looma standard for naming THUMBNAILS
		 		$dot = strrpos($fn, ".");
				if ( ! ($dot === false)) { return substr_replace($fn, "_thumb.jpg", $dot, 10);}
				else return "";
			} //end function THUMBNAIL


            $class = trim($_GET['class']);  //from MONGO - format is "class1", "class2", etc
            $grade = trim($_GET['grade']);  // display name of $class - format is "Grade 1", etc
            $subject = trim($_GET['subject']) ;
            $prefix = trim($_GET['prefix']) ;

    echo "<div id='main-container-horizontal' class='scroll'>";
    echo "<div  class='scroll'>";
        if ($subject === "social studies") $caps = "Social Studies"; else $caps = ucfirst($subject);
        echo "<h2 class='title'>Chapters for " . $grade . " " . $caps . "</h2>";

			//get a textbook record for this CLASS and SUBJECT
			$query = array('class' => $class, 'subject' => $subject, 'prefix' => $prefix);
			//returns only these fields of the textbook record
			$projection = array('_id' => 0,
								'prefix' => 1,
							    'fn' => 1,
							    'fp' => 1,
							    'dn' => 1,
							    'nfn' => 1,
								'ndn' => 1,
								);

			$tb = $textbooks_collection -> findOne($query, $projection);

			//echo "result of DB query: <br>";
			//print_r($tb);
			//echo "<br><br>";

			$tb_dn = array_key_exists('dn', $tb) ? $tb['dn'] : null;		//dn is textbook displayname
			$tb_fn = array_key_exists('fn', $tb) ? $tb['fn'] : null;		//fn is textbook filename
			$tb_fp = array_key_exists('fp', $tb) ? $tb['fp'] : null;		//fp is textbook filepath
			$tb_fp = "../content/" . $tb_fp;
			$tb_nfn = array_key_exists('nfn', $tb) ? $tb['nfn'] : null;	//nfn is textbook native filename
			$tb_ndn = array_key_exists('ndn', $tb) ? $tb['ndn'] : null;		//dn is textbook displayname
			$prefix = array_key_exists('prefix', $tb) ? $tb['prefix'] : null; //prefix is the chapter-id starting characters, e.g. "2EN"

			echo "<br><br><table>";
			echo "<tr>";

			if ($tb_fn != null)
			    echo "<th><button class='heading img' id='englishTitle' disabled>" .
                       // str_replace("Class","Grade ",$tb_dn) .
                       $grade .
                      "<img src=" . $tb_fp . thumbnail($tb_fn) . "></button></th>";
			else
			    echo "<th></th>";

			if ($tb_nfn != null) echo "<th><button class='heading img' id='nativeTitle' disabled> $tb_ndn
									  <img src=" . $tb_fp . thumbnail($tb_nfn) . "></button></th>";

			else                echo "<th></th>";

                echo "<th><button class='heading img activities' disabled>"; keyword('Lesson'); echo "</button></th>";
                echo "<th><button class='heading img activities' disabled>"; keyword('Activities'); echo "</button></th>";

			echo "</tr>";

            $prefix_as_regex = "^" . $prefix . "\d"; //insert the PREFIX into a REGEX

            $query = array('_id' => array('$regex' => $prefix_as_regex));

			$projection = array('_id' => 1,
							    'pn' => 1,
							    'npn' => 1,
							    'dn' => 1,
							    'ndn' => 1,
								);
			$chapters = $chapters_collection -> find($query, $projection);
			$chapters->sort(array('_id' => 1)); //NOTE: this is MONGO sort() method for mongo cursors
                                                // this sort is on '_id' which is the "ch_id" of the chapter
                                                // we must always maintain chapter IDs so that their SORT() order is the natural sort order

			// for each CHAPTER in the CHAPTERS	array,
			// display buttons for textbook, 2nd language textbook (if any) and
			// an ACTIVITIES button that has a data-activity attribute
			// that holds the MongoDB ObjectId for this chapter (for looking up the activities list when needed)

			foreach ($chapters as $ch) {
		        echo "<tr>";

				$ch_dn =  array_key_exists('dn', $ch) ? $ch['dn'] : $tb_dn;
				        //$ch_dn is chapter displayname
				$ch_ndn = array_key_exists('ndn', $ch) ? $ch['ndn'] : $ch_dn;
				        //$ch_ndn is native displayname
				$ch_pn =  array_key_exists('pn', $ch) ? $ch['pn'] : null;
				        //$ch_pn is chapter page number
				$ch_npn = array_key_exists('npn', $ch) ? $ch['npn'] : null;
				        //$ch_npn is chapter native page number
				$ch_id  = array_key_exists('_id', $ch) ? $ch['_id'] : null;
				        //$ch_id is chapter ID string

				// display chapter button for first [english] textbook, if any
				if ($tb_fn && $ch_pn) { echo "<td><button class='chapter'
									  data-fn='$tb_fn'
									  data-fp='$tb_fp'
									  data-ch='$ch_id'
									  data-ft='pdf'
									  data-zm='160'
					                  data-pg='$ch_pn'>
					                  $ch_dn
					                  </button></td>";

						  }
				else {echo "<td><button class='chapter' style='visibility: hidden'></button></td>";}

				// display chapter button for 2nd [native] textbook, if any
				if ($tb_nfn && $ch_npn) { echo "<td><button class='chapter'
									  data-fn='$tb_nfn'
									  data-fp='$tb_fp'
									  data-ft='pdf'
					                  data-pg='$ch_npn'
					                  data-ch='$ch_id'>
					                  $ch_ndn
					                  </button></td>";
						  }
				else {echo "<td><button class='chapter' style='visibility: hidden'></button></td>";}


                // display a button for the lesson plans for this chapter
                $query = array('ch_id' => $ch_id, 'ft' => 'lesson');
                $projection = array('_id' => 0,
                                'mongoID' => 1,
                                'dn' => 1
                                );

                //check in the database to see if there are any LESSON PLANS for this CHAPTER. if so, create a button
                // NOTE: current code only finds the FIRST lesson for the chapter.
                // expand in the future to allow multiple lessons per chapter
                $lesson = $activities_collection -> findOne($query, $projection);

                if ($lesson) {
                    echo "<td><button class='lesson'
                            data-ch='$ch_id'
                            data-chdn='" .
                            $lesson['dn'] .
                            "' data-ft='lesson'
                            data-id='" .
                            $lesson['mongoID'] .
                            "'>";
                    keyword('Lesson');
                    echo "</button></td>";
                }

                else {echo "<td><button class='activity' style='visibility: hidden'></button></td>";}




				// finally, display a button for the activities of this chapter with data-activity=CHAPTER_ID key value
				// first check whether there are any activities for this chapter and make the button invisible if not

                /* change DEC 18: always show Activities button

                    //get the activities for this chapter
                    $query = array('ch_id' => $ch_id);
                    //returns only these fields of the activity record
                    $projection = array('_id' => 0,
                                    'ch_id' => 1,
                                    );

                    //check in the database to see if there are any ACTIVITIES for this CHAPTER. if so, create a button
                    $activities = $activities_collection -> findOne($query, $projection);

                    if ($activities)

                    { */
					echo "<td><button class='activities'
							data-ch='$ch_id'
							data-chdn='$ch_dn'>";
                    keyword('Activities');
                    echo "</button></td>";
                    /*

                    } else echo "<td></td>";

                    echo "</tr>";
                    */
			}
			echo "</table></div></div>";
	?>


   	<?php include ('includes/toolbar.php'); ?>
   	<?php include ('includes/js-includes.php'); ?>
  	<script src="js/looma-chapters.js"></script>          <!-- Looma Javascript -->
</body>
