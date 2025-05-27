<?php
// Output HTML content with hook priority
echo '<div class="test1-container">';
echo '<h2>Test 1 Content</h2>';
echo '<p>This content is being rendered from test1.php at ' . date('Y-m-d H:i:s') . '</p>';
if (did_action('init')) {
    global $wp_filter;
    $priority = '';
    if (isset($wp_filter['init'])) {
        foreach ($wp_filter['init']->callbacks as $p => $callbacks) {
            foreach ($callbacks as $callback) {
                if (is_array($callback['function']) && 
                    is_array($callback['function'][0]) && 
                    $callback['function'][0][0] === 'test1.php') {
                    $priority = $p;
                    break 2;
                }
            }
        }
    }
    echo '<p>Hook Priority: ' . ($priority ? $priority : 'Not found') . '</p>';
}
echo '</div>';
?>