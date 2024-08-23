<?php

$username = \Auth::user()->name ?? "";
$sharedFunctions = new App\IBL\Shared($db);
$season = new App\IBL\Season($db);

$sql2 = "SELECT * FROM nuke_users WHERE username='$username'";
$result2 = $db->sql_query($sql2);
$userinfo = $db->sql_fetchrow($result2);

$teamlogo = $userinfo['user_ibl_team'];
$tid = $sharedFunctions->getTidFromTeamname($teamlogo);
$team = App\IBL\Team::withTeamID($db, $tid);

$displaytopmenu = App\IBL\UI::displaytopmenu($db, $tid);

// === CODE TO INSERT IBL DEPTH CHART ===

function posHandler($positionVar)
{
    return '<option value="0"' . ($positionVar == 0 ? " SELECTED" : "") . '>No</option>
        <option value="1"' . ($positionVar == 1 ? " SELECTED" : "") . '>1st</option>
        <option value="2"' . ($positionVar == 2 ? " SELECTED" : "") . '>2nd</option>
        <option value="3"' . ($positionVar == 3 ? " SELECTED" : "") . '>3rd</option>
        <option value="4"' . ($positionVar == 4 ? " SELECTED" : "") . '>4th</option>
        <option value="5"' . ($positionVar == 5 ? " SELECTED" : "") . '>ok</option>';
}

function offdefHandler($focusVar)
{
    return '<option value="0"' . ($focusVar == 0 ? " SELECTED" : "") . '>Auto</option>
        <option value="1"' . ($focusVar == 1 ? " SELECTED" : "") . '>Outside</option>
        <option value="2"' . ($focusVar == 2 ? " SELECTED" : "") . '>Drive</option>
        <option value="3"' . ($focusVar == 3 ? " SELECTED" : "") . '>Post</option>';
}

function oidibhHandler($settingVar)
{
    return '<option value="2"' . ($settingVar == 2 ? " SELECTED" : "") . '>2</option>
        <option value="1"' . ($settingVar == 1 ? " SELECTED" : "") . '>1</option>
        <option value="0"' . ($settingVar == 0 ? " SELECTED" : "") . '>-</option>
        <option value="-1"' . ($settingVar == -1 ? " SELECTED" : "") . '>-1</option>
        <option value="-2"' . ($settingVar == -2 ? " SELECTED" : "") . '>-2</option>';
}

$sql7 = "SELECT * FROM ibl_offense_sets WHERE TeamName = '$teamlogo' ORDER BY SetNumber ASC";
$result7 = $db->sql_query($sql7);

$queryPlayersOnTeam = "SELECT * FROM ibl_plr WHERE teamname = '$teamlogo' AND tid = $tid AND retired = '0' AND ordinal <= 960 ORDER BY ordinal ASC"; // 960 is the cut-off ordinal for players on waivers
$playersOnTeam = $db->sql_query($queryPlayersOnTeam);

$offense_name = "Positionless";
$Slot1 = "PG";
$Slot2 = "SG";
$Slot3 = "SF";
$Slot4 = "PF";
$Slot5 = "C";

$table_ratings = App\IBL\UI::ratings($db, $playersOnTeam, $team, "", $season);

$depthcount = 1;

$output = "";
mysqli_data_seek($playersOnTeam, 0);
while ($player = $db->sql_fetchrow($playersOnTeam)) {
    $player_pid = $player['pid'];
    $player_pos = $player['pos'];
    $player_name = $player['name'];

    $player_PG = $player['dc_PGDepth'];
    $player_SG = $player['dc_SGDepth'];
    $player_SF = $player['dc_SFDepth'];
    $player_PF = $player['dc_PFDepth'];
    $player_C = $player['dc_CDepth'];
    $player_active = $player['dc_active'];
    $player_min = $player['dc_minutes'];
    $player_of = $player['dc_of'];
    $player_df = $player['dc_df'];
    $player_oi = $player['dc_oi'];
    $player_di = $player['dc_di'];
    $player_bh = $player['dc_bh'];
    $player_inj = $player['injured'];

    $output .= " <tr>
        <td>$player_pos</td>
        <td nowrap>
            <input type=\"hidden\" name=\"Injury$depthcount\" value=\"$player_inj\">
            <input type=\"hidden\" name=\"Name$depthcount\" value=\"$player_name\">
            <a href=\"./modules.php?name=Player&pa=showpage&pid=$player_pid\">$player_name</a>
        </td>";

    if ($player_inj < 15) {
        $output .= "<td class='dark: text-gray-900'><select name=\"pg$depthcount\">";
        $output .= posHandler($player_PG);
        $output .= "</select></td>";
        $output .= "<td class='dark: text-gray-900'><select name=\"sg$depthcount\">";
        $output .= posHandler($player_SG);
        $output .= "</select></td>";
        $output .= "<td class='dark: text-gray-900'><select name=\"sf$depthcount\">";
        $output .= posHandler($player_SF);
        $output .= "</select></td>";
        $output .= "<td class='dark: text-gray-900'><select name=\"pf$depthcount\">";
        $output .= posHandler($player_PF);
        $output .= "</select></td>";
        $output .= "<td class='dark: text-gray-900'><select name=\"c$depthcount\">";
        $output .= posHandler($player_C);
        $output .= "</select></td>";
    } else {
        $output .= "<td class='dark: text-gray-900><input type=\"hidden\" name=\"pg$depthcount\" value=\"0\">no</td>
            <td class='dark: text-gray-900><input type=\"hidden\" name=\"sg$depthcount\" value=\"0\">no</td>
            <td class='dark: text-gray-900><input type=\"hidden\" name=\"sf$depthcount\" value=\"0\">no</td>
            <td class='dark: text-gray-900><input type=\"hidden\" name=\"pf$depthcount\" value=\"0\">no</td>
            <td class='dark: text-gray-900><input type=\"hidden\" name=\"c$depthcount\" value=\"0\">no</td>";
    }

    $output .= "<td class='dark: text-gray-900'><select name=\"active$depthcount\">";
    if ($player_active == 1) {
        $output .= "<option value=\"1\" SELECTED>Yes</option><option value=\"0\">No</option>";
    } else {
        $output .= "<option value=\"1\">Yes</option><option value=\"0\" SELECTED>No</option>";
    }
    $output .= "</select></td>";

    $output .= "<td class='dark: text-gray-900'><select name=\"min$depthcount\">";
    $output .= "<option value=\"0\"" . ($player_min == 0 ? " SELECTED" : "") . ">Auto</option>";
    $i = 1;
    while ($i <= 40) { // 40 is the maxiumum amount of minutes a player can be set to play.
        $output .= "<option value=\"" . $i . "\"" . ($player_min == $i ? " SELECTED" : "") . ">" . $i . "</option>";
        $i++;
    }

    $output .= "</select></td><td class='dark: text-gray-900'><select name=\"OF$depthcount\">";
    $output .= offdefHandler($player_of);
    $output .= "</select></td><td class='dark: text-gray-900'><select name=\"DF$depthcount\">";
    $output .= offdefHandler($player_df);

    $output .= "</select></td><td class='dark: text-gray-900'><select name=\"OI$depthcount\">";
    $output .= oidibhHandler($player_oi);
    $output .= "</select></td><td class='dark: text-gray-900'><select name=\"DI$depthcount\">";
    $output .= oidibhHandler($player_di);
    $output .= "</select></td><td class='dark: text-gray-900'><select name=\"BH$depthcount\">";
    $output .= oidibhHandler($player_bh);

    $output .= "</select></td></tr>";
    $depthcount++;
}

?>

<x-app-layout>
    <x-slot:header>
        Depth Chart
    </x-slot:header>
    <center class="">
        <form name="Depth_Chart" method="POST" action="/depth-chart/submit">
            @csrf
            <input type="hidden" name="Team_Name" value="{{ $teamlogo }}">
            <input type="hidden" name="Set_Name" value="{{ $offense_name }}">
            <img src="images/logo/{{ $tid }}.jpg"><br>
            <div class="overflow-x-scroll">
                {!! $table_ratings !!}
            </div>
            <p>
            <div class="overflow-x-scroll">
                <table>
                    <tr>
                        <th colspan=14>Offensive Set: {{ $offense_name }}</th>
                    </tr>
                    <tr>
                        <th>Pos</th>
                        <th>Player</th>
                        <th>{{ $Slot1 }}</th>
                        <th>{{ $Slot2 }}</th>
                        <th>{{ $Slot3 }}</th>
                        <th>{{ $Slot4 }}</th>
                        <th>{{ $Slot5 }}</th>
                        <th>active</th>
                        <th>min</th>
                        <th>OF</th>
                        <th>DF</th>
                        <th>OI</th>
                        <th>DI</th>
                        <th>BH</th>
                    </tr>
                    {!! $output !!}
                    <tr>
                        <th colspan=14>
                            <input type="submit" value="Submit">
                        </th>
                    </tr>
                </table>
            </div>
        </form>
    </center>
</x-app-layout>