<?php
// Security functions for CSRF protection and input validation

/**
 * Generate CSRF token
 */
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Sanitize input
 */
function sanitize_input($data) {
    if (is_array($data)) {
        return array_map('sanitize_input', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Encrypt sensitive data
 */
function encrypt_data($data) {
    $key = 'your-encryption-key-here'; // Should be in config
    $cipher = "aes-256-cbc";
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext = openssl_encrypt($data, $cipher, $key, 0, $iv);
    return base64_encode($iv . $ciphertext);
}

/**
 * Decrypt sensitive data
 */
function decrypt_data($encrypted_data) {
    $key = 'your-encryption-key-here'; // Should be in config
    $cipher = "aes-256-cbc";
    $data = base64_decode($encrypted_data);
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = substr($data, 0, $ivlen);
    $ciphertext = substr($data, $ivlen);
    return openssl_decrypt($ciphertext, $cipher, $key, 0, $iv);
}

/**
 * Log audit action
 */
function audit_log($action, $user_id, $details = '') {
    $db = get_db_connection();
    $stmt = $db->prepare("INSERT INTO audit_log (user_id, action, details, ip_address, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$user_id, $action, $details, $_SERVER['REMOTE_ADDR']]);
}

/**
 * Check rate limiting
 */
function check_rate_limit($action, $user_id, $limit = 10, $window = 60) {
    $db = get_db_connection();
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM audit_log WHERE user_id = ? AND action = ? AND created_at > DATE_SUB(NOW(), INTERVAL ? SECOND)");
    $stmt->execute([$user_id, $action, $window]);
    return $stmt->fetch()['count'] < $limit;
}
?>