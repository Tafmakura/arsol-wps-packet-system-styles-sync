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
                if (is_array($callback['function']) && 
                    is_array($callback['function'][0]) && 
                    $callback['function'][0][0] === 'test2.php') {
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