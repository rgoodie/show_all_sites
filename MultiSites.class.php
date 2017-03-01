<?php

/**
 * Created by PhpStorm.
 * User: carbon
 * Date: 2/22/17
 * Time: 4:00 PM
 */
class MultiSites
{

    public $raw_folders = array();
    public $refined_links = array();
    public $master_url_pattern;


    public function __construct()
    {
        $this->master_url_pattern = explode("\n", trim(variable_get('show_all_sites_URLpattern', '')));
        $this->raw_folders = $this->getRawFolders();
        $this->refined_links = $this->makeLinksIfPossible();





    }

    /**
     * @return array of folders in /sites/
     * @throws Exception
     */
    private function getRawFolders()
    {

        // basic sites folder properly coded
        $drupal_base_path = DRUPAL_ROOT . DIRECTORY_SEPARATOR . "sites" . DIRECTORY_SEPARATOR;

        if (!is_dir($drupal_base_path)) {
            throw new Exception(sprintf("%s is not a folder", $drupal_base_path));
        }

        // glob folders
        $raw_folders = glob($drupal_base_path . "*", GLOB_ONLYDIR);

        // make array presentable by removing base folder name
        $raw_folders = array_map(function ($item) use ($drupal_base_path) {
            // remove base path to isolate only base folder name
            $item = str_replace($drupal_base_path, "", $item);
            return $item;
        }, $raw_folders);

        sort($raw_folders);

        return $raw_folders;
    }

    /*
     * Refines links. Creates new array. If there is a match with the module settings url master pattern, then
     * this attempts to create a link.
     */
    private function makeLinksIfPossible()
    {

        // if url pattern not set
        if ((count($this->master_url_pattern) < 1 ) || (strlen($this->master_url_pattern[0]) < 3 ))  {
            return array(t('URL pattern not set in module settings.'));
        }



        $refind_links = array();
        for ($i = 0; $i < count($this->raw_folders); $i++) {
            foreach($this->master_url_pattern as $pattern) {
                if (stristr($this->raw_folders[$i], $pattern)) {
                    $new_url = str_replace($pattern . '.', 'https://' . $pattern . '/', $this->raw_folders[$i] ) ;
                    $refind_links[] = l($new_url, $new_url);
                }
            }
        }
        return $refind_links;
    }


    public function getLinks() {
        return $this->refined_links;
    }

    public function getRaw() {
        return $this->raw_folders;
    }
}