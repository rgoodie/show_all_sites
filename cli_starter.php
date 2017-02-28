<?php
/**
 * This file is used in the initial creation of the plugin. I begin on the command line
 * using drush scr cli_starter.php
 */



// include_once("MultiSites.class.php");


// $sites = new MultiSites();
// print_r($sites);


// include_once("ExamineSubmissions.class.php");
$submissions = new ExamineSubmissions(4);
print_r($submissions);


