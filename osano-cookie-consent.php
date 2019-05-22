<?php
/*
Plugin Name: Osano Cookie Consent
Plugin URI:
Description: Official Cookie Consent WordPress plugin.
Version:     1.0
Author:      Osano, Inc., a Public Benefit Corporation
Author URI:  https://cookieconsent.osano.com/
License:     MIT
License URI: https://opensource.org/licenses/MIT
Text Domain: icc
*/

defined( 'ABSPATH' ) or die( 'No!' );

define('CCVERSION', '3.1');
define('PLUGINVERSION', '1.0');

function icc_load_assets()
{
    $src = plugins_url( '/assets/cookie-consent', __FILE__ );
    wp_register_script( 'cookieconsent-script', $src.'/cookieconsent.min.js', array(), CCVERSION );
    wp_enqueue_script( 'cookieconsent-script' );
    wp_register_style( 'cookieconsent-style',  $src.'/cookieconsent.min.css', array(), CCVERSION );
    wp_enqueue_style( 'cookieconsent-style' );
}
add_action( 'wp_enqueue_scripts', 'icc_load_assets' );

function icc_create_snippet() {
    if(!is_admin() && get_option('icc_popup_enabled') && get_option('icc_popup_options')) {
        $config = get_option('icc_popup_options');
        echo '<script>window.cookieconsent.initialise('.$config.');</script>';
    }
}
add_action( 'wp_footer', 'icc_create_snippet' );

function icc_menu() {
    $page = add_options_page(
        'Cookieconsent options',
        'Cookieconsent',
        'manage_options',
        'icc-options',
        'icc_options_page'
    );
    add_action('admin_print_scripts-' . $page, 'icc_load_admin_scripts');
}
add_action( 'admin_menu', 'icc_menu' );

function icc_load_admin_scripts() {
    $src = plugins_url( '/assets/js/scripts.js', __FILE__ );
    wp_register_script( 'cookieconsent-admin-script', $src, array(), PLUGINVERSION );
    wp_enqueue_script( 'cookieconsent-admin-script' );
    $src2 = plugins_url( '/assets/css/admin.css', __FILE__ );
    wp_register_style( 'cookieconsent-admin-style',  $src2, array(), PLUGINVERSION );
    wp_enqueue_style( 'cookieconsent-admin-style' );
}

function icc_register_settings() {
    register_setting( 'icc-options', 'icc_popup_enabled' );
    register_setting( 'icc-options', 'icc_popup_options' );
    register_setting( 'icc-options', 'choose-position' );
    register_setting( 'icc-options', 'choose-layout' );
    register_setting( 'icc-options', 'theme-selector' );
    register_setting( 'icc-options', 'policy' );
    register_setting( 'icc-options', 'link-href' );
    register_setting( 'icc-options', 'choose-cookie-compliance' );
    register_setting( 'icc-options', 'message-text' );
    register_setting( 'icc-options', 'dismiss-text' );
    register_setting( 'icc-options', 'allow-text' );
    register_setting( 'icc-options', 'link-text' );
    register_setting( 'icc-options', 'deny-text' );
    register_setting( 'icc-options', 'custom-attributes' );
}
add_action( 'admin_init', 'icc_register_settings' );

function icc_options_page() {
    echo '<form method="post" action="options.php">';
        settings_fields( 'icc-options' );
        do_settings_sections( 'icc-options' );
        ?>
        <div class="wrap" id="cc-options-form">
            <h2>Cookieconsent options</h2>
            <h3>Enable cookieconsent</h3>
            <table class="form-table">
                <input type="hidden" id="icc_popup_options" name="icc_popup_options" value="{enabled=false}">
                <tr valign="top">
                    <td><label for="icc_popup_enabled">Enabled</label></td>
                    <td><input type="checkbox" id="icc_popup_enabled" name="icc_popup_enabled" value="1" <?php echo get_option('icc_popup_enabled') ? 'checked' : '' ?> /></td>
        </tr>
        </table>
        <?php submit_button(); ?>
        <h3>Customise you Cookie Consent window</h3>
        <table class="form-table">
            <tr><th colspan="2">1. Position</th></tr>
            <tr>
                <td>
                    <input type="radio" id="position-bottom" name="choose-position" value="bottom" checked><label for="position-bottom">Banner bottom</label><br />
                    <input type="radio" id="position-top" name="choose-position" value="top" <?php echo get_option('choose-position')=='top' ? 'checked' : '' ?>><label for="position-top">Banner top</label><br />
                </td>

                <td>
                    <input type="radio" id="position-bottom-left" name="choose-position" value="bottom-left" <?php echo get_option('choose-position')=='bottom-left' ? 'checked' : '' ?>><label for="position-bottom-left">Floating left</label><br />
                    <input type="radio" id="position-bottom-right" name="choose-position" value="bottom-right" <?php echo get_option('choose-position')=='bottom-right' ? 'checked' : '' ?>><label for="position-bottom-right">Floating right</label>
                </td>
            </tr>
            <tr><th colspan="2">2. Layout</th></tr>
            <tr>
                <td>
                    <input type="radio" id="layout-block" name="choose-layout" value="block" checked><label for="layout-block">Block</label><br />
                    <input type="radio" id="layout-edgeless" name="choose-layout" value="edgeless" <?php echo get_option('choose-layout')=='edgeless' ? 'checked' : '' ?>><label for="layout-edgeless">Edgeless</label>
                </td>
                <td>
                    <input type="radio" id="layout-classic" name="choose-layout" value="classic" <?php echo get_option('choose-layout')=='classic' ? 'checked' : '' ?>><label for="layout-classic">Classic</label><br />
                    <input type="radio" id="layout-wire" name="choose-layout" value="wire" <?php echo get_option('choose-layout')=='wire' ? 'checked' : '' ?>><label for="layout-wire">Wire</label>
                </td>
            </tr>
            <tr><th colspan="2">3. Palette</th></tr>
            <tr>
                <td colspan="2">
                    <span id="choose-colours" class="choose-colours"></span>
                    <script>var selected = "<?php echo get_option('theme-selector') ?>";</script>
                </td>
            </tr>
            <tr><th colspan="2">4. Learn more link</th></tr>
            <tr>
                <td colspan="2">
                    <input type="radio" id="aboutcookies" name="policy" value="aboutcookies" checked>
                    <label for="aboutcookies">Link to cookiesandyou.com <a href="http://cookiesandyou.com" target="_blank" class="nohover"> <i class="fa fa-external-link" aria-hidden="true"></i></a></label><br />
                    <input type="radio" id="policylink" name="policy" value="policylink" <?php echo get_option('policy')=='policylink' ? 'checked' : '' ?>>
                    <label for="policylink">Link to your own policy (leave empty to disable link)</label><br />
                    <input type="text" name="link-href" placeholder="www.example.com/cookiepolicy" value="<?php echo get_option('link-href') ?>" onclick="document.getElementById('policylink').checked = true;" />
                </td>
            </tr>
            <tr><th colspan="2">5. Compliance type</th></tr>
            <tr>
                <td colspan="2">
                    <input type="radio" id="only-tell" name="choose-cookie-compliance" value="info" checked><label for="only-tell">Just tell users that we use cookies</label><br />
                    <input type="radio" id="let-opt-out" name="choose-cookie-compliance" value="opt-out" <?php echo get_option('choose-cookie-compliance')=='opt-out' ? 'checked' : '' ?>><label for="let-opt-out">Let users opt out of cookies (Advanced)</label><br />
                    <input type="radio" id="ask-to-opt" name="choose-cookie-compliance" value="opt-in" <?php echo get_option('choose-cookie-compliance')=='opt-in' ? 'checked' : '' ?>><label for="ask-to-opt">Ask users to opt into cookies (Advanced)</label><br />
                    <p>For more information about compliance see <a href="http://cookieconsent.osano.com/documentation/compliance/" target="_blank">documentation</a></p>
                </td>
            </tr>
            <tr><th colspan="2">6. Custom text</th></tr>
            <tr>
                <td colspan="2" style="padding-bottom:0;"><p><b>Message</b></p>
                    <textarea name="message-text" id="message-text" placeholder="This website uses cookies to ensure you get the best experience on our website." maxlength="300"><?php echo get_option('message-text') ?></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <p><b>Dismiss button text</b></p><input type="text" name="dismiss-text" placeholder="Got it!" maxlength="30"  value="<?php echo get_option('dismiss-text') ?>" /><br />
                    <span id="text-accept-container"><p><b>Accept button text</b></p><input type="text" name="allow-text" placeholder="Allow cookies" maxlength="30" value="<?php echo get_option('allow-text') ?>" /></span>
                </td>
                <td>
                    <span id="text-policylink-container"><p><b>Policy link text</b></p><input type="text" name="link-text" placeholder="Learn more" maxlength="30" value="<?php echo get_option('link-text') ?>" /></span><br />
                    <span id="text-deny-container"><p><b>Deny button text</b></p><input type="text" name="deny-text" placeholder="Refuse cookies" maxlength="30" value="<?php echo get_option('deny-text') ?>" /></span>
                </td>
            </tr>
            <tr><th colspan="2"><span style="color:gray">Advanced</span>: Custom attributes</th></tr>
            <tr>
                <td colspan="2">
                    <textarea style="height: 150px;" name="custom-attributes" id="custom-attributes"><?php echo get_option('custom-attributes') ?></textarea>

                    <p>This overwrites all other options.</p>
                    <p>List of available attributes can be found in Cookie Consent <a href="http://cookieconsent.osano.com/documentation/javascript-api/">documentation</a>.</p>
                    <p>
                        Example:<br />
                        <code>palette:{popup:{background:"#fff"},button:{background:"#aa0000"}}</code>
                    </p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
        </div>
        <?php
    echo '</form>';
}