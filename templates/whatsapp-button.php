<?php
/**
 * WhatsApp button and popup template
 *
 * @package Saint_Hossam_WhatsApp_Chat
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<!-- Floating WhatsApp Button -->
<div class="sh-whatsapp-button sh-position-<?php echo esc_attr($position); ?>">
    <button class="sh-wa-trigger" aria-label="<?php _e('فتح محادثة واتساب', 'sh-whatsapp-chat'); ?>">
        <i class="fab fa-whatsapp"></i>
    </button>
</div>

<!-- WhatsApp Chat Popup -->
<div id="sh-whatsapp-popup" class="sh-whatsapp-popup sh-position-<?php echo esc_attr($position); ?>" style="display:none;">
    <div class="sh-whatsapp-popup-content">
        <!-- Close Button -->
        <button class="sh-whatsapp-close" aria-label="<?php _e('إغلاق', 'sh-whatsapp-chat'); ?>">
            <i class="fas fa-times"></i>
        </button>
        
        <!-- Header -->
        <div class="sh-whatsapp-header">
            <div class="sh-wa-icon">
                <i class="fab fa-whatsapp"></i>
            </div>
            <div class="sh-wa-info">
                <h3><?php _e('تواصل معنا', 'sh-whatsapp-chat'); ?></h3>
                <span class="sh-wa-status">
                    <span class="sh-status-dot"></span>
                    <?php _e('متاح الآن', 'sh-whatsapp-chat'); ?>
                </span>
            </div>
        </div>
        
        <!-- Body -->
        <div class="sh-whatsapp-body">
            <!-- Welcome Message Bubble -->
            <div class="sh-welcome-bubble">
                <p><?php echo esc_html($welcome_msg); ?></p>
                <span class="sh-bubble-time"><?php echo current_time('H:i'); ?></span>
            </div>
            
            <!-- Message Input -->
            <div class="sh-message-input">
                <textarea id="sh-whatsapp-message" 
                          placeholder="<?php _e('اكتب رسالتك هنا...', 'sh-whatsapp-chat'); ?>" 
                          rows="3"></textarea>
                <button id="sh-send-whatsapp" class="sh-send-btn">
                    <i class="fas fa-paper-plane"></i>
                    <?php _e('إرسال', 'sh-whatsapp-chat'); ?>
                </button>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="sh-whatsapp-footer">
            <small>
                <?php _e('مدعوم بواسطة', 'sh-whatsapp-chat'); ?> 
                <strong>Saint Hossam WhatsApp Chat</strong>
            </small>
        </div>
    </div>
</div>
