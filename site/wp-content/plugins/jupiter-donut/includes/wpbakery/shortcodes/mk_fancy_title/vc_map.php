<?php
vc_map(
	array(
		'name' => __( 'Fancy Title', 'jupiter-donut' ),
		'base' => 'mk_fancy_title',
		'html_template' => dirname( __FILE__ ) . '/mk_fancy_title.php',
		'icon' => 'icon-mk-fancy-title vc_mk_element-icon',
		'category' => __( 'Typography', 'jupiter-donut' ),
		'description' => __( 'Advanced headings with cool styles.', 'jupiter-donut' ),
		'params' => array(
			array(
				'type' => 'textarea_html',
				'holder' => 'div',
				'heading' => __( 'Content.', 'jupiter-donut' ),
				'param_name' => 'content',
				'value' => __( '', 'jupiter-donut' ),
				'description' => __( '', 'jupiter-donut' ),
			),
			array(
				'type' => 'toggle',
				'heading' => __( 'Strip Tags?', 'jupiter-donut' ),
				'param_name' => 'strip_tags',
				'value' => 'false',
				'description' => __( 'If enabled, all tags included in editor above (including br and p tags) will be stripped out, however shortcodes inserted will be executed.', 'jupiter-donut' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Tag Name', 'jupiter-donut' ),
				'param_name' => 'tag_name',
				'value' => array(
					'h2' => 'h2',
					'h3' => 'h3',
					'h4' => 'h4',
					'h5' => 'h5',
					'h6' => 'h6',
					'h1' => 'h1',
					'span' => 'span',
				),
				'description' => __( 'For SEO reasons you might need to define your titles tag names according to priority. Please note that H1 can only be used only once in a page due to the SEO reasons. So try to use lower than H2 to meet SEO best practices.', 'jupiter-donut' ),
			),
			array(
				'type' => 'toggle',
				'heading' => __( 'Pattern?', 'jupiter-donut' ),
				'param_name' => 'style',
				'value' => 'false',
				'description' => __( '', 'jupiter-donut' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Text Color Type', 'jupiter-donut' ),
				'param_name' => 'color_style',
				'default' => 'single_color',
				'value' => array(
					__( 'Single Color', 'jupiter-donut' ) => 'single_color',
					__( 'Gradient Color', 'jupiter-donut' ) => 'gradient_color',
				),
				'description' => __( 'Gradients work properly only in Webkit browsers.', 'jupiter-donut' ),
			),
			array(
				'type' => 'alpha_colorpicker',
				'heading' => __( 'Text Color', 'jupiter-donut' ),
				'param_name' => 'color',
				'value' => '',
				'description' => __( '', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'color_style',
					'value' => array(
						'single_color',
					),
				),
			),
			array(
				'type' => 'alpha_colorpicker',
				'heading' => __( 'From', 'jupiter-donut' ),
				'param_name' => 'grandient_color_from',
				'edit_field_class' => 'vc_col-sm-3 vc_column',
				'value' => '',
				'description' => __( '', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'color_style',
					'value' => array(
						'gradient_color',
					),
				),
			),
			array(
				'type' => 'alpha_colorpicker',
				'heading' => __( 'To', 'jupiter-donut' ),
				'param_name' => 'grandient_color_to',
				'edit_field_class' => 'vc_col-sm-3 vc_column',
				'value' => '',
				'description' => __( '', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'color_style',
					'value' => array(
						'gradient_color',
					),
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Style', 'jupiter-donut' ),
				'param_name' => 'grandient_color_style',
				'edit_field_class' => 'vc_col-sm-3 vc_column',
				'value' => array(
					__( 'Linear', 'jupiter-donut' ) => 'linear',
					__( 'Radial', 'jupiter-donut' ) => 'radial',
				),
				'description' => __( '', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'color_style',
					'value' => array(
						'gradient_color',
					),
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Angle', 'jupiter-donut' ),
				'param_name' => 'grandient_color_angle',
				'edit_field_class' => 'vc_col-sm-3 vc_column',
				'value' => array(
					__( 'Vertical ↓', 'jupiter-donut' ) => 'vertical',
					__( 'Horizontal →', 'jupiter-donut' ) => 'horizontal',
					__( 'Diagonal ↘', 'jupiter-donut' ) => 'diagonal_left_bottom',
					__( 'Diagonal ↗', 'jupiter-donut' ) => 'diagonal_left_top',
				),
				'description' => __( '', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'grandient_color_style',
					'value' => array(
						'linear',
					),
				),
			),
			array(
				'type' => 'alpha_colorpicker',
				'heading' => __( 'Gradient Fallback Color', 'jupiter-donut' ),
				'param_name' => 'grandient_color_fallback',
				// "edit_field_class" => "vc_col-sm-3",
				'value' => '',
				'description' => __( '', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'color_style',
					'value' => array(
						'gradient_color',
					),
				),
			),
			array(
				'type' => 'range',
				'heading' => __( 'Font Size', 'jupiter-donut' ),
				'param_name' => 'size',
				'value' => '14',
				'min' => '12',
				'max' => '70',
				'step' => '1',
				'unit' => 'px',
				'description' => __( '', 'jupiter-donut' ),
			),
			array(
				'type' => 'toggle',
				'heading' => __( 'Force Responsive Font Size?', 'jupiter-donut' ),
				'param_name' => 'force_font_size',
				'value' => 'false',
				'description' => __( '', 'jupiter-donut' ),
			),
			array(
				'type' => 'range',
				'heading' => __( 'Font Size for Small Desktops', 'jupiter-donut' ),
				'param_name' => 'size_smallscreen',
				'value' => '0',
				'min' => '0',
				'max' => '70',
				'step' => '1',
				'unit' => 'px',
				'description' => __( 'For screens smaller than 1280px. If value is zero the font size not going to be affected.', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'force_font_size',
					'value' => array(
						'true',
					),
				),
			),
			array(
				'type' => 'range',
				'heading' => __( 'Font Size for Tablet', 'jupiter-donut' ),
				'param_name' => 'size_tablet',
				'value' => '0',
				'min' => '0',
				'max' => '70',
				'step' => '1',
				'unit' => 'px',
				'description' => __( 'For screens between 768 and 1024px. If value is zero the font size not going to be affected.', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'force_font_size',
					'value' => array(
						'true',
					),
				),
			),
			array(
				'type' => 'range',
				'heading' => __( 'Font Size for Mobile', 'jupiter-donut' ),
				'param_name' => 'size_phone',
				'value' => '0',
				'min' => '0',
				'max' => '70',
				'step' => '1',
				'unit' => 'px',
				'description' => __( 'For screens smaller than 768px. If value is zero the font size not going to be affected.', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'force_font_size',
					'value' => array(
						'true',
					),
				),
			),
			array(
				'type' => 'range',
				'heading' => __( 'Line Height', 'jupiter-donut' ),
				'param_name' => 'line_height',
				'value' => '100',
				'min' => '50',
				'max' => '500',
				'step' => '1',
				'unit' => '%',
				'description' => __( 'If hundred value is chosen, the default value set from theme options will be used. Use this option if you wish to override the line-height for this module by setting your own value.', 'jupiter-donut' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Font Weight', 'jupiter-donut' ),
				'param_name' => 'font_weight',
				'value' => $font_weight,
				'description' => __( '', 'jupiter-donut' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Font Style', 'jupiter-donut' ),
				'param_name' => 'font_style',
				'value' => array(
					__( 'Default', 'jupiter-donut' ) => 'inherit',
					__( 'Normal', 'jupiter-donut' ) => 'normal',
					__( 'Italic', 'jupiter-donut' ) => 'italic',
				),
				'description' => __( '', 'jupiter-donut' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Text Transform', 'jupiter-donut' ),
				'param_name' => 'txt_transform',
				'value' => array(
					__( 'Default', 'jupiter-donut' ) => 'initial',
					__( 'None', 'jupiter-donut' ) => 'none',
					__( 'Uppercase', 'jupiter-donut' ) => 'uppercase',
					__( 'Lowercase', 'jupiter-donut' ) => 'lowercase',
					__( 'Capitalize', 'jupiter-donut' ) => 'capitalize',
				),
				'description' => __( '', 'jupiter-donut' ),
			),
			array(
				'type' => 'range',
				'heading' => __( 'Letter Spacing', 'jupiter-donut' ),
				'param_name' => 'letter_spacing',
				'value' => '0',
				'min' => '0',
				'max' => '10',
				'step' => '1',
				'unit' => 'px',
				'description' => __( 'Space between each character.', 'jupiter-donut' ),
			),
			array(
				'type' => 'range',
				'heading' => __( 'Padding Top', 'jupiter-donut' ),
				'param_name' => 'margin_top',
				'value' => '0',
				'min' => '0',
				'max' => '500',
				'step' => '1',
				'unit' => 'px',
				'description' => __( '', 'jupiter-donut' ),
			),
			array(
				'type' => 'range',
				'heading' => __( 'Padding Bottom', 'jupiter-donut' ),
				'param_name' => 'margin_bottom',
				'value' => '20',
				'min' => '0',
				'max' => '500',
				'step' => '1',
				'unit' => 'px',
				'description' => __( '', 'jupiter-donut' ),
			),
			array(
				'type' => 'theme_fonts',
				'heading' => __( 'Font Family', 'jupiter-donut' ),
				'param_name' => 'font_family',
				'value' => '',
				'description' => __( 'You can choose a font for this shortcode, however using non-safe fonts can affect page load and performance.', 'jupiter-donut' ),
			),
			array(
				'type' => 'hidden_input',
				'param_name' => 'font_type',
				'value' => '',
				'description' => __( '', 'jupiter-donut' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Align', 'jupiter-donut' ),
				'param_name' => 'align',
				'width' => 150,
				'value' => array(
					__( 'Left', 'jupiter-donut' ) => 'left',
					__( 'Right', 'jupiter-donut' ) => 'right',
					__( 'Center', 'jupiter-donut' ) => 'center',
				),
				'description' => __( '', 'jupiter-donut' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Responsive Align', 'jupiter-donut' ),
				'param_name' => 'responsive_align',
				'default' => 'center',
				'width' => 150,
				'value' => array(
					__( 'Center', 'jupiter-donut' ) => 'center',
					__( 'Left', 'jupiter-donut' ) => 'left',
					__( 'Right', 'jupiter-donut' ) => 'right',
				),
				'description' => __( 'You can choose the align of this shortcode when it reaches to tablet/mobile sizes.', 'jupiter-donut' ),
			),
			$add_css_animations,
			$add_device_visibility,
			array(
				'type' => 'textfield',
				'heading' => __( 'Extra class name', 'jupiter-donut' ),
				'param_name' => 'el_class',
				'value' => '',
				'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in Custom CSS Shortcode or Masterkey Custom CSS option.', 'jupiter-donut' ),
			),
			array(
				'type' => 'toggle',
				'heading' => __( 'Drop Shadow', 'jupiter-donut' ),
				'param_name' => 'drop_shadow',
				'value' => 'false',
				'description' => __( '', 'jupiter-donut' ),
				'group' => 'Styles &amp; Colors',
			),
			array(
				'type' => 'range',
				'heading' => __( 'Angle', 'jupiter-donut' ),
				'param_name' => 'shadow_angle',
				'value' => '45',
				'min' => '0',
				'max' => '360',
				'step' => '1',
				'unit' => '&deg;',
				'description' => __( '', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'drop_shadow',
					'value' => array(
						'true',
					),
				),
				'group' => 'Styles &amp; Colors',
			),
			array(
				'type' => 'range',
				'heading' => __( 'Distance', 'jupiter-donut' ),
				'param_name' => 'shadow_distance',
				'value' => '8',
				'min' => '1',
				'max' => '100',
				'step' => '1',
				'unit' => 'px',
				'description' => __( '', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'drop_shadow',
					'value' => array(
						'true',
					),
				),
				'group' => 'Styles &amp; Colors',
			),
			array(
				'type' => 'range',
				'heading' => __( 'Blur', 'jupiter-donut' ),
				'param_name' => 'shadow_blur',
				'value' => '20',
				'min' => '0',
				'max' => '100',
				'step' => '1',
				'unit' => 'px',
				'description' => __( '', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'drop_shadow',
					'value' => array(
						'true',
					),
				),
				'group' => 'Styles &amp; Colors',
			),
			array(
				'type' => 'alpha_colorpicker',
				'heading' => __( 'Color', 'jupiter-donut' ),
				'param_name' => 'shadow_color',
				'edit_field_class' => 'vc_col-sm-3 vc_column',
				'value' => 'rgba(0,0,0,0.5)',
				'description' => __( '', 'jupiter-donut' ),
				'dependency' => array(
					'element' => 'drop_shadow',
					'value' => array(
						'true',
					),
				),
				'group' => 'Styles &amp; Colors',
			),
		),
	)
);