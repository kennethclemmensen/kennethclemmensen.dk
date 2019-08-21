<?php
/**
 * The IconWidget class creates a widget to show an icon
 */
final class IconWidget extends WP_Widget {

    private $title;
    private $icon;
    private $link;
    private $target;
    private $checkboxCheckedValue;

    /**
     * IconWidget constructor
     */
    public function __construct() {
        parent::__construct(self::class, 'Icon', [
            'classname' => self::class,
            'description' => 'Add an icon'
        ]);
        $this->title = 'title';
        $this->icon = 'icon';
        $this->link = 'link';
        $this->target = 'target';
        $this->checkboxCheckedValue = 'on';
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'
     * @param array $instance the settings for the particular instance of the widget
     */
    public function widget($args, $instance) : void {
        echo $args['before_widget'].'<div class="iconwidget">';
        $title = $instance[$this->title];
        $icon = '<i class="'.$instance[$this->icon].'"></i>';
        $link = $instance[$this->link];
        $target = ($instance[$this->target] === $this->checkboxCheckedValue) ? '_blank' : '_self';
        if($title !== '') echo $args['before_title'].apply_filters('widget_title', $title).$args['after_title'];
        echo ($link !== '') ? '<a href="'.esc_url(do_shortcode($link)).'" target="'.$target.'">'.$icon.'</a>' : $icon;
        echo '</div>'.$args['after_widget'];
    }

    /**
     * Show the settings update form for the widget
     *
     * @param array $instance the current settings for the widget
     */
    public function form($instance) : void {
        $title = $instance[$this->title];
        $titleFieldID = esc_attr($this->get_field_id($this->title));
        $icon = $instance[$this->icon];
        $iconFieldID = esc_attr($this->get_field_id($this->icon));
        $link = $instance[$this->link];
        $linkFieldID = esc_attr($this->get_field_id($this->link));
        $target = $instance[$this->target];
        $targetFieldID = esc_attr($this->get_field_id($this->target));
        ?>
        <p>
            <label for="<?php echo $titleFieldID; ?>"><?php echo esc_attr('Title:'); ?></label>
            <input type="text" id="<?php echo $titleFieldID; ?>" class="widefat"
                   name="<?php echo esc_attr($this->get_field_name($this->title)); ?>"
                   value="<?php echo esc_attr($title); ?>">
            <label for="<?php echo $iconFieldID; ?>"><?php echo esc_attr('Icon:'); ?></label>
            <select id="<?php echo $iconFieldID; ?>" class="widefat"
                    name="<?php echo esc_attr($this->get_field_name($this->icon)); ?>">
                <?php
                $icons = $this->getIcons();
                foreach($icons as $key => $value) {
                    echo '<option value="'.$value.'" '.selected($icon, $value).'>'.$key.'</option>';
                }
                ?>
            </select>
            <label for="<?php echo $linkFieldID; ?>"><?php echo esc_attr('Link:'); ?></label>
            <input type="text" id="<?php echo $linkFieldID; ?>" class="widefat"
                   name="<?php echo esc_attr($this->get_field_name($this->link)); ?>"
                   value="<?php echo esc_attr($link); ?>">
            <label for="<?php echo $targetFieldID; ?>"><?php echo esc_attr('Open in a new tab:'); ?></label>
            <input type="checkbox"
                   id="<?php echo $targetFieldID; ?>" <?php checked($target, $this->checkboxCheckedValue); ?>
                   name="<?php echo esc_attr($this->get_field_name($this->target)); ?>">
        </p>
        <?php
    }

    /**
     * Get the icons
     *
     * @return array the icons
     */
    private function getIcons() : array {
        $icons = file_get_contents(__DIR__.'/../json/icons.json');
        return ($icons) ? json_decode($icons, true) : [];
    }
}