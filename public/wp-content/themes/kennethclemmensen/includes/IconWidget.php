<?php
/**
 * The IconWidget class creates a widget to show an icon
 */
class IconWidget extends WP_Widget {

    private $title;
    private $icon;
    private $link;
    private $target;
    private $on;

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
        $this->on = 'on';
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'
     * @param array $instance The settings for the particular instance of the widget
     */
    public function widget($args, $instance) : void {
        echo $args['before_widget'].'<div class="iconwidget">';
        $title = (isset($instance[$this->title])) ? $instance[$this->title] : '';
        $icon = (isset($instance[$this->icon])) ? $instance[$this->icon] : '';
        $link = (isset($instance[$this->link])) ? $instance[$this->link] : '';
        $target = (isset($instance[$this->target]) && $instance[$this->target] === $this->on) ? '_blank' : '_self';
        if($title !== '') echo $args['before_title'].apply_filters('widget_title', $title).$args['after_title'];
        echo ($link !== '') ? '<a href="'.esc_url($link).'" target="'.$target.'">'.$icon.'</a>' : $icon;
        echo '</div>'.$args['after_widget'];
    }

    /**
     * Outputs the settings update form.
     *
     * @param array $instance Current settings
     */
    public function form($instance) : void {
        $title = (isset($instance[$this->title])) ? $instance[$this->title] : '';
        $titleFieldID = esc_attr($this->get_field_id($this->title));
        $icon = (isset($instance[$this->icon])) ? $instance[$this->icon] : '';
        $iconFieldID = esc_attr($this->get_field_id($this->icon));
        $link = (isset($instance[$this->link])) ? $instance[$this->link] : '';
        $linkFieldID = esc_attr($this->get_field_id($this->link));
        $target = (isset($instance[$this->target])) ? $instance[$this->target] : false;
        $targetFieldID = esc_attr($this->get_field_id($this->target));
        ?>
        <p>
            <label for="<?php echo $titleFieldID; ?>"><?php echo esc_attr('Title:'); ?></label>
            <input type="text" id="<?php echo $titleFieldID; ?>" class="widefat"
                   name="<?php echo esc_attr($this->get_field_name($this->title)); ?>"
                   value="<?php echo esc_attr($title); ?>">
            <label for="<?php echo $iconFieldID; ?>"><?php echo esc_attr('Icon:'); ?></label>
            <input type="text" id="<?php echo $iconFieldID; ?>" class="widefat"
                   name="<?php echo esc_attr($this->get_field_name($this->icon)); ?>"
                   value="<?php echo esc_attr($icon); ?>">
            <label for="<?php echo $linkFieldID; ?>"><?php echo esc_attr('Link:'); ?></label>
            <input type="url" id="<?php echo $linkFieldID; ?>" class="widefat"
                   name="<?php echo esc_attr($this->get_field_name($this->link)); ?>"
                   value="<?php echo esc_attr($link); ?>">
            <label for="<?php echo $targetFieldID; ?>"><?php echo esc_attr('Open in a new tab:'); ?></label>
            <input type="checkbox" id="<?php echo $targetFieldID; ?>" <?php checked($target, $this->on); ?>
                   name="<?php echo esc_attr($this->get_field_name($this->target)); ?>">
        </p>
        <?php
    }
}