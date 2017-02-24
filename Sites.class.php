<?php

/**
 * Created by PhpStorm.
 * User: carbon
 * Date: 2/22/17
 * Time: 4:00 PM
 */
class Sites
{

    public $raw_folders = array();
    public $refined_links = array();
    public $master_url_pattern;


    public function __construct()
    {
        $this->master_url_pattern = explode("\n", variable_get('show_all_sites_URLpattern', ''));
        $this->raw_folders = $this->getRawFolders();
        $this->makeLinksIfPossible();



    }


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

        // Try to convert to URL based on above master url patterns
        for ($i = 0; $i < count($raw_folders); $i++) {

            // step through each URL pattern
            foreach ($this->master_url_pattern as $pattern) {
                $pattern_with_trailing_dot = $pattern . ".";
                $url_as_link = sprintf("https://%s/", $pattern);
                $raw_folders[$i] = str_replace($pattern_with_trailing_dot, $url_as_link, $raw_folders[$i]);
            }
        }

        sort($raw_folders);


        return $raw_folders;
    }


    private function makeLinksIfPossible()
    {

        for ($i = 0; $i < count($this->raw_folders); $i++) {
            if (stristr($this->raw_folders[$i], 'http')) {
                $this->refined_links[] = l($this->raw_folders[$i], $this->raw_folders[$i]);
            }
        }

    }


    public function getLinks() {
        return $this->refined_links;
    }

    public function getRaw() {
        return $this->raw_folders;
    }
}