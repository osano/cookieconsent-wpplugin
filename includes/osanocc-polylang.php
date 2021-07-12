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


function icc_register_literals() {
    global $icc_i18n_literals;
    if ( is_admin() ){
        if ( function_exists('pll_register_string') ) {
            $plugin_name = 'Osano Cookie Consent';
            
            foreach ( $icc_i18n_literals as $value ){
                pll_register_string( $value['name'], $value['default'], $plugin_name );
            }
        }
    }
}

function icc_translate( $config ) {
    global $icc_i18n_literals;
    $result = array();
    if ( function_exists('pll_register_string')) {
        $a = json_decode($config, true);
        foreach ( $a as $key => $value ){
            $result[$key] = $value;
        }
        $result['content'] = array();
        foreach ( $icc_i18n_literals as $literal => $field ){
            if ( function_exists('pll__')){
                $result['content'][$literal] = pll__($field['default']);
            } else if ( $a['content'][$literal] != null ){
                $result['content'][$literal] = $a['content'][$literal];
            }
        }
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
                <h2 style="color: red">$wmessage</h2>
            </td>
        </tr>
HTML;
        echo $warning;
    }
}