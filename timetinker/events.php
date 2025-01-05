<?php 
    require_once "../classes/EventList.php";

    if (!isset($_SESSION)) {
        session_start();
    }

    if (!isset($_SESSION["user"])) {
        header("location: ../login/login.php?messaggio=Devi effettuare il login per accedere a questa pagina");
        exit;
    }

    if(!isset($_GET["year"]) || empty($_GET["year"])) {
        header("location: timeline.php");
        exit;
    }
    if(!is_numeric($_GET["year"]) || $_GET["year"] > date("Y") || $_GET["year"] < -1000) {
        if($_GET["year"] != 0) {
            header("location: timeline.php");
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
</head>
<body>
    <h1>Events</h1>
    <h2>Year: <?php echo $_GET["year"]; ?></h2>
    <table>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Date</th>
        </tr>
        <?php
            $events = EventList::GetEventsByYear($_GET["year"]);
            foreach ($events as $e) {
                echo "<tr>";
                echo "<td>" . $e->getName() . "</td>";
                echo "<td>" . $e->getDescription() . "</td>";
                echo "<td>" . $e->getDate() . "</td>";
                echo "</tr>";
            }

            //EventList::getHistoricalEventFromAPI($_GET["year"]);
        ?>
    </table>
</body>
</html>