<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

class WP_Bootstrap_Navwalker extends Walker_Nav_Menu {

    public function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class='dropdown-menu' role='menu'>\n";
    }

    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        $classes[] = 'nav-item';

        $has_children = isset($args->has_children) && $args->has_children;

        if ($has_children) {
            $classes[] = 'dropdown';
        }
        if (in_array('current-menu-item', $classes)) {
            $classes[] = 'active';
        }

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $output .= "$indent<li class='" . esc_attr($class_names) . "'>";

        // Attributes for <a>
        $atts = array();
        $atts['title'] = !empty($item->attr_title) ? $item->attr_title : strip_tags($item->title);
        $atts['href'] = !empty($item->url) ? $item->url : '#';
        $atts['class'] = ($depth > 0) ? 'dropdown-item' : 'nav-link';

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ($attr === 'href') ? esc_url($value) : esc_attr($value);
                $attributes .= " $attr=\"$value\"";
            }
        }

        // Output <a> tag separately to make it always clickable
        $output .= "<a$attributes>" . apply_filters('the_title', $item->title, $item->ID) . "</a>";

        // Dropdown toggle button (only for mobile)
        if ($has_children) {
            $output .= '<button class="mobile-toggler d-block d-lg-none" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-chevron-right"></i>
                        </button>';
        }
    }

    public function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output) {
        if (!$element) return;
        $id_field = $this->db_fields['id'];
        if (is_object($args[0])) {
            $args[0]->has_children = !empty($children_elements[$element->$id_field]);
        }
        parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }

    public static function fallback($args) {
        if (current_user_can('edit_theme_options')) {
            echo '<ul class="nav"><li class="nav-item"><a class="nav-link" href="' . esc_url(admin_url('nav-menus.php')) . '">Add a menu</a></li></ul>';
        }
    }
}
?>
