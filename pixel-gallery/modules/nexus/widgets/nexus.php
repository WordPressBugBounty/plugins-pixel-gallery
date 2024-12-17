<?php

namespace PixelGallery\Modules\Nexus\Widgets;

use PixelGallery\Base\Module_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Repeater;
use PixelGallery\Utils;
use PixelGallery\Traits\Global_Widget_Controls;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Nexus extends Module_Base
{

    use Global_Widget_Controls;

    public function get_name()
    {
        return 'pg-nexus';
    }

    public function get_title()
    {
        return BDTPG . esc_html__('Nexus', 'pixel-gallery');
    }

    public function get_icon()
    {
        return 'pg-icon-nexus';
    }

    public function get_categories()
    {
        return ['pixel-gallery'];
    }

    public function get_keywords()
    {
        return ['nexus', 'grid', 'gallery'];
    }

    public function get_style_depends()
    {
        return ['pg-nexus'];
    }

    public function get_custom_help_url() {
		return 'https://youtu.be/At7BhTM-9Gs';
	}

    public function has_widget_inner_wrapper(): bool {
        return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
    }
	protected function is_dynamic_content(): bool {
		return false;
	}

	protected function register_controls() {

        $this->start_controls_section(
            'section_layout',
            [
                'label' => __('Layout', 'pixel-gallery'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        //Global
		$this->register_grid_controls('nexus');
		$this->register_global_height_controls('nexus');
		$this->register_title_tag_controls();
		$this->register_alignment_controls('nexus');
		$this->register_thumbnail_size_controls();

		//Global Lightbox Controls
		$this->register_lightbox_controls();
		$this->register_link_target_controls();
		$this->end_controls_section();

		//Repeater
		$this->start_controls_section(
			'section_item_content',
			[
				'label' => __('Items', 'pixel-gallery'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$repeater = new Repeater();
		$repeater->start_controls_tabs('tabs_item_content');
		$repeater->start_controls_tab(
			'tab_item_content',
			[
				'label' => esc_html__('Content', 'pixel-gallery'),
			]
		);
		$this->register_repeater_media_controls($repeater);
		$this->register_repeater_title_controls($repeater);
		$this->register_repeater_readmore_controls($repeater);
		$this->register_repeater_custom_url_controls($repeater);
		$this->register_repeater_hidden_item_controls($repeater);

		$repeater->end_controls_tab();
		$repeater->start_controls_tab(
			'tab_item_grid',
			[
				'label' => esc_html__('Grid', 'pixel-gallery'),
			]
		);
		$this->register_repeater_grid_controls($repeater, 'nexus');
		$this->register_repeater_item_height_controls($repeater, 'nexus');
		$repeater->end_controls_tab();
		$repeater->end_controls_tabs();
		$this->register_repeater_items_controls($repeater);
		$this->end_controls_section();

        //Style
        $this->start_controls_section(
            'pg_section_style',
            [
                'label'     => esc_html__('Items', 'pixel-gallery'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs_item_style');

        $this->start_controls_tab(
            'tab_item_normal',
            [
                'label' => esc_html__('Normal', 'pixel-gallery'),
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'item_border',
                'selector'  => '{{WRAPPER}} .pg-nexus-item',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'item_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'pixel-gallery'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .pg-nexus-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label'      => esc_html__('Padding', 'pixel-gallery'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .pg-nexus-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'item_box_shadow',
                'selector' => '{{WRAPPER}} .pg-nexus-item',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_item_hover',
            [
                'label' => esc_html__('Hover', 'pixel-gallery'),
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label'     => __('Primary Color', 'pixel-gallery'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pg-nexus-item:hover .pg-nexus-image-wrap::before' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'secondary_color',
            [
                'label'     => __('Secondary Color', 'pixel-gallery'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pg-nexus-item:hover .pg-nexus-image-wrap::after' => 'box-shadow: inset 0 0 0 10px {{VALUE}};',
                    '{{WRAPPER}} .pg-nexus-image-wrap::after' => 'box-shadow: inset 0 0 0 0 {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_hover_border_color',
            [
                'label'     => esc_html__('Border Color', 'pixel-gallery'),
                'type'      => Controls_Manager::COLOR,
                'condition' => [
                    'item_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .pg-nexus-item:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'item_hover_box_shadow',
                'selector' => '{{WRAPPER}} .pg-nexus-item:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        //Global Title Controls
        $this->register_title_controls('nexus');

        //Global Readmore Controls
        $this->register_readmore_controls('nexus');

        //Clip Path Controls
		$this->register_clip_path_controls('nexus');
    }

    public function render_items()
    {
        $settings = $this->get_settings_for_display();
        $id = 'pg-nexus-' . $this->get_id();
        $slide_index = 1;
        foreach ($settings['items'] as $index => $item) :

            $attr_name = 'grid-item' . $index;
			$this->add_render_attribute($attr_name, 'class', 'pg-nexus-item pg-item elementor-repeater-item-' . esc_attr($item['_id']), true);

			/**
			 * Render Video Inject Here
			 * Video Would be work on Media File & Lightbox
			 * @since 1.0.0
			 */
			if ($item['media_type'] == 'video') {
				$this->render_video_frame($item, $attr_name, $id);
			}

		?>

		<div <?php $this->print_render_attribute_string($attr_name); ?>>
			<?php if ($item['item_hidden'] !== 'yes') : ?>
			<?php $this->render_image_wrap($item, 'nexus'); ?>
                    <?php $this->render_title($item, 'nexus'); ?>
                    <?php if ('none' !== $settings['link_to'] && $settings['link_target'] == 'only_button') : ?>
                        <?php $this->render_readmore($item, $index, $id, 'nexus'); ?>
                    <?php endif; ?>

                    <?php if ('none' !== $settings['link_to'] && $settings['link_target'] == 'whole_item') : ?>
                        <?php $this->render_lightbox_link_url($item, $index, $id); ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

        <?php
            $slide_index++;
        endforeach;
    }

    public function render()
    {
        $settings   = $this->get_settings_for_display();
        $this->add_render_attribute('grid', 'class', 'pg-nexus-grid pg-grid');

        if (isset($settings['pg_in_animation_show']) && ($settings['pg_in_animation_show'] == 'yes')) {
			$this->add_render_attribute( 'grid', 'class', 'pg-in-animation' );
			if (isset($settings['pg_in_animation_delay']['size'])) {
				$this->add_render_attribute( 'grid', 'data-in-animation-delay', $settings['pg_in_animation_delay']['size'] );
			}
		}

		?>
		<div <?php $this->print_render_attribute_string('grid'); ?>>
            <?php $this->render_items(); ?>
        </div>
<?php
    }
}
