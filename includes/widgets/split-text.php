<?php
class SplitText extends \Elementor\Widget_Base
{

	public function get_name()
	{
		return 'ms-split-text';
	}

	public function get_title()
	{
		return esc_html__('Split Text', 'stp');
	}

	public function get_icon()
	{
		return 'eicon-heading';
	}

	public function get_categories()
	{
		return ['basic'];
	}

	public function get_keywords()
	{
		return ['split text', 'split', 'amimation', 'custom'];
	}

	protected function register_controls()
	{

		// Content Tab Start

		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__('Title', 'stp'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__('Title', 'stp'),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__('Split Heading Widget by Momin Sarder', 'stp'),

				'label_block' => true,
				'dynamic' => [
					'active' => true
				]


			]
		);
		//tag changer control
		$this->add_control(
			'heading_tag',
			[
				'label' => esc_html__('Html Tag', 'stp'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p'
				],
			]
		);

		$this->add_control(
			'title_link',
			[
				'label' => esc_html__('Link', 'stp'),
				'type' => \Elementor\Controls_Manager::URL,
				'options' => ['url', 'is_external', 'nofollow'],
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
					// 'custom_attributes' => '',
				],
				'label_block' => true,
				'dynamic' => [
					'active' => true
				],
			]
		);

		$this->end_controls_section();

		// Content Tab End


		// Style Tab Start
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__('Title', 'stp'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__('Text Color', 'stp'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .reveal-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'ms_title_typography',
				'selector' => '{{WRAPPER}} .reveal-text',
			]
		);

		$this->end_controls_section();


		// Style Tab End

	}
	protected function render()
	{
		$settings = $this->get_settings_for_display();
		$title = $settings['title'];
		$is_external = $settings['title_link']['is_external'];
		$no_follow =  $settings['title_link']['nofollow'];
		$heading_tag = $settings['heading_tag'];
		$heading_link = $settings['title_link']['url'];
		$el_class = !empty($settings['el_class']) ? ' ' . esc_attr($settings['el_class']) : '';

		// Add inline editing attribute for the 'title' control
		$this->add_inline_editing_attributes('title', 'none');

		if ($heading_link) {
			echo '<a href="' . esc_url($heading_link) . '"' . 'target="' . ($is_external == true ? '_blank' : '') . '"' . 'rel="'.($no_follow == true ? 'nofollow' : '') .'"'.'>';
		}

		echo '<' . $heading_tag . ' class="reveal-text elementor-inline-editing' . $el_class . '" ' . $this->get_render_attribute_string('title') . '>';

		echo esc_html($title);
		echo '</' . esc_html($heading_tag) . '>';

		if ($heading_link) {
			echo '</a>';
		}
	}

	protected function content_template()
	{
?>
		<# view.addInlineEditingAttributes('title', 'none' ); #>

			<# if (settings.title_link) { #>
				<a href="{{ settings.title_link.url }}">
					<# } #>
						<{{ settings.heading_tag }} class="reveal-text {{ settings.el_class }} elementor-text-color-{{ settings.title_color }} elementor-element-{{ view.getRenderAttributeString('title')['data-id'] }}{{ view.getRenderAttributeString('title') }}">
							{{ settings.title }}
						</{{ settings.heading_tag }}>
						<# if (settings.title_link) { #>
				</a>
				<# } #>
			<?php
		}
	}

	/*
	protected function render() {
		$settings = $this->get_settings_for_display();
		$title = $settings['title'];
		$heading_tag = $settings['heading_tag'];
		$heading_link = $settings['title_link']['url'];
		$el_class = !empty($settings['el_class']) ? ' ' . esc_attr($settings['el_class']) : '';
	
		$this->add_inline_editing_attributes('title', 'none');
	
		if ($heading_link) {
			echo '<a href="' . esc_url($heading_link) . '">';
		}
	
		echo '<' . $heading_tag . ' class="reveal-text' . $el_class . '" data-elementor-setting-key="title">';
		echo esc_html($title);
		echo '</' . $heading_tag . '>';
	
		if ($heading_link) {
			echo '</a>';
		}
	}
	*/
