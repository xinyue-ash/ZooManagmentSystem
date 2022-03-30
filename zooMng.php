<!-- The html file of Zoon Managment System UI -->
<!-- This file is modified from file by Group 24 from Milestone3 2022 Spring:
https://github.students.cs.ubc.ca/CPSC304/CPSC304_PHP_Project/blob/master/init.php -->
<!--Test Oracle file for UBC CPSC304 2018 Winter Term 1
  Created by Jiemin Zhang
  Modified by Simona Radu
  Modified by Jessica Wong (2018-06-22)
  This file shows the very basics of how to execute PHP commands
  on Oracle.  
  Specifically, it will drop a table, create a table, insert values
  update values, and then query for values
 
  IF YOU HAVE A TABLE CALLED "demoTable" IT WILL BE DESTROYED
  The script assumes you already have a server set up
  All OCI commands are commands to the Oracle libraries
  To get the file to work, you must place it somewhere where your
  Apache server can run it, and you must rename it to have a ".php"
  extension.  You must also change the username and password on the 
  OCILogon below to be your ORACLE username and password -->

  <html>

<head>
    <title>Zoo Management</title>
</head>

<body>
    <!-- animal carers -->
    <h1>Animal Carers</h1>


    <h3>Insert Zone Shortage</h3>
    <i>
        <h4>Enter the name of the zone and type of supply to edit the status of zone shortage below</h4>
    </i>
    <form method="POST" action="zooMng.php">
        <!--refresh page when submitted-->
        <input type="hidden" id="insertZoneShortage" name="insertZoneShortage">
        zoneName: <input type="text" name="insZoneName"> <br /><br />
        supplyShotage: <input type="text" name="insSupplyShortage"> <br /><br />

        <input type="submit" value="Insert" name="insertSubmit">
        <!-- <input type="submit" value="Delete" name=""> -->
    </form>

    <h3>Update needVet</h3>
    <i>
        <h4>Enter the ID of the animal that needs medical care from vets below</h4>
    </i>
    <form method="POST" action="zooMng.php">
        <input type="hidden" id="updateVet" name="updateVet">
        animalD: <input type="text" name="animalID"> <br /><br />
        <input type="submit" value="Update needVet" name="updateSubmit"></p>
    </form>

    <h3>Responsible Animal</h3>
    <i>
        <h4>Enter your worker ID below to get a list of animals you are responsible of</h4>
    </i>
    <!-- (SELECT animal that i am responsible for) -->
    <form method="GET" action="zooMng.php">
        <input type="hidden" id="findRspnAnimal" name="findRspnAnimal">
        carerID: <input type="text" name="carerID"> <br /><br />

        <input type="submit" value="Select" name="selectSubmit"></p>
    </form>


    <h3>Count number of a input species</h3>
    <i>
        <h4>Enter the name of the specie below to get the total count of that specie</h4>
    </i>
    <form method="GET" action="zooMng.php">
        <input type="hidden" id="countNumSpc" name="countNumSpc">
        species: <input type="text" name="species"> <br /><br />

        <input type="submit" value="Count" name="countSpcSubmit"></p>
    </form>


    <h3>Project all animal with animalID, species, gender </h3>
    <form method="GET" action="zooMng.php">
        <input type="hidden" id="prjctAnimal" name="prjctAnimal">
        <input type="submit" value="Project Info" name="prjctSubmit"></p>
    </form>

    <hr />


    <!-- Vet -->
    <h1>Medical Care</h1>

    <h3>Update needVet</h3>
    <i>
        <h4>Enter the ID of the animal that no longer needs medical care from vets below</h4>
    </i>
    <form method="POST" action="zooMng.php">
        <input type="hidden" id="updateVetFromVet" name="updateVetFromVet">
        animalD: <input type="text" name="animalIDFromVet"> <br /><br />
        <input type="submit" value="Update needVet" name="updateSubmit"></p>
    </form>

    <h3>Find animals who need to see vet</h3>
    <i>
        <h4>Enter your Vet ID below and get a list of animals that need medical care under you responsibility</h4>
    </i>
    <form method="GET" action="zooMng.php">
        <!--refresh page when submitted-->
        <input type="hidden" id="findANeedVet" name="findANeedVet">
        vetID: <input type="text" name="vetID"> <br /><br />

        <input type="submit" value="See which cutie need to see me" name="selectSubmit"></p>
    </form>

    <hr />
    <!-- manager -->
    <h1>Supply Managment</h1>

    <h3>Delete a company</h3>
    <i>
        <h4>Enter the name of the company below to delete the company from the database</h4>
    </i>
    <form method="POST" action="zooMng.php">
        <!--refresh page when submitted-->
        <input type="hidden" id="deletco" name="deletco">

        companyName: <input type="text" name="coname"> <br /><br />

        <input type="submit" value="Delete Company" name="deleteSubmit">
        <!-- DELETION (on CASCADE) : delete a company, then the information in Provide_Supplies also deleted  -->

    </form>

    <h3>Search Highest Storage</h3>
    <i>
        <h4>Find Company with Highest Storage under Each Supply Category</h4>
    </i>
    <form method="GET" action="zooMng.php">
        <input type="hidden" id="searchCo" name="searchCo">
        <input type="submit" value="Search Companies" name="selectSubmit"></p>
    </form>


    <h3>Find Company</h3>
    <i>
        <h4>With existing zone shortages, find a list of company that can provide all the needed supplies</h4>
    </i>
    <form method="GET" action="zooMng.php">
        <!--refresh page when submitted-->
        <input type="hidden" id="findCo" name="findCo">
        <input type="submit" value="Find Company" name="selectSubmit"></p>

    </form>
    

    <hr />
    <h1>Results</h1>

    <!-- start of php functions -->
    <?php
    include("index.php");
    ?>

</body>

</html>
