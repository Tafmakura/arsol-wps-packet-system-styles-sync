<?php
/**
 * Custom Widgets for the Arsol WP Snippets Packet Example
 */

class Arsol_Custom_Widgets {

    public function __construct() {
        add_action('widgets_init', array($this, 'register_widgets'));
    }

    public function register_widgets() {
        register_widget('Arsol_Example_Widget');
    }
}

class Arsol_Example_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'arsol_example_widget',
            __('Arsol Example Widget', 'text_domain'),
            array('description' => __('A simple example widget', 'text_domain'))
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        echo __('Hello, World!', 'text_domain');
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('New title', 'text_domain');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php esc_attr_e('Title:', 'text_domain'); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
}

// Initialize the custom widgets
function arsol_register_custom_widgets() {
    $custom_widgets = new Arsol_Custom_Widgets();
}
add_action('init', 'arsol_register_custom_widgets');
?>