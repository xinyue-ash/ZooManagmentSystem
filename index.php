<?php
		//this tells the system that it's no longer just parsing html; it's now parsing PHP
	include("animal_carer.php");
        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = NULL; // edit the login credentials in connectToDB()
        $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

        function debugAlertMessage($message) {
            global $show_debug_alert_messages;

            if ($show_debug_alert_messages) {
                echo "<script type='text/javascript'>alert('" . $message . "');</script>";
            }
        }

        function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
            //echo "<br>running ".$cmdstr."<br>";
            global $db_conn, $success;

            $statement = OCIParse($db_conn, $cmdstr); 
            //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
                echo htmlentities($e['message']);
                $success = False;
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
                echo htmlentities($e['message']);
                $success = False;
            }

			return $statement;
		}

        function executeBoundSQL($cmdstr, $list) {
            /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
		In this case you don't need to create the statement several times. Bound variables cause a statement to only be
		parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection. 
		See the sample code below for how this function is used */

			global $db_conn, $success;
			$statement = OCIParse($db_conn, $cmdstr);

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn);
                echo htmlentities($e['message']);
                $success = False;
            }

            foreach ($list as $tuple) {
                foreach ($tuple as $bind => $val) {
                    echo $val;
                    echo "<br>".$bind."<br>";
                    OCIBindByName($statement, $bind, $val);
                    unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
				}

                $r = OCIExecute($statement, OCI_DEFAULT);
                if (!$r) {
                    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
                    echo htmlentities($e['message']);
                    echo "<br>";
                    $success = False;
                }
            }
        }
function printResult($result) { //prints results from a select statement
            echo "<br>Retrieved data from table demoTable:<br>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Name</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["ID"] . "</td><td>" . $row["NAME"] . "</td></tr>"; //or just use "echo $row[0]" 
            }

            echo "</table>";
        }

        function connectToDB() {
            global $db_conn;

            // Your username is ora_(CWL_ID) and the password is a(student number). For example, 
			// ora_platypus is the username and a12345678 is the password.
            $db_conn = OCILogon("ora_ashleyue", "a86285756", "dbhost.students.cs.ubc.ca:1522/stu");

            if ($db_conn) {
                debugAlertMessage("Database is Connected");
                return true;
            } else {
                debugAlertMessage("Cannot connect to Database");
                $e = OCI_Error(); // For OCILogon errors pass no handle
                echo htmlentities($e['message']);
                return false;
            }
        }

        function disconnectFromDB() {
            global $db_conn;

            debugAlertMessage("Disconnect from Database");
            OCILogoff($db_conn);
        }
function handleResetRequest() {
            global $db_conn;
            // Drop old table
            // AC:need to modify 
            executePlainSQL("DROP TABLE demoTable");

            // Create new table
            echo "<br> creating new table <br>";
            executePlainSQL("CREATE TABLE demoTable (id int PRIMARY KEY, name char(30))");
            //executePlainSQL("CREATE TABLE demoTable (id int PRIMARY KEY, name char(30))");
            executePlainSQL("CREATE TABLE Zones
	                        (zoneName VARCHAR(20),
	                        numAnimals INTEGER,
	                        PRIMARY KEY (zoneName));");

            executePlainSQL("CREATE TABLE Zone_Shortage 
	                        (zoneName VARCHAR(20),
	                        supplyShortage VARCHAR(20),
	                        PRIMARY KEY (zoneName, supplyShortage),
	                        FOREIGN KEY (zoneName) references Zones ON DELETE CASCADE);");

            executePlainSQL("CREATE TABLE Animal_BasicInfo 
	                        (animalID INTEGER,
	                        gender VARCHAR(10),
	                        needVet INTEGER,
	                        since DATE,
	                        zoneName VARCHAR(20),
	                        species VARCHAR(20),
	                        PRIMARY KEY (animalID),
	                        FOREIGN KEY (zoneName) references Zones);");

            executePlainSQL("CREATE TABLE Animal_Carers
	                        (carerID INTEGER,
	                        cname VARCHAR(20),
	                        workingLocation VARCHAR(20),
	                        toolSetID INTEGER,
	                        expertise VARCHAR(20),
	                        feedingSchedule VARCHAR(20),
	                        CHECK ((feedingSchedule IS NOT NULL) 
		                    OR (toolSetID IS NOT NULL) 
		                    OR (expertise  IS NOT NULL)),
	                        PRIMARY KEY (carerID));");

            executePlainSQL("CREATE TABLE Animal_Carers
	                        (carerID INTEGER,
	                        cname VARCHAR(20),
	                        workingLocation VARCHAR(20),
	                        toolSetID INTEGER,
	                        expertise VARCHAR(20),
	                        feedingSchedule VARCHAR(20),
	                        CHECK ((feedingSchedule IS NOT NULL) 
		                    OR (toolSetID IS NOT NULL) 
		                    OR (expertise  IS NOT NULL)),
	                        PRIMARY KEY (carerID));");
            OCICommit($db_conn);
        }
// HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('resetTablesRequest', $_POST)) {
                    handleResetRequest();
                } else if (array_key_exists('updateQueryRequest', $_POST)) {
                    handleUpdateRequest();
                } else if (array_key_exists('insertQueryRequest', $_POST)) {
                    handleInsertRequest();
                }else if (array_key_exists('insertZoneShortage', $_POST)){ // ac: I added this for now 
                    insertZoneShortage();
                }

                disconnectFromDB();
            }
        }
// HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('resetTablesRequest', $_POST)) {
                    handleResetRequest();
                } else if (array_key_exists('updateQueryRequest', $_POST)) {
                    handleUpdateRequest();
                } else if (array_key_exists('insertQueryRequest', $_POST)) {
                    handleInsertRequest();
                }else if (array_key_exists('insertZoneShortage', $_POST)){ // ac: I added this for now 
                    insertZoneShortage();
                }else if (array_key_exists('updateVet', $_POST)){ // ac: I added this for now 
                    updateVet();
                }

                disconnectFromDB();
            }
        }

        // HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('countTuples', $_GET)) {
                    handleCountRequest();
                }

                disconnectFromDB();
            }
        }

		if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['findRspnAnimal'])){
            findRspnAnimal();

        } else if (isset($_GET['findGenderForSpc'])){
            findGenderForSpc();

        }
        else if (isset($_GET['countTupleRequest'])) {
            handleGETRequest();
        }
	?>

        
