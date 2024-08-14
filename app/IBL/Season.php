<?php

namespace App\IBL;

class Season
{
    protected $db;

    public $phase;

    public $beginningYear;
    public $endingYear;

    public $lastSimNumber;
    public $lastSimStartDate;
    public $lastSimEndDate;

    public $allowTrades;
    public $allowWaivers;

    const IBL_PRESEASON_MONTH = 9;
    const IBL_HEAT_MONTH = 10;
    const IBL_REGULAR_SEASON_STARTING_MONTH = 11;
    const IBL_REGULAR_SEASON_ENDING_MONTH = 05;
    const IBL_PLAYOFF_MONTH = 06;
    const JSB_PLAYOFF_MONTH = 22;

    public function __construct($db)
    {
        $this->db = $db;

        $this->phase = $this->getSeasonPhase();

        $this->endingYear = $this->getSeasonEndingYear();
        $this->beginningYear = $this->endingYear - 1;

        $arrayLastSimDates = $this->getLastSimDatesArray();
        $this->lastSimNumber = $arrayLastSimDates["Sim"];
        $this->lastSimStartDate = $arrayLastSimDates["Start Date"];
        $this->lastSimEndDate = $arrayLastSimDates["End Date"];

        $this->allowTrades = $this->getAllowTradesStatus();
        $this->allowWaivers = $this->getAllowWaiversStatus();
    }

    public function getSeasonPhase()
    {
        $querySeasonPhase = $this->db->sql_query("SELECT value
            FROM ibl_settings
            WHERE name = 'Current Season Phase'
            LIMIT 1");

        return $this->db->sql_result($querySeasonPhase, 0);
    }

    public function getSeasonEndingYear()
    {
        $querySeasonEndingYear = $this->db->sql_query("SELECT value
            FROM ibl_settings
            WHERE name = 'Current Season Ending Year'
            LIMIT 1");

        return $this->db->sql_result($querySeasonEndingYear, 0);
    }

    public function getFirstBoxScoreDate()
    {
        $queryFirstBoxScoreDate = $this->db->sql_query("SELECT Date
            FROM ibl_box_scores
            ORDER BY Date ASC
            LIMIT 1");

        return $this->db->sql_result($queryFirstBoxScoreDate, 0);
    }

    public function getLastBoxScoreDate()
    {
        $queryLastBoxScoreDate = $this->db->sql_query("SELECT Date
            FROM ibl_box_scores
            ORDER BY Date DESC
            LIMIT 1");

        return $this->db->sql_result($queryLastBoxScoreDate, 0);
    }

    public function getLastSimDatesArray()
    {
        $queryLastSimDates = $this->db->sql_query("SELECT *
            FROM ibl_sim_dates
            ORDER BY sim DESC
            LIMIT 1");

        return $this->db->sql_fetch_assoc($queryLastSimDates);
    }

    public function setLastSimDatesArray($newSimNumber, $newSimStartDate, $newSimEndDate)
    {
        $querySimDates = $this->db->sql_query("INSERT INTO ibl_sim_dates
                    (`Sim`,
                    `Start Date`,
                    `End Date`)
            VALUES  ('$newSimNumber',
                    '$newSimStartDate',
                    '$newSimEndDate'); ");

        return $querySimDates;
    }

    public function getAllowTradesStatus()
    {
        $queryAllowTradesStatus = $this->db->sql_query("SELECT value
            FROM ibl_settings
            WHERE name = 'Allow Trades'
            LIMIT 1");

        return $this->db->sql_result($queryAllowTradesStatus, 0);
    }

    public function getAllowWaiversStatus()
    {
        $queryAllowWaiversStatus = $this->db->sql_query("SELECT value
            FROM ibl_settings
            WHERE name = 'Allow Waiver Moves'
            LIMIT 1");

        return $this->db->sql_result($queryAllowWaiversStatus, 0);
    }
}