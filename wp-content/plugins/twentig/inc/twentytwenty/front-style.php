<?php

/**
 * Enqueue scripts and styles.
 */
function twentig_twentytwenty_theme_scripts() {

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_style(
		'twentig-twentytwenty',
		TWENTIG_ASSETS_URI . "/css/twentytwenty{$min}.css",
		array(),
		TWENTIG_VERSION
	);

	wp_style_add_data( 'twentig-twentytwenty', 'rtl', 'replace' );
	wp_style_add_data( 'twentig-twentytwenty', 'suffix', $min );

	twentig_twentytwenty_print_customizer_css();

	wp_enqueue_style( // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		'twentig-theme-fonts',
		twentig_fonts_url(),
		array(),
		null
	);

	wp_enqueue_style( // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		'twentig-theme-logo-font',
		twentig_logo_font_url(),
		array(),
		null
	);

	// Skip enqueueing JavaScript if this is an AMP response.
	if ( ! twentig_is_amp_endpoint() ) {
		wp_enqueue_script( 'twentig-twentytwenty', TWENTIG_ASSETS_URI . "/js/twentig-twentytwenty{$min}.js", array(), '1.0' );
	}
}
add_action( 'wp_enqueue_scripts', 'twentig_twentytwenty_theme_scripts', 12 );

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed.
 */
function twentig_twentytwenty_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'twentig-theme-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'twentig_twentytwenty_resource_hints', 10, 2 );

/**
 * Add custom classes generated by Customizer settings to the array of body classes.
 *
 * @param array $classes Classes added to the body tag.
 */
function twentig_twentytwenty_body_class( $classes ) {

	$header_layout     = get_theme_mod( 'twentig_header_layout' );
	$header_decoration = get_theme_mod( 'twentig_header_decoration' );
	$text_width        = get_theme_mod( 'twentig_text_width' );
	$h1_font_size      = get_theme_mod( 'twentig_h1_font_size' );
	$body_font_size    = get_theme_mod( 'twentig_body_font_size', twentig_get_default_body_font_size() );
	$body_line_height  = get_theme_mod( 'twentig_body_line_height' );
	$header_width      = get_theme_mod( 'twentig_header_width' );
	$menu_font_size    = get_theme_mod( 'twentig_menu_font_size' );
	$menu_spacing      = get_theme_mod( 'twentig_menu_spacing' );
	$menu_hover        = get_theme_mod( 'twentig_menu_hover', 'underline' );
	$footer_layout     = get_theme_mod( 'twentig_footer_layout' );
	$has_sidebar_1     = is_active_sidebar( 'sidebar-1' );
	$has_sidebar_2     = is_active_sidebar( 'sidebar-2' );
	$footer_width      = get_theme_mod( 'twentig_footer_width' );
	$footer_size       = get_theme_mod( 'twentig_footer_font_size' );
	$socials_style     = get_theme_mod( 'twentig_socials_style' );
	$separator_style   = get_theme_mod( 'twentig_separator_style' );
	$button_shape      = get_theme_mod( 'twentig_button_shape', 'square' );
	$button_hover      = get_theme_mod( 'twentig_button_hover' );

	if ( $header_layout ) {
		$classes[] = 'tw-header-layout-' . $header_layout;
	}

	if ( get_theme_mod( 'twentig_header_sticky' ) ) {
		$classes[] = 'tw-header-sticky';
	}

	if ( $header_decoration ) {
		$classes[] = 'tw-header-' . $header_decoration;
	}

	if ( $text_width ) {
		$classes[] = 'tw-text-width-' . $text_width;
	}

	if ( get_theme_mod( 'twentig_page_header_no_background', false ) ) {
		$classes[] = 'tw-entry-header-no-bg';
	}

	if ( get_theme_mod( 'twentig_body_font' ) || get_theme_mod( 'twentig_heading_font' ) ) {
		$classes[] = 'tw-font-active';
	}

	if ( $h1_font_size ) {
		$classes[] = 'tw-h1-font-' . $h1_font_size;
	}

	if ( $body_font_size ) {
		$classes[] = 'tw-site-font-' . $body_font_size;
	}

	if ( $body_line_height ) {
		$classes[] = 'tw-site-lh-' . $body_line_height;
	}

	if ( 'normal' === get_theme_mod( 'twentig_heading_letter_spacing' ) ) {
		$classes[] = 'tw-heading-ls-normal';
	}

	if ( is_page_template( 'tw-header-transparent-light.php' ) ) {
		$classes[] = 'overlay-header';
	}

	if ( twentig_is_amp_endpoint() && ( in_array( 'overlay-header', $classes, true ) || in_array( 'tw-header-transparent', $classes, true ) ) && in_array( 'tw-header-sticky', $classes, true ) ) {
		$classes[] = 'has-header-opaque';
	}

	if ( $header_width && 'wider' !== $header_width ) {
		$classes[] = 'tw-header-' . $header_width;
	}

	if ( $menu_font_size ) {
		$classes[] = 'tw-nav-size-' . $menu_font_size;
	}

	if ( $menu_spacing ) {
		$classes[] = 'tw-nav-spacing-' . $menu_spacing;
	}

	if ( 'underline' !== $menu_hover ) {
		$classes[] = 'tw-nav-hover-' . $menu_hover;
	}

	if ( get_theme_mod( 'twentig_burger_icon', false ) ) {
		$classes[] = 'tw-menu-burger';
	}

	if ( ! get_theme_mod( 'twentig_toggle_label', true ) ) {
		$classes[] = 'tw-toggle-label-hidden';
	}

	if ( has_nav_menu( 'social' ) ) {
		if ( twentig_twentytwenty_is_socials_location( 'primary-menu' ) ) {
			$classes[] = 'tw-menu-has-socials';
		}

		if ( ! twentig_twentytwenty_is_socials_location( 'modal-mobile' ) && ! twentig_twentytwenty_is_socials_location( 'modal-desktop' ) ) {
			$classes[] = 'modal-socials-hidden';
		} elseif ( ! twentig_twentytwenty_is_socials_location( 'modal-mobile' ) ) {
			$classes[] = 'modal-socials-mobile-hidden';
		} elseif ( ! twentig_twentytwenty_is_socials_location( 'modal-desktop' ) ) {
			$classes[] = 'modal-socials-desktop-hidden';
		}
	} else {
		$classes[] = 'modal-socials-hidden';
	}

	if ( $footer_layout ) {
		if ( ! $has_sidebar_1 && ! $has_sidebar_2 ) {
			$classes[] = 'footer-top-hidden';
			$classes   = array_diff( $classes, array( 'footer-top-visible' ) );
		}
	} else {
		if ( ! $has_sidebar_1 && ! $has_sidebar_2 && ( ! has_nav_menu( 'social' ) || ! twentig_twentytwenty_is_socials_location( 'footer' ) ) && ! has_nav_menu( 'footer' ) ) {
			$classes[] = 'footer-top-hidden';
			$classes   = array_diff( $classes, array( 'footer-top-visible' ) );
		}
	}

	if ( $footer_width ) {
		$classes[] = 'tw-footer-' . $footer_width;
	}

	if ( 'row' === get_theme_mod( 'twentig_footer_widget_layout' ) ) {
		$classes[] = 'tw-footer-widgets-row';
	}

	if ( $footer_size ) {
		$classes[] = 'tw-footer-size-' . $footer_size;
	}

	if ( $socials_style ) {
		$classes[] = 'tw-socials-' . $socials_style;
	}

	if ( $separator_style ) {
		$classes[] = 'tw-hr-' . $separator_style;
	}

	if ( 'square' !== $button_shape ) {
		$classes[] = 'tw-btn-' . $button_shape;
	}

	if ( $button_hover ) {
		$classes[] = 'tw-button-hover-' . $button_hover;
	}

	if ( is_home() || is_author() || is_category() || is_tag() || is_date() || is_tax( get_object_taxonomies( 'post' ) ) ) {

		$blog_layout = get_theme_mod( 'twentig_blog_layout' );

		if ( $blog_layout ) {
			$classes[] = 'tw-blog-' . $blog_layout;
		}

		if ( 'grid-basic' === $blog_layout || 'grid-card' === $blog_layout ) {
			$classes[] = 'tw-blog-grid';
			$classes[] = 'tw-blog-columns-' . get_theme_mod( 'twentig_blog_columns', '3' );
			add_filter(
				'post_thumbnail_size',
				function() {
					return 'large';
				}
			);
			if ( '' === get_the_posts_pagination() ) {
				$classes[] = 'tw-blog-no-pagination';
			}
		} elseif ( '' == $blog_layout && 'narrow-image' === get_theme_mod( 'twentig_post_hero_layout' ) ) {
			$classes[] = 'tw-hero-narrow-image';
		}
	} elseif ( is_search() ) {
		if ( 'stack' === get_theme_mod( 'twentig_page_search_layout' ) ) {
			$classes[] = 'tw-blog-stack';
		}
	} elseif ( is_page() ) {
		if ( is_page_template( 'templates/template-cover.php' ) ) {
			$cover_height = get_theme_mod( 'twentig_cover_page_height' );
			if ( $cover_height ) {
				$classes[] = 'tw-cover-' . $cover_height;
			} elseif ( ! get_theme_mod( 'twentig_cover_page_scroll_indicator', true ) ) {
				$classes[] = 'tw-cover-hide-arrow';
			}
			if ( 'center' === get_theme_mod( 'twentig_cover_vertical_align' ) ) {
				$classes[] = 'tw-cover-center';
			}
		}

		if ( is_page_template( 'tw-no-title.php' ) || is_page_template( 'tw-no-header-footer.php' ) ) {
			$classes = array_diff( $classes, array( 'has-post-thumbnail', 'missing-post-thumbnail' ) );
		}

		$hero_type = get_theme_mod( 'twentig_page_hero_layout' );
		if ( $hero_type && has_post_thumbnail() && ( ! is_page_template() || is_page_template( 'templates/template-full-width.php' ) ) ) {
			$classes[] = 'tw-hero-' . $hero_type;
		}
	} elseif ( is_singular( 'post' ) ) {

		if ( is_page_template( 'templates/template-cover.php' ) ) {
			$cover_height = get_theme_mod( 'twentig_cover_post_height' );
			if ( $cover_height ) {
				$classes[] = 'tw-cover-' . $cover_height;
			}
			if ( 'center' === get_theme_mod( 'twentig_cover_vertical_align' ) ) {
				$classes[] = 'tw-cover-center';
			}
		} else {
			$hero_type = get_theme_mod( 'twentig_post_hero_layout' );
			if ( $hero_type && has_post_thumbnail() && ( ! is_page_template() || is_page_template( 'templates/template-full-width.php' ) ) ) {
				$classes[] = 'tw-hero-' . $hero_type;
			}
		}

		if ( has_excerpt() && ! get_theme_mod( 'twentig_post_excerpt', true ) ) {
			$classes[] = 'tw-no-excerpt';
		}

		if ( 'image' === get_theme_mod( 'twentig_post_navigation' ) ) {
			$classes[] = 'tw-nav-image';
		}
	}

	return $classes;
}
add_filter( 'body_class', 'twentig_twentytwenty_body_class', 11 );

/**
 * Display custom CSS generated by the Customizer settings.
 */
function twentig_twentytwenty_print_customizer_css() {
	$css = '';

	$body_font           = get_theme_mod( 'twentig_body_font' );
	$heading_font        = get_theme_mod( 'twentig_heading_font' );
	$heading_font_weight = get_theme_mod( 'twentig_heading_font_weight', '700' );
	$secondary_font      = get_theme_mod( 'twentig_secondary_font', 'heading' );
	$menu_font           = get_theme_mod( 'twentig_menu_font', 'heading' );
	$body_font_stack     = twentig_get_font_stack( 'body' );
	$heading_font_stack  = twentig_get_font_stack( 'heading' );

	if ( $body_font || $heading_font ) {
		$css .= '
			body,
			.entry-content,
			.entry-content p,
			.entry-content ol,
			.entry-content ul,
			.widget_text p,
			.widget_text ol,
			.widget_text ul,
			.widget-content .rssSummary,
			.comment-content p,			
			.entry-content .wp-block-latest-posts__post-excerpt,
			.entry-content .wp-block-latest-posts__post-full-content,
			.has-drop-cap:not(:focus):first-letter { font-family: ' . $body_font_stack . '; }';

		$css .= 'h1, h2, h3, h4, h5, h6, .entry-content h1, .entry-content h2, .entry-content h3, .entry-content h4, .entry-content h5, .entry-content h6, .faux-heading, .site-title, .pagination-single a, .entry-content .wp-block-latest-posts li > a { font-family: ' . $heading_font_stack . '; }';

		if ( 'heading' === $menu_font ) {
			$css .= 'ul.primary-menu, ul.modal-menu { font-family: ' . $heading_font_stack . '; }';
		}

		if ( 'heading' === $secondary_font ) {
			$css .= '
				.intro-text,
				input,
				textarea,
				select,
				button, 
				.button, 
				.faux-button, 
				.wp-block-button__link,
				.wp-block-file__button,
				.entry-content .wp-block-file,	
				.primary-menu li.menu-button > a,
				.entry-content .wp-block-pullquote,
				.entry-content .wp-block-quote.is-style-large,
				.entry-content .wp-block-quote.is-style-tw-large-icon,
				.entry-content cite,
				.entry-content figcaption,
				.wp-caption-text,
				.entry-content .wp-caption-text,
				.widget-content cite,
				.widget-content figcaption,
				.widget-content .wp-caption-text,
				.entry-categories,
				.post-meta,
				.comment-meta, 
				.comment-footer-meta,
				.author-bio,
				.comment-respond p.comment-notes, 
				.comment-respond p.logged-in-as,
				.entry-content .wp-block-archives,
				.entry-content .wp-block-categories,
				.entry-content .wp-block-latest-posts,
				.entry-content .wp-block-latest-comments,
				p.comment-awaiting-moderation,
				.pagination,
				#site-footer,							
				.widget:not(.widget-text),
				.footer-menu,
				label,
				.toggle .toggle-text {
					font-family: ' . $heading_font_stack . ';
				}';
		} else {
			$css .= '
			input,
			textarea,			
			select,
			button, 
			.button, 
			.faux-button, 
			.wp-block-button__link,
			.wp-block-file__button,	
			.primary-menu li.menu-button > a,	
			.entry-content .wp-block-pullquote,
			.entry-content .wp-block-quote.is-style-large,
			.entry-content cite,
			.entry-content figcaption,
			.wp-caption-text,
			.entry-content .wp-caption-text,
			.widget-content cite,
			.widget-content figcaption,
			.widget-content .wp-caption-text,
			.entry-content .wp-block-archives,
			.entry-content .wp-block-categories,
			.entry-content .wp-block-latest-posts,
			.entry-content .wp-block-latest-comments,
			p.comment-awaiting-moderation {
				font-family: ' . $body_font_stack . ';
			}';
		}

		$css .= 'table {font-size: inherit;} ';
	}

	if ( 'body' === $menu_font ) {
		$css .= 'ul.primary-menu, ul.modal-menu { font-family: ' . $body_font_stack . '; }';
	}

	if ( $heading_font_weight && '700' !== $heading_font_weight ) {
		$css .= 'h1, .heading-size-1, h2, h3, h4, h5, h6, .faux-heading, .archive-title, .site-title, .pagination-single a, .entry-content .wp-block-latest-posts li > a { font-weight: ' . $heading_font_weight . ';}';
	} elseif ( $heading_font ) {
		$css .= 'h1, .heading-size-1 { font-weight: ' . $heading_font_weight . ';}';
	}

	/* Site title */
	if ( ! has_custom_logo() ) {
		$css_logo = '';

		$logo_font             = get_theme_mod( 'twentig_logo_font' );
		$logo_font_weight      = get_theme_mod( 'twentig_logo_font_weight', '700' );
		$logo_font_size        = get_theme_mod( 'twentig_logo_font_size', false );
		$logo_letter_spacing   = get_theme_mod( 'twentig_logo_letter_spacing', false );
		$logo_transform        = get_theme_mod( 'twentig_logo_text_transform' );
		$logo_mobile_font_size = get_theme_mod( 'twentig_logo_mobile_font_size' );

		if ( $heading_font || $logo_font ) {
			$css .= '#site-header .site-title a { text-decoration: none; }';
		}

		if ( $logo_font ) {
			$css_logo .= 'font-family: ' . twentig_get_font_stack( 'logo' ) . ' ;';
		}

		if ( $logo_font_weight ) {
			$css_logo .= 'font-weight: ' . $logo_font_weight . ';';
		}

		if ( $logo_font_size ) {
			$css_logo .= 'font-size:' . $logo_font_size . 'px;';
		}

		if ( $logo_letter_spacing ) {
			$css_logo .= 'letter-spacing:' . $logo_letter_spacing . 'em;';
		}

		if ( $logo_transform ) {
			$css_logo .= 'text-transform: ' . esc_attr( $logo_transform ) . ';';
		}

		if ( $css_logo ) {
			$css .= '#site-header .site-title { ' . $css_logo . '}';
		}

		if ( $logo_mobile_font_size ) {
			$css .= '@media(max-width:699px) { #site-header .site-title { font-size:' . intval( $logo_mobile_font_size ) . 'px; } }';
		}
	} else {
		$logo_responsive_width = get_theme_mod( 'twentig_logo_mobile_width' );
		if ( $logo_responsive_width ) {
			$css .= '@media(max-width:699px) { .site-logo .custom-logo-link img { width:' . intval( $logo_responsive_width ) . 'px; height:auto !important; max-height: none; } }';
		}
	}

	/* Menu */

	$menu_font_weight = get_theme_mod( 'twentig_menu_font_weight', 500 );
	$menu_transform   = get_theme_mod( 'twentig_menu_text_transform' );
	$menu_accent      = sanitize_hex_color( twentytwenty_get_color_for_area( 'header-footer', 'accent' ) );
	$menu_secondary   = sanitize_hex_color( twentytwenty_get_color_for_area( 'header-footer', 'secondary' ) );
	$menu_color       = get_theme_mod( 'twentig_menu_color', 'accent' );
	$menu_hover       = get_theme_mod( 'twentig_menu_hover', 'underline' );
	$header_sticky    = get_theme_mod( 'twentig_header_sticky' );
	$hex              = get_theme_mod( 'twentig_accent_hex_color' );

	if ( $menu_font_weight ) {
		$css .= 'ul.primary-menu, ul.modal-menu > li .ancestor-wrapper a { font-weight:' . esc_attr( $menu_font_weight ) . ';}';
	}

	if ( $menu_transform ) {
		$css .= 'ul.primary-menu li a, ul.modal-menu li .ancestor-wrapper a { text-transform:' . esc_attr( $menu_transform ) . ';';
		if ( 'uppercase' === $menu_transform ) {
			$css .= 'letter-spacing: 0.0333em;';
		}
		$css .= '}';
	}

	if ( ! get_theme_mod( 'twentig_button_uppercase', true ) ) {
		$css .= 'button, .button, .faux-button, .wp-block-button__link, .wp-block-file__button, input[type="button"], input[type="submit"] { text-transform: none; letter-spacing: normal; }';
	}

	if ( is_customize_preview() && 'hex' === get_theme_mod( 'accent_hue_active' ) && $hex ) {
		$css .= '.color-accent, :root .has-accent-color, .header-footer-group .color-accent, .has-drop-cap:not(:focus):first-letter, .wp-block-button.is-style-outline, a, .modal-menu a, .footer-menu a, .footer-widgets a, #site-footer .wp-block-button.is-style-outline, .wp-block-pullquote:before, .singular:not(.overlay-header) .entry-header a, .archive-header a {
			color:' . $hex . '}';

		if ( 'accent' === $menu_color ) {
			$css .= 'body:not(.overlay-header) .primary-menu > li > a, body:not(.overlay-header) .primary-menu > li > .icon { color:' . $hex . '}';
		}

		$css .= 'blockquote{ border-color:' . $hex . '}';
		$css .= 'button:not(.toggle), .wp-block-button__link, .wp-block-file .wp-block-file__button, input[type="button"], input[type="submit"], .faux-button, .bg-accent, :root .has-accent-background-color, .comment-reply-link, .social-icons a, #site-footer .button, #site-footer .faux-button, #site-footer .wp-block-button__link, #site-footer input[type="button"], #site-footer input[type="submit"], #site-header ul.primary-menu li.menu-button > a, .menu-modal ul.modal-menu > li.menu-button > .ancestor-wrapper > a { background-color:' . $hex . '}';
	}

	if ( $header_sticky ) {
		if ( 'secondary' === $menu_color ) {
			$css .= 'body.has-header-opaque .primary-menu > li:not(.menu-button) > a, body.has-header-opaque .primary-menu > li > .icon { color: ' . $menu_secondary . '; }';
		} elseif ( 'accent' === $menu_color ) {
			$css .= 'body.has-header-opaque .primary-menu > li:not(.menu-button) > a, body.has-header-opaque .primary-menu > li > .icon { color: ' . $menu_accent . '; }';
		}
	}

	if ( 'text' === $menu_color ) {
		$css .= 'body:not(.overlay-header) .primary-menu > li > a, body:not(.overlay-header) .primary-menu > li > .icon, .modal-menu > li > .ancestor-wrapper > a { color: inherit; }';
	} elseif ( 'secondary' === $menu_color ) {
		$menu_secondary = sanitize_hex_color( twentytwenty_get_color_for_area( 'header-footer', 'secondary' ) );
		$css           .= 'body:not(.overlay-header) .primary-menu > li > a, body:not(.overlay-header) .primary-menu > li > .icon, .modal-menu > li > .ancestor-wrapper > a { color: ' . $menu_secondary . '; }';
	}

	if ( 'color' === $menu_hover ) {
		$menu_hover_color = 'inherit';
		if ( 'text' === $menu_color ) {
			$menu_hover_color = $menu_accent;
		}
		$css .= 'body:not(.overlay-header) .primary-menu > li > a:hover, body:not(.overlay-header) .primary-menu > li > a:hover + .icon, 
		body:not(.overlay-header) .primary-menu > li.current-menu-item > a, body:not(.overlay-header) .primary-menu > li.current-menu-item > .icon, 
		body:not(.overlay-header) .primary-menu > li.current_page_ancestor > a, body:not(.overlay-header) .primary-menu > li.current_page_ancestor > .icon,
		body:not(.overlay-header) .primary-menu > li.current-page-ancestor > a, body:not(.overlay-header) .primary-menu > li.current-page-ancestor > .icon,
		.single-post:not(.overlay-header) .primary-menu li.current_page_parent > a, .single-post .modal-menu li.current_page_parent > .ancestor-wrapper > a,
		.modal-menu > li > .ancestor-wrapper > a:hover, .modal-menu > li > .ancestor-wrapper > a:hover + .toggle,
		.modal-menu > li.current-menu-item > .ancestor-wrapper > a, .modal-menu > li.current-menu-item > .ancestor-wrapper > .toggle,
		.modal-menu > li.current_page_ancestor > .ancestor-wrapper > a, .modal-menu > li.current_page_ancestor > .ancestor-wrapper > .toggle,
		.modal-menu > li.current-page-ancestor > .ancestor-wrapper > a, .modal-menu > li.current-page-ancestor > .ancestor-wrapper > .toggle { 
			color: ' . $menu_hover_color . ';}';
		if ( $header_sticky ) {
			$css .= 'body.has-header-opaque .primary-menu > li > a:hover, body.has-header-opaque .primary-menu > li > a:hover + .icon, 
			body.has-header-opaque .primary-menu > li.current-menu-item > a, body.has-header-opaque .primary-menu > li.current-menu-item > .icon,
			body.has-header-opaque .primary-menu li.current_page_ancestor > a, body.has-header-opaque .primary-menu li.current_page_ancestor > .icon,
			body.has-header-opaque .primary-menu li.current-page-ancestor > a, body.has-header-opaque .primary-menu li.current-page-ancestor > .icon,
			.single-post.has-header-opaque .primary-menu li.current_page_parent > a { color: ' . $menu_hover_color . '; }';
		}
	}

	/* Footer */

	$footer_bgcolor = get_theme_mod( 'twentig_footer_background_color' );
	$footer_layout  = get_theme_mod( 'twentig_footer_layout' );

	if ( $footer_bgcolor ) {

		$css .= twentig_get_footer_colors_css();

		$background_color = get_theme_mod( 'background_color', 'f5efe0' );
		$background_color = strtolower( '#' . ltrim( $background_color, '#' ) );

		if ( $background_color !== $footer_bgcolor ) {
			$css .= '.reduced-spacing.footer-top-visible .footer-nav-widgets-wrapper, .reduced-spacing.footer-top-hidden #site-footer{ border: 0; }';
		} else {
			$css .= '.footer-top-visible .footer-nav-widgets-wrapper, .footer-top-hidden #site-footer { border-top-width: 0.1rem; }';
		}
	} else {
		$footer_link_color = get_theme_mod( 'twentig_footer_link_color' );
		if ( 'text' === $footer_link_color || 'secondary' === $footer_link_color ) {
			$footer_link_color_value = sanitize_hex_color( twentytwenty_get_color_for_area( 'header-footer', $footer_link_color ) );
			$css                    .= '.footer-widgets a, .footer-menu a{ color:' . $footer_link_color_value . ';}';
		}
	}

	if ( 'hidden' === $footer_layout || 'custom' === $footer_layout ) {
		$css .= '.footer-widgets-outer-wrapper { border-bottom: 0; }';
	}

	/* Subtle Color */

	$subtle_background = get_theme_mod( 'twentig_subtle_background_color' );
	if ( $subtle_background ) {
		$css .= ':root .has-subtle-background-background-color{ background-color: ' . $subtle_background . '; }';
		$css .= ':root .has-subtle-background-color.has-text-color { color: ' . $subtle_background . '; }';
	}

	if ( '#ffffff' === strtolower( twentytwenty_get_color_for_area( 'content', 'text' ) ) ) {
		$css .= '.wp-block-button:not(.is-style-outline) .wp-block-button__link:not(.has-text-color) { color: #000; }';
	}

	$css = apply_filters( 'twentig_customizer_css', $css );

	if ( $css ) :
		wp_add_inline_style( 'twentig-twentytwenty', twentig_minify_css( $css ) );
	endif;
}
