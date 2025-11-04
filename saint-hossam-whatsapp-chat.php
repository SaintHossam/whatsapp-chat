<?php
/**
 * Plugin Name: WhatsApp Chat
 * Plugin URI: https://github.com/SaintHossam/whatsapp-chat
 * Description: Professional floating WhatsApp chat window for Arabic websites
 * Version: 1.0.0
 * Author: Hossam Hamdy (Saint Hossam)
 * Author URI: https://github.com/SaintHossam
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: sh-whatsapp-chat
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define constants
define('SH_WA_CHAT_VERSION', '1.0.0');
define('SH_WA_CHAT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SH_WA_CHAT_PLUGIN_URL', plugin_dir_url(__FILE__));

class Saint_Hossam_WhatsApp_Chat {
    
    private static $instance = null;
    
    /**
     * Get singleton instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        // Load text domain
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        
        // Enqueue styles and scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        
        // Render WhatsApp button
        add_action('wp_footer', array($this, 'render_whatsapp_button'));
    }
    
    /**
     * Load plugin text domain for translations
     */
    public function load_textdomain() {
        load_plugin_textdomain('sh-whatsapp-chat', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    /**
     * Add settings page to WordPress admin
     */
    public function add_admin_menu() {
        add_options_page(
            __('إعدادات واتساب شات', 'sh-whatsapp-chat'),
            __('واتساب شات', 'sh-whatsapp-chat'),
            'manage_options',
            'sh-whatsapp-chat',
            array($this, 'render_settings_page')
        );
    }
    
    /**
     * Register plugin settings
     */
    public function register_settings() {
        register_setting('sh_whatsapp_chat_settings', 'sh_wa_phone_number');
        register_setting('sh_whatsapp_chat_settings', 'sh_wa_welcome_message');
        register_setting('sh_whatsapp_chat_settings', 'sh_wa_button_position');
        register_setting('sh_whatsapp_chat_settings', 'sh_wa_enable_chat');
    }
    
    /**
     * Render settings page in admin
     */
    public function render_settings_page() {
        ?>
        <div class="wrap" dir="rtl">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('sh_whatsapp_chat_settings');
                do_settings_sections('sh_whatsapp_chat_settings');
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="sh_wa_enable_chat"><?php _e('تفعيل الشات', 'sh-whatsapp-chat'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" id="sh_wa_enable_chat" name="sh_wa_enable_chat" value="1" <?php checked(1, get_option('sh_wa_enable_chat'), true); ?> />
                            <p class="description"><?php _e('تفعيل أو تعطيل نافذة الواتساب', 'sh-whatsapp-chat'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="sh_wa_phone_number"><?php _e('رقم الواتساب', 'sh-whatsapp-chat'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="sh_wa_phone_number" name="sh_wa_phone_number" 
                                   value="<?php echo esc_attr(get_option('sh_wa_phone_number')); ?>" 
                                   class="regular-text" placeholder="966512345678" />
                            <p class="description"><?php _e('أدخل رقم الواتساب مع كود الدولة بدون علامة +', 'sh-whatsapp-chat'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="sh_wa_welcome_message"><?php _e('رسالة الترحيب', 'sh-whatsapp-chat'); ?></label>
                        </th>
                        <td>
                            <textarea id="sh_wa_welcome_message" name="sh_wa_welcome_message" 
                                      rows="3" class="large-text"><?php echo esc_textarea(get_option('sh_wa_welcome_message', 'مرحباً! كيف يمكننا مساعدتك؟')); ?></textarea>
                            <p class="description"><?php _e('الرسالة التي تظهر في نافذة الشات', 'sh-whatsapp-chat'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="sh_wa_button_position"><?php _e('موضع الزر', 'sh-whatsapp-chat'); ?></label>
                        </th>
                        <td>
                            <select id="sh_wa_button_position" name="sh_wa_button_position">
                                <option value="right" <?php selected(get_option('sh_wa_button_position', 'right'), 'right'); ?>><?php _e('يمين', 'sh-whatsapp-chat'); ?></option>
                                <option value="left" <?php selected(get_option('sh_wa_button_position'), 'left'); ?>><?php _e('يسار', 'sh-whatsapp-chat'); ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
                <?php submit_button(__('حفظ التغييرات', 'sh-whatsapp-chat')); ?>
            </form>
        </div>
        <?php
    }
    
    /**
     * Enqueue CSS and JavaScript assets
     */
    public function enqueue_assets() {
        // Check if chat is enabled
        if (!get_option('sh_wa_enable_chat')) {
            return;
        }
        
        // Font Awesome
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css', array(), '6.7.2');
        
        // Custom styles
        wp_enqueue_style('sh-whatsapp-chat', SH_WA_CHAT_PLUGIN_URL . 'assets/css/style.css', array(), SH_WA_CHAT_VERSION);
        
        // Custom script
        wp_enqueue_script('sh-whatsapp-chat', SH_WA_CHAT_PLUGIN_URL . 'assets/js/script.js', array('jquery'), SH_WA_CHAT_VERSION, true);
        
        // Pass data to JavaScript
        wp_localize_script('sh-whatsapp-chat', 'shWhatsAppChat', array(
            'phoneNumber' => get_option('sh_wa_phone_number'),
            'welcomeMessage' => get_option('sh_wa_welcome_message', 'مرحباً! كيف يمكننا مساعدتك؟'),
            'position' => get_option('sh_wa_button_position', 'right')
        ));
    }
    
    /**
     * Render WhatsApp button in footer
     */
    public function render_whatsapp_button() {
        // Check if chat is enabled
        if (!get_option('sh_wa_enable_chat')) {
            return;
        }
        
        // Get phone number
        $phone = get_option('sh_wa_phone_number');
        if (empty($phone)) {
            return;
        }
        
        // Get settings
        $welcome_msg = get_option('sh_wa_welcome_message', 'مرحباً! كيف يمكننا مساعدتك؟');
        $position = get_option('sh_wa_button_position', 'right');
        
        // Include template
        include SH_WA_CHAT_PLUGIN_DIR . 'templates/whatsapp-button.php';
    }
}

/**
 * Initialize plugin
 */
function saint_hossam_whatsapp_chat_init() {
    return Saint_Hossam_WhatsApp_Chat::get_instance();
}
add_action('plugins_loaded', 'saint_hossam_whatsapp_chat_init');
