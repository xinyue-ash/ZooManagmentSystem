<?php
include("index.php");
function insertZoneShortage(){
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['insZoneName'],
                ":bind2" => $_POST['insSupplyShortage']
            );

            // https://www.w3schools.com/php/php_arrays_associative.asp

            $alltuples = array (
                $tuple
            );

            $result = executeBoundSQL("insert into Zone_Shorage values (:bind1, :bind2)", $alltuples);
            
            OCICommit($db_conn);
}
function updateVet() {
    global $db_conn;

    $aniaml_ID = $_POST['animalID'];
    $needVet = $_POST['needVet'];

    // you need the wrap the old name and new name values with single quotations
    executePlainSQL("UPDATE demoTable SET needVet='" . $needVet . "' WHERE aniaml_ID='" . $aniaml_ID . "'");
    OCICommit($db_conn);
}

function findRspnAnimal(){
    global $db_conn;

    $carerID = $_GET['carerID'];
    
    // you need the wrap the old name and new name values with single quotations
    $result = executePlainSQL("SELECT animalID FROM Take_Care_Of WHERE carerID='" . $carerID . "'");

    while (($row = oci_fetch_row($result)) != false) {
                echo "<br> The animal that you are taking care of: " . $row[0] . "<br>";
            }
    OCICommit($db_conn);
}

function findGenderForSpc(){
    global $db_conn;

    $species = $_GET['species'];
    
    //need to modify 
    
    $result = executePlainSQL("SELECT animalID FROM Take_Care_Of WHERE carerID='" . $carerID . "'");

    while (($row = oci_fetch_row($result)) != false) {
                echo "<br> The animal that you are taking care of: " . $row[0] . "<br>";
            }
    OCICommit($db_conn);
}
?>
// is delete zoneShortage optional
