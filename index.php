<!-- This file is modified from file by Group 24 from Milestone3 2022 Spring:
https://github.students.cs.ubc.ca/CPSC304/CPSC304_PHP_Project/blob/master/init.php -->
<?php

//this tells the system that it's no longer just parsing html; it's now parsing PHP

$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = NULL; // edit the login credentials in connectToDB()
$show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

function debugAlertMessage($message)
{
    global $show_debug_alert_messages;

    if ($show_debug_alert_messages) {
        echo "<script type='text/javascript'>alert('" . $message . "');</script>";
    }
}

function executePlainSQL($cmdstr)
{ //takes a plain (no bound variables) SQL command and executes it
    echo "<br>running " . $cmdstr . "<br>";
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

function executeBoundSQL($cmdstr, $list)
{
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
            echo "<br>" . $bind . "<br>";
            OCIBindByName($statement, $bind, $val);
            unset($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
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

function connectToDB()
{
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

function disconnectFromDB()
{
    global $db_conn;

    debugAlertMessage("Disconnect from Database");
    OCILogoff($db_conn);
}


// Insertion
function insertZoneShortage()
{
    global $db_conn;

    //Getting the values from user and insert data into the table
    $tuple = array(
        ":bind1" => $_POST['insZoneName'],
        ":bind2" => $_POST['insSupplyShortage']
    );


    $alltuples = array(
        $tuple
    );

    $result = executeBoundSQL("insert into Zone_Shortage values(:bind1, :bind2)", $alltuples);
    //printResult($result);

    OCICommit($db_conn);
}

// Update
function updateVet()
{
    global $db_conn;

    $aniaml_ID = (int)$_POST['animalID'];

    // you need the wrap the old name and new name values with single quotations
    executePlainSQL("UPDATE Animal_BasicInfo SET needVet=1 WHERE animalID='" . $aniaml_ID . "'");
    OCICommit($db_conn);
}

// Selection
function findRspnAnimal()
{
    global $db_conn;

    $carerID = (int)$_GET['carerID'];

    $result = executePlainSQL("SELECT animalID FROM Take_Care_Of  WHERE carerID='" . $carerID . "'");

    echo "<br>The animal that you are taking care of:<br>";
    echo "<table>";
    echo "<tr><th>ANIMALID</th></tr>";
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row["ANIMALID"] . "</td><td>";
    }
    echo "</table>";


    OCICommit($db_conn);
}

// Aggrt
function countNumSpc()
{
    global $db_conn;

    $spc = $_GET['species'];

    $result = executePlainSQL("SELECT Count(*) FROM Animal_BasicInfo WHERE species='" . $spc . "'");

    if (($row = oci_fetch_row($result)) != false) {
        echo "<br> The number of " . $spc . " in Animal_BasicInfo: " . $row[0] . "<br>";
    }
    OCICommit($db_conn);
}


//Projection
function prjctAnimal()
{
    global $db_conn;
    $result = executePlainSQL("select animalID, species, gender from Animal_BasicInfo");

    echo "<br>Animal Info:<br>";
    echo "<table>";
    echo "<tr><th>ANIMALID</th><th>SPECIES</th><th>GENDER</th></tr>";
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row["ANIMALID"] . "</td><td>" . $row["SPECIES"] . "</td><td>" . $row["GENDER"] . "</td></tr>";
    }
    echo "</table>";

    OCICommit($db_conn);
}

function updateVetFromVet()
{
    global $db_conn;

    $aniaml_ID = (int)$_POST['animalIDFromVet'];

    executePlainSQL("UPDATE Animal_BasicInfo SET needVet=0 WHERE animalID='" . $aniaml_ID . "'");

    OCICommit($db_conn);
}

// join
function findANeedVet()
{
    global $db_conn;

    $vet_ID = (int)$_GET['vetID'];
    //select animalID from Animal_BasicInfo A, Vets_Occupation V where A.zoneName = V.zoneName and needVet = 1 and vetID = vid(int);

    $result = executePlainSQL("SELECT animalID FROM Animal_BasicInfo A, Vets_Occupation V  WHERE A.zoneName = V.zoneName and needVet = 1 and vetID='" . $vet_ID . "'");

    echo "<br>The animal that you are taking care of:<br>";
    echo "<table>";
    echo "<tr><th>ANIMALID</th></tr>";
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row["ANIMALID"] . "</td><td>";
    }
    echo "</table>";

    OCICommit($db_conn);
}


// HANDLE ALL POST ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handlePOSTRequest()
{
    if (connectToDB()) {
        if (array_key_exists('insertZoneShortage', $_POST)) {
            insertZoneShortage();
        } else if (array_key_exists('updateVet', $_POST)) {
            updateVet();
        } else if (array_key_exists('updateVetFromVet', $_POST)) {
            updateVetFromVet();
        }

        disconnectFromDB();
    }
}

// HANDLE ALL GET ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handleGETRequest()
{
    if (connectToDB()) {
        if (array_key_exists('countNumSpc', $_GET)) {
            countNumSpc();
        } else if (array_key_exists('findRspnAnimal', $_GET)) {
            findRspnAnimal();
        } else if (array_key_exists('prjctAnimal', $_GET)) {
            prjctAnimal();
        } else if (array_key_exists('findANeedVet', $_GET)) {
            findANeedVet();
        }

        disconnectFromDB();
    }
}

if (isset($_POST['updateSubmit']) || isset($_POST['insertSubmit'])) {
    handlePOSTRequest();
} else if (isset($_GET['countNumSpc']) || isset($_GET['selectSubmit']) || isset($_GET['prjctSubmit'])) {
    handleGETRequest();
}
