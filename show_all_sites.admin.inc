<?php

/**
 * @file
 * Settings file for module
 */



/**
 * Form builder.
 */
function show_all_sites_settings_form($form, &$form_state) {
    $form['show_all_sites_URLpattern'] = array(
        '#type' => 'textarea',
        '#title' => t('Master URL Patterns'),
        '#default_value' => variable_get('show_all_sites_URLpattern', ''),
        '#required' => TRUE,
        '#description' => t(sprintf(
            "It's suggested you use the domain name of your server. In this case \"<b>%s</b>\". No slashes. Each URL on separate line.",
            $GLOBALS['_SERVER']['SERVER_NAME']
        )),
    );

    return system_settings_form($form);
}
