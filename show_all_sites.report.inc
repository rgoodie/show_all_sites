<?php

function show_all_sites_subsites_report_content()
{

    $sites = new MultiSites();

    $out = theme_item_list(array(
        'items' => $sites->getLinks(),
        'title' => t('Direct URLs'),
        'type' => 'ul',
        'attributes' => array()
    ));

    $out .= theme_item_list(array(
        'title' => t('All folders'),
        'items' => $sites->getRaw(),
        'type' => 'ul',
        'attributes' => array(),
    ));


    return $out;
}


function show_all_sites_webforms_at_a_glance()
{
    // get list of uids of webforms
    $webform_uids = array_keys(node_load_multiple(array(), array('type' => 'webform')));

    $submissions = array();
    foreach ($webform_uids as $webform_uid) {
        $submissions[] = new ExamineSubmissions($webform_uid);
    }
    $reports_table = array();
    foreach ($submissions as $submission) {
        $reports_table[] = $submission->toReport();
    }

    $out = theme_table(array(
        'header' => array_keys($reports_table[0]),
        'rows' => $reports_table,
        'attributes' => array('class' => array(
            'table',
            'table-striped'
        )),
        'caption' => t('The oldest and newest entries in each webform submission.'),
        'colgroups' => array(),
        'sticky' => '',
        'empty' => ''
    ));

    $primary_contacts = ExamineSubmissions::getPrinipcleContacts();

    $out .= theme_table(array(
        'header' => array_keys($primary_contacts[0]),
        'rows' => $primary_contacts,
        'attributes' => array('class' => array(
            'table',
            'table-striped'
        )),
        'caption' => t('Individuals who are in groups other than admin or authenticated who may have ownership of this data.'),
        'colgroups' => array(),
        'sticky' => '',
        'empty' => ''
    ));


    // number of submissions per user
    $num_sub_per_user = ExamineSubmissions::getSubmissionsPerUser();
    $out .= theme_table(array(
        'header' => array_keys($num_sub_per_user[0]),
        'rows' => $num_sub_per_user,
        'attributes' => array('class' =>
            'table',
            'table-striped'
        ),
        'caption' => t('Number of submissions per individual'),
        'colgroups' => array(),
        'empty' => '',
        'sticky' => ''
    ));


    return $out;


}