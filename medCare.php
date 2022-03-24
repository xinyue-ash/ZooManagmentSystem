<?php

    include("index.php");

    function needVet() {
        global $db_conn;
    
        $aniaml_ID = $_POST['animalID'];
        $need_vet = false;
    
        // you need the wrap the old name and new name values with single quotations
        executePlainSQL("UPDATE demoTable SET needVet='" . $need_vet . "' WHERE aniaml_ID='" . $aniaml_ID . "'");
        OCICommit($db_conn);
    }

    function enterAnimalID() {
        
    }

?>