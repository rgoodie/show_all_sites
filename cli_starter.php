<?php
/**
 * This file is used in the initial creation of the plugin. I begin on the command line
 * using drush scr cli_starter.php
 */



// include_once("MultiSites.class.php");


// $sites = new MultiSites();
// print_r($sites);


// include_once("ExamineSubmissions.class.php");



// select u.realname, ws.uid, count(ws.sid)
// FROM webform_submissions AS ws
// INNER JOIN realname AS u
// ON u.uid=ws.uid
// GROUP BY ws.uid
// ORDER BY count(ws.sid) desc;



 $query_string = "select u.realname, ws.uid, count(ws.sid)
        FROM {webform_submissions} AS ws
        INNER JOIN {realname} AS u
        ON u.uid=ws.uid
        GROUP BY ws.uid
        ORDER BY count(ws.sid) desc;";

$query = db_query($query_string);

while($row =  $query->fetchAssoc()) {
    $results[] =$row;
}

print_r($results);