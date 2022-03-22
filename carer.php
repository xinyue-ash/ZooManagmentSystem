<?php

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

    echo "carer php";

?>