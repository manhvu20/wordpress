<?php

if ( !hara_redux_framework_activated() )  return;

class Tbay_Widget_Template_Custom_Block extends Tbay_Widget
{
    public function __construct()
    {
        parent::__construct(
            'hara_template_custom_block',
            esc_html__('Hara Template Custom Block', 'hara'),
            [
                'description' => esc_html__('Embed your saved Thembay Template Custom Block', 'hara'),
            ]
        );
    }
    public function getTemplate()
    {
        $this->template = 'template-elementor.php';
    }
    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);
        $before_title = $args['before_title'];
        $after_title = $args['after_title'];
        if ($title) {
            echo trim($before_title)  . trim($title) . trim($after_title);
        }

        if (! empty($instance['template_id']) && 'publish' === get_post_status($instance['template_id'])) {

            $template_id = $instance['template_id'];
            $template_id   = hara_wpml_object_id( $template_id, 'post', true, apply_filters( 'wpml_current_language', NULL ) );

            $this->sidebar_id = $args['id'];

            if (hara_elementor_activated() && Elementor\Plugin::instance()->documents->get( $template_id )->is_built_with_elementor()) {
                echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display($template_id);
            } else {
                $content_post   = get_post($template_id);
                $content        = $content_post->post_content;
                echo do_shortcode($content);
            }
           

            unset($this->sidebar_id);
        }
    }
    public function form($instance)
    {
        $default = [
            'title' => '',
            'template_id' => '',
        ];

        $instance = array_merge($default, $instance);

        $templates = hara_tbay_get_ids_custom_block();

        if (! $templates) {
            echo '<div id="tbay-elementor-widget-template-empty-templates">
            <div class="tbay-elementor-widget-template-empty-templates-icon"><i class="eicon-nerd" aria-hidden="true"></i></div>
            <div class="tbay-elementor-widget-template-empty-templates-title">' . esc_html__('You Haven’t Saved Templates Yet.', 'hara') . '</div>
            <div class="tbay-elementor-widget-template-empty-templates-footer">' . esc_html__('Want to learn more about Elementor library?', 'hara') . ' <a class="elementor-widget-template-empty-templates-footer-url" href="https://go.elementor.com/docs-library/" target="_blank">' . esc_html__('Click Here', 'hara') . '</a>
            </div>
            </div>';

            return;
        } ?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title', 'hara'); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>">
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('template_id')); ?>"><?php esc_html_e('Choose Template', 'hara'); ?>:</label>
			<select class="widefat elementor-widget-template-select" id="<?php echo esc_attr($this->get_field_id('template_id')); ?>" name="<?php echo esc_attr($this->get_field_name('template_id')); ?>">
				<option value="">— <?php esc_html_e('Select', 'hara'); ?> —</option>
				<?php
                foreach ($templates as $key => $template) :
                    $selected = selected($key, $key); ?>
					<option value="<?php echo esc_attr($key); ?>" <?php echo trim($selected); ?>>
						<?php echo trim($template); ?>
					</option>
				<?php endforeach; ?>
			</select>

			<?php
                if (!empty($instance['template_id'])) {
                    $edit_link = admin_url('post.php?post='. $instance['template_id'] .'&action=elementor');
                } else {
                    $edit_link = admin_url('edit.php?post_type=tbay_custom_post');
                } ?>
			<a target="_blank" class="elementor-edit-template" href="<?php echo esc_url($edit_link); ?>">
				<i class="eicon-pencil"></i> <?php echo esc_html__('Edit Template', 'hara'); ?>
			</a>
		</p>
		<?php
    }


    public function update($new_instance, $old_instance)
    {
        $instance = [];
        $instance['title'] = (! empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['template_id'] = $new_instance['template_id'];

        return $instance;
    }
}
