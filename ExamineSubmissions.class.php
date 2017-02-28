<?php

/**
 * Created by PhpStorm.
 * User: carbon
 * Date: 2/27/17
 * Time: 12:56 PM
 */
class ExamineSubmissions
{

    private $nid;
    private $roles;
    private $oldest_date = array();
    private $newest_date = array();
    private $principle_contacts = array();
    private $now;
    private $title;
    public $report =array();

    public function __construct($nid = 0)
    {
        if (!is_numeric($nid)) {
            drupal_set_message(t("The node id is not numeric."), 'error');
            return FALSE;
        }

        if ($nid < 1) {
            drupal_set_message(t("The node id must be greater than 0"), 'error');
            return FALSE;
        }

        // record nid
        $this->nid = $nid;


        $this->oldest_date = $this->getOldestSubmission();
        $this->newest_date = $this->getNewestSubmission();
        $this->principle_contacts = $this->getPrinipcleContacts();
        $this->title = node_page_title(node_load($nid));

        $this->now = time();

        return $this;
    }

    public function getOldestSubmission()
    {

        $query = db_select('webform_submissions', 'wfs')
            ->fields('wfs', array('sid', 'submitted'))
            ->condition('nid', $this->nid, '=')
            ->orderBy('submitted')
            ->range(0, 1)
            ->execute()
            ->fetch();

        $query->human_date = format_date($query->submitted, 'short');

        return $query;
    }

    public function getNewestSubmission()
    {

        $query = db_select('webform_submissions', 'wfs')
            ->fields('wfs', array('sid', 'submitted'))
            ->condition('nid', $this->nid, '=')
            ->orderBy('submitted', 'desc')
            ->range(0, 1)
            ->execute()
            ->fetch();

        $query->human_date = format_date($query->submitted, 'short');

        return $query;

    }

    public static function getPrinipcleContacts($nid, $roles)
    {
        // Get a list of user roles. Rule of non-admin, standard roles with
        // this filter
        $roles = array_filter(user_roles(), function ($item) {
            if (!array_search(trim($item), array('', 'authenticated user', 'anonymous user', 'administrator'))) {
                return TRUE;
            }
        });

        $query = db_select('users_roles', 'ur')
            ->fields('ur', array('uid'))
            ->condition('rid', array_keys($this->roles), 'IN')
            ->distinct()
            ->execute();

        $user_uids = array();
        while ($record = $query->fetch()) {
            $user_uids[] = $record->uid;
        }

       $pinciples = array();
        foreach (user_load_multiple($user_uids) as $u) {
            $pinciples[] = array(
                'name' => $u->realname,
                'uid' => $u->uid,
                'mail' => $u->mail,
                'roles' => implode(', ', array_values($u->roles))
            );

        }


        return $pinciples;

    }

    public function toReport()
    {
        return array(
            'Title' => $this->title,
            'Oldest' => $this->oldest_date->human_date,
            'Newest' => $this->newest_date->human_date,
//            'Contacts' => theme_table(array(
//                'title'=>t('Contacts'),
//                'header'=> array_keys($this->principle_contacts),
//                'rows'=>$this->principle_contacts,
//                'attributes'=>array()
//            ))
        );
    }

}