<?php
// Output HTML content with hook priority
echo '<div class="test2-container">';
echo '<h2>Test 2 Content</h2>';
echo '<p>This content is being rendered from test2.php at ' . date('Y-m-d H:i:s') . '</p>';
if (did_action('init')) {
    global $wp_filter;
    $priority = '';
    if (isset($wp_filter['init'])) {
        foreach ($wp_filter['init']->callbacks as $p => $callbacks) {
            foreach ($callbacks as $callback) {
                // Check if this is our callback by looking at the file path
                if (is_array($callback['function']) && 
                    isset($callback['function'][0]) && 
                    is_string($callback['function'][0]) && 
                    strpos($callback['function'][0], 'test2.php') !== false) {
                    $priority = $p;
                    break 2;
                }
            }
        }
    }
    echo '<p>Hook Priority: ' . ($priority ? $priority : 'Not found') . '</p>';
    // Debug information
    echo '<pre style="display:none;">';
    print_r($wp_filter['init']->callbacks);
    echo '</pre>';
}
echo '</div>';
?>