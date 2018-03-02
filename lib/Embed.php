<?php

namespace Theme\Embed;

/**
 * Class Embed
 */
class Embed {
	/**
	 * Default oEmbed widht.
	 *
	 * @var int Width in pixels.
	 */
	public $width;

	/**
	 * Default oEmbed height.
	 *
	 * @var int Height in pixels.
	 */
	public $height;

	/**
	 * Embed constructor.
	 *
	 * @param int   $width The default width for oEmbed content in pixels. Default 1400.
	 * @param float $ratio Ratio to calculate height. Default 16/9.
	 */
	public function __construct( $width = 1400, $ratio = 1.777777778 ) {
		$this->width = $width;

		/**
		 * Use logic used by wp_embed_defaults() function.
		 *
		 * @see wp_embed_defaults()
		 */
		$this->height = (int) min( ceil( $width * $ratio ), 1000 );
	}

	/**
	 * Init hooks.
	 */
	public function init() {
		add_filter( 'embed_defaults', [ $this, 'set_embed_defaults' ] );
		add_filter( 'embed_oembed_html', [ $this, 'filter_oembed_html' ], 99, 4 );

		$youtube = new Youtube();
		$youtube->init();
	}

	/**
	 * Set default array of embed parameters.
	 *
	 * @see wp_embed_defaults()
	 *
	 * @return array Array with a width and height parameter.
	 */
	public function set_embed_defaults() {
		return [
			'width'  => $this->width,
			'height' => $this->height,
		];
	}

	/**
	 * Wrap HTML with markup for responsiveness.
	 *
	 * @param mixed  $html    The cached HTML result, stored in post meta.
	 * @param string $url     The attempted embed URL.
	 * @param array  $attr    An array of shortcode attributes.
	 * @param int    $post_id Post ID.
	 *
	 * @return string
	 */
	public function filter_oembed_html( $html, $url, $attr, $post_id ) {
		// Remove width and height tags from element
		$html = preg_replace( '/(width|height)="\d*"\s?/', '', $html );

		return '<div class="responsive-embed"><div class="keep-aspect-ratio">' . $html . '</div></div>';
	}
}
