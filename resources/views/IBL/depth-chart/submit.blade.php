<?php

$season = new App\IBL\Season($db);

$Set_Name = $_POST['Set_Name'];
$Team_Name = $_POST['Team_Name'];
$html = "";
$filetext = "Name,PG,SG,SF,PF,C,ACTIVE,MIN,OF,DF,OI,DI
";

$activePlayers = 0;
$pos_1 = 0;
$pos_2 = 0;
$pos_3 = 0;
$pos_4 = 0;
$pos_5 = 0;
$hasStarterAtMultiplePositions = false;

$i = 1;
while ($i <= 15) {
    $startingPositionCount = 0;

    $a = "<tr><td>" . $_POST['Name' . $i] . "</td>";
    $b = "<td>" . $_POST['pg' . $i] . "</td>";
    $c = "<td>" . $_POST['sg' . $i] . "</td>";
    $d = "<td>" . $_POST['sf' . $i] . "</td>";
    $e = "<td>" . $_POST['pf' . $i] . "</td>";
    $f = "<td>" . $_POST['c' . $i] . "</td>";
    $g = "<td>" . $_POST['active' . $i] . "</td>";
    $h = "<td>" . $_POST['min' . $i] . "</td>";
    $z = "<td>" . $_POST['OF' . $i] . "</td>";
    $j = "<td>" . $_POST['DF' . $i] . "</td>";
    $k = "<td>" . $_POST['OI' . $i] . "</td>";
    $l = "<td>" . $_POST['DI' . $i] . "</td>";
    $m = "<td>" . $_POST['BH' . $i] . "</td></tr>
";

    $html .= $a . $b . $c . $d . $e . $f . $g . $h . $z . $j . $k . $l . $m;

    $injury = $_POST['Injury' . $i];
    $aa = $_POST['Name' . $i] . ",";
    $bb = $_POST['pg' . $i] . ",";
    $cc = $_POST['sg' . $i] . ",";
    $dd = $_POST['sf' . $i] . ",";
    $ee = $_POST['pf' . $i] . ",";
    $ff = $_POST['c' . $i] . ",";
    $gg = $_POST['active' . $i] . ",";
    $hh = $_POST['min' . $i] . ",";
    $zz = $_POST['OF' . $i] . ",";
    $jj = $_POST['DF' . $i] . ",";
    $kk = $_POST['OI' . $i] . ",";
    $ll = $_POST['DI' . $i] . ",";
    $mm = $_POST['BH' . $i] . "
";

    $filetext .= $aa . $bb . $cc . $dd . $ee . $ff . $gg . $hh . $zz . $jj . $kk . $ll . $mm;

    $dc_insert1 = $_POST['pg' . $i];
    $dc_insert2 = $_POST['sg' . $i];
    $dc_insert3 = $_POST['sf' . $i];
    $dc_insert4 = $_POST['pf' . $i];
    $dc_insert5 = $_POST['c' . $i];
    $dc_insert6 = $_POST['active' . $i];
    $dc_insert7 = $_POST['min' . $i];
    $dc_insert8 = $_POST['OF' . $i];
    $dc_insert9 = $_POST['DF' . $i];
    $dc_insertA = $_POST['OI' . $i];
    $dc_insertB = $_POST['DI' . $i];
    $dc_insertC = $_POST['BH' . $i];
    $dc_insertkey = addslashes($_POST['Name' . $i]);

    $updatequery1 = "UPDATE ibl_plr SET dc_PGDepth = '$dc_insert1' WHERE name = '$dc_insertkey'";
    $updatequery2 = "UPDATE ibl_plr SET dc_SGDepth = '$dc_insert2' WHERE name = '$dc_insertkey'";
    $updatequery3 = "UPDATE ibl_plr SET dc_SFDepth = '$dc_insert3' WHERE name = '$dc_insertkey'";
    $updatequery4 = "UPDATE ibl_plr SET dc_PFDepth = '$dc_insert4' WHERE name = '$dc_insertkey'";
    $updatequery5 = "UPDATE ibl_plr SET dc_CDepth = '$dc_insert5' WHERE name = '$dc_insertkey'";
    $updatequery6 = "UPDATE ibl_plr SET dc_active = '$dc_insert6' WHERE name = '$dc_insertkey'";
    $updatequery7 = "UPDATE ibl_plr SET dc_minutes = '$dc_insert7' WHERE name = '$dc_insertkey'";
    $updatequery8 = "UPDATE ibl_plr SET dc_of = '$dc_insert8' WHERE name = '$dc_insertkey'";
    $updatequery9 = "UPDATE ibl_plr SET dc_df = '$dc_insert9' WHERE name = '$dc_insertkey'";
    $updatequeryA = "UPDATE ibl_plr SET dc_oi = '$dc_insertA' WHERE name = '$dc_insertkey'";
    $updatequeryB = "UPDATE ibl_plr SET dc_di = '$dc_insertB' WHERE name = '$dc_insertkey'";
    $updatequeryC = "UPDATE ibl_plr SET dc_bh = '$dc_insertC' WHERE name = '$dc_insertkey'";
    $updatequeryD = "UPDATE ibl_team_history SET depth = NOW() WHERE team_name = '$Team_Name'";
    $updatequeryF = "UPDATE ibl_team_history SET sim_depth = NOW() WHERE team_name = '$Team_Name'";
    $executeupdate1 = $db->sql_query($updatequery1);
    $executeupdate2 = $db->sql_query($updatequery2);
    $executeupdate3 = $db->sql_query($updatequery3);
    $executeupdate4 = $db->sql_query($updatequery4);
    $executeupdate5 = $db->sql_query($updatequery5);
    $executeupdate6 = $db->sql_query($updatequery6);
    $executeupdate7 = $db->sql_query($updatequery7);
    $executeupdate8 = $db->sql_query($updatequery8);
    $executeupdate9 = $db->sql_query($updatequery9);
    $executeupdateA = $db->sql_query($updatequeryA);
    $executeupdateB = $db->sql_query($updatequeryB);
    $executeupdateC = $db->sql_query($updatequeryC);

    if ($dc_insert6 == 1) {
        $activePlayers++;
    }

    if ($dc_insert1 > 0 && $injury == 0) {
        $pos_1++;
    }
    if ($dc_insert2 > 0 && $injury == 0) {
        $pos_2++;
    }
    if ($dc_insert3 > 0 && $injury == 0) {
        $pos_3++;
    }
    if ($dc_insert4 > 0 && $injury == 0) {
        $pos_4++;
    }
    if ($dc_insert5 > 0 && $injury == 0) {
        $pos_5++;
    }

    // Check whether a player is listed at multiple starting positions
    if ($_POST['pg' . $i] == 1) {
        $startingPositionCount++;
    }
    if ($_POST['sg' . $i] == 1) {
        $startingPositionCount++;
    }
    if ($_POST['sf' . $i] == 1) {
        $startingPositionCount++;
    }
    if ($_POST['pf' . $i] == 1) {
        $startingPositionCount++;
    }
    if ($_POST['c' . $i] == 1) {
        $startingPositionCount++;
    }
    if ($startingPositionCount > 1) {
        $hasStarterAtMultiplePositions = true;
        $nameOfProblemStarter = $_POST['Name' . $i];
    }

    $i++;
}

$html .= "</table>";

if ($season->phase != 'Playoffs') {
    $minActivePlayers = 12;
    $maxActivePlayers = 12;
    $minPositionDepth = 3;
} else {
    $minActivePlayers = 10;
    $maxActivePlayers = 12;
    $minPositionDepth = 2;
}

$error = false;
$errorText = "";
if ($activePlayers < $minActivePlayers) {
    $errorText .= "<font color=red><b>You must have at least $minActivePlayers active players in your lineup; you have $activePlayers.</b></font><p>
        Please press the \"Back\" button on your browser and activate " . ($minActivePlayers - $activePlayers) . " player(s).</center><p>";
    $error = true;
}
if ($activePlayers > $maxActivePlayers) {
    $errorText .= "<font color=red><b>You can't have more than $maxActivePlayers active players in your lineup; you have $activePlayers.</b></font><p>
        Please press the \"Back\" button on your browser and deactivate " . ($activePlayers - $maxActivePlayers) . " player(s).</center><p>";
    $error = true;
}
if ($pos_1 < $minPositionDepth) {
    $errorText .= "<font color=red><b>You must have at least $minPositionDepth players entered in PG slot &mdash; you have $pos_1.</b></font><p>
        Please click the \"Back\" button on your browser and activate " . ($minPositionDepth - $pos_1) . " player(s).</center><p>";
    $error = true;
}
if ($pos_2 < $minPositionDepth) {
    $errorText .= "<font color=red><b>You must have at least $minPositionDepth players entered in SG slot &mdash; you have $pos_2.</b></font><p>
        Please click the \"Back\" button on your browser and activate " . ($minPositionDepth - $pos_2) . " player(s).</center><p>";
    $error = true;
}
if ($pos_3 < $minPositionDepth) {
    $errorText .= "<font color=red><b>You must have at least $minPositionDepth players entered in SF slot &mdash; you have $pos_3.</b></font><p>
        Please click the \"Back\" button on your browser and activate " . ($minPositionDepth - $pos_3) . " player(s).</center><p>";
    $error = true;
}
if ($pos_4 < $minPositionDepth) {
    $errorText .= "<font color=red><b>You must have at least $minPositionDepth players entered in PF slot &mdash; you have $pos_4.</b></font><p>
        Please click the \"Back\" button on your browser and activate " . ($minPositionDepth - $pos_4) . " player(s).</center><p>";
    $error = true;
}
if ($pos_5 < $minPositionDepth) {
    $errorText .= "<font color=red><b>You must have at least $minPositionDepth players entered in C slot &mdash; you have $pos_5.</b></font><p>
        Please click the \"Back\" button on your browser and activate " . ($minPositionDepth - $pos_5) . " player(s).</center><p>";
    $error = true;
}
if ($hasStarterAtMultiplePositions) {
    $errorText .= "<font color=red><b>$nameOfProblemStarter is listed at more than one position in the starting lineup.</b></font><p>
        Please click the \"Back\" button on your browser and ensure they are only starting at ONE position.</center><p>";
    $error = true;
}

if ($error == true) {
    $html .= "<center><u>Your lineup has <b>not</b> been submitted:</u></center><br>";
    $html .= $errorText;
} else {
    $emailsubject = $Team_Name . " Depth Chart - $Set_Name Offensive Set";
    $recipient = 'ibldepthcharts@gmail.com';
    $filename = '../depthcharts/' . $Team_Name . '.txt';

    if (file_put_contents($filename, $filetext)) {
        $executeupdateD = $db->sql_query($updatequeryD);
        $executeupdateF = $db->sql_query($updatequeryF);

        if ($_SERVER['SERVER_NAME'] != "localhost") {
            mail($recipient, $emailsubject, $filetext, "From: ibldepthcharts@gmail.com");
        }

        $html .= "<br><center><u>Your depth chart has been submitted and e-mailed successfully. Thank you.</u></center>";
    } else {
        $html .= "<br><font color=red>Depth chart failed to save properly; please contact the commissioner.</font></center>";
    }
};

?>

<x-app-layout>
    <x-slot:header>
        Depth Chart Submission
    </x-slot:header>
    <center>
    {{ $Team_Name }} Depth Chart Submission
    <br><br>
    <table>
        <tr>
            <td><b>Name</td>
            <td><b>PG</td>
            <td><b>SG</td>
            <td><b>SF</td>
            <td><b>PF</td>
            <td><b>C</td>
            <td><b>Active</td>
            <td><b>Min</td>
            <td><b>OF</td>
            <td><b>DF</td>
            <td><b>OI</td>
            <td><b>DI</td>
            <td><b>BH</td>
        </tr>
        {!! $html !!}
    </center>
</x-app-layout>