<?php

include "crew_model.php";
$capture_event_id = $_GET['event_id']; //event_id staat in de url (?event_id=)
?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
    <head>
        <!-- De hele website is UTF-8, dat is de standaard -->
        <meta charset="utf-8">
        <!-- Dit bepaald hoe de website schaalt op mobiel -->
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap -->
        <link rel="stylesheet" type="text/css" href="../dependencies/bootstrap-5.2.1/css/bootstrap.min.css">
        <script type="text/javascript" src="../dependencies/bootstrap-5.2.1/js/bootstrap.min.js"></script>

        <!--jQuery-->
        <script src="../dependencies/jquery-3.6.1.min.js"></script>

        <!-- Stylesheet -->
        <link href="../css/admin.css" rel="stylesheet">

        <!--Javascript-->
        <script src="../js/admin.js"></script>

        <title>The capture of <?php echo $capture_event['event_name'] ?></title>
    </head>

    <body>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarColor01">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="glossary.php">Glossary</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Header -->
        <div class="container">
            <div class="row">
                <h1>The Capture of <?php echo $capture_event['event_name'] ?></h1>
            </div>
        </div>

        <div class="container">
            <form method="POST">
                <div class="row">
                    <!-- Kolom van 4 breed -->
                    <div class="col-4">
                        <h2>Captured ship crew</h2>
                        <hr>
                        <div id="crew_members">
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#crew-dialog">
                                +
                            </button>
                            <?php
                            // Haal de crew members op adhv het Capture Event ID
                            $crew_members = get_crew_members($db_connection, $capture_event_id);

                            $crew_member_count = 0;  //count gebruik je alleen voor hr, maar ik zet hem hier vast neer
                            foreach ($crew_members as $crew_member) {
                                $crew_member_count++;

                                // <?=$variabel is hetzelfde als <? echo $variabel
                                ?>
                                <div id="crew_member_<?=$crew_member['id']; ?>" class="crew_member">

                                    <!-- Name texfield -->
                                    <label class="form-label" for="crew_name"><b>Name:</b></label><br/>
                                    <input name="crew_name[<?= $crew_member['id'] ?>]" id="crew_name"
                                           value="<?= $crew_member['crew_name'] ?? "" ?>"
                                           placeholder="Name"/>
                                    <br>
                                    <?php

                                    // Crew function dropdown
                                    echo createDropDown(
                                        "crew_function", // ID van de dropdown (<select>)
                                        "Function: ",  // De tekst boven de dropdown
                                        "crew_function[$crew_member[id]]", // Naam van de dropdown (<select> name), met het ID van de crew member zodat je weet welke crew member het is
                                        get_crew_functions($db_connection),  // Vul de dropdown
                                        "crew_function", // Dit veld willen we laten zien in de dropdown (cleric, cook, captain, etc)
                                        "crew_function", // Dit veld sturen we naar PHP als je 'm aanklikt (kan een ID zijn, maar is nu hetzelfde)
                                        $crew_member['crew_function'] // Als deze er is, selecteer die dan
                                    );

                                    // Religion dropdown
                                    echo createDropDown(
                                        "religion_name", // ID van de dropdown (<select>)
                                        "Religion: ", // De tekst boven de dropdown
                                        "religion_name[$crew_member[id]]", // Naam van de dropdown (<select> name), met het ID van de crew member zodat je weet van wie deze religie is
                                        get_religions($db_connection), // Vul de dropdown
                                        "religion_name", // Dit veld willen we laten zien in de dropdown (katholiek, protestants, etc)
                                        "id", // Dit veld sturen we naar PHP als je 'm aanklikt (ID van de religion)
                                        $crew_member['religion_id'] // Als deze er is, selecteer die dan (dus vergelijken op ID)
                                    );

                                    // Place of birth dropdown
                                    echo createDropDown(
                                        "crew_origin",
                                        "Place of birth: ",
                                        "crew_origin[$crew_member[id]]",
                                        get_places_of_birth($db_connection),
                                        "crew_origin",
                                        "crew_origin",
                                        $crew_member['crew_origin']
                                    );

                                    // Place of residence dropdown
                                    echo createDropDown(
                                        "crew_residence",
                                        "Place of residence: ",
                                        "crew_residence[$crew_member[id]]",
                                        get_crew_residences($db_connection),
                                        "crew_residence",
                                        "crew_residence",
                                        $crew_member['crew_residence']
                                    );
                                    ?>

                                    <!-- Age textfield-->
                                    <label class="form-label" for="crew_age"><b>Age:</b></label><br/>
                                    <input name="crew_age[<?= $crew_member['id'] ?>]" id="crew_age"
                                           value="<?= $crew_member['crew_age'] ?? "" ?>"
                                           placeholder="Age"/>

                                    <?php
                                    // Als we meer dan 1 crew member hebben, EN het niet de laatste crew member is, dan tonen we het streepje
                                    if (count($crew_members) > 1 && $crew_member_count != count($crew_members)) { ?>
                                        <hr style="width:52%; text-align:left; margin-left:0">
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <input type="submit" value="save all changes" class="btn btn-danger">
                </div>
            </form>
        </div>
    </body>
</html>
