<?php
$icc_i18n_literals = array(
    'message' => array(
        'name' => 'Message',
        'default' => 'This website uses cookies to ensure you get the best experience on our website.'
    ),
    'dismiss' => array(
        'name' => 'Dismiss button text',
        'default' => 'Got it!'
    ),
    'link' => array(
        'name' => 'Policy link text',
        'default' => 'Learn more'
    ),
    'deny' => array(
        'name' => 'Deny button text',
        'default' => 'Refuse cookies'
    ),
);


function icc_i18n_init_literals() {
    global $icc_i18n_literals;
    if ( is_admin() ){
        if ( function_exists('pll_register_string') ) {
            $plugin_name = 'Osano Cookie Consent';
            
            foreach ( $icc_i18n_literals as $value ){
                pll_register_string( $value['name'], $value['default'], $plugin_name );
            }
        }
    }
    
    add_filter('privacy_policy_url', 'icc_translate_privacy_page', 10, 2);
}

function icc_translate( $config ) {
    global $icc_i18n_literals;
    $result = array();
    if ( function_exists('pll_register_string')) {
        $a = json_decode($config, true);
        // There are more fields to be preserved than just text.
        $save_keys = array('href');
        $saved_content = array(); 
        foreach ( $a as $key => $value ){
            $result[$key] = $value;
            if ( $key == 'content' ) {
                foreach ( $save_keys as $key2save ) {
                    if ($result[$key][$key2save] != null ) {
                        $saved_content[$key2save] = $result[$key][$key2save];
                    }
                }
            }
        }
        $result['content'] = array();
        foreach ( $icc_i18n_literals as $literal => $field ){
            if ( function_exists('pll__')){
                $result['content'][$literal] = pll__($field['default']);
            } else if ( $a['content'][$literal] != null ){
                $result['content'][$literal] = $a['content'][$literal];
            }
        }
        $result['content'] = array_merge($result['content'], $saved_content);
        return json_encode($result);
    } else {
        return $config;
    }
    
}

function icc_i18n_warning() {
    if ( function_exists('pll_register_string')) {
        $wmessage = 'You have Polylang plugin installed. You should set up literals in "' . __( 'Languages', 'polylang' ) . ' &gt; ' . __( 'Strings translations', 'polylang' ) . '"';
        $warning = <<<HTML
        <tr>
            <td colspan="2" style="padding-bottom:0;">
                <p style="color: red">$wmessage</p>
            </td>
        </tr>
HTML;
        echo $warning;
    }
}

/**
 * 
 * @param string $url
 * @param int $policy_page_id
 */
function icc_translate_privacy_page( $url, $policy_page_id){
    //die();
    if ( function_exists('pll_get_post_translations') && function_exists('pll_current_language') && function_exists('pll_default_language')) {
        $trans = pll_get_post_translations( $policy_page_id );
        $lang = pll_current_language();
        if (empty($lang)) {
            $lang = pll_default_language();
        }
        if ( !empty($trans) && $trans[$lang] != null ){
            return get_post_permalink($trans[$lang]);
        }
    }
    return $url;
}