<?php

namespace Theme\Embed;

/**
 * Class Youtube
 *
 * Optimizes Youtube embeds.
 */
class Youtube {
	/**
	 * Init hooks.
	 */
	public function init() {
		add_filter( 'oembed_dataparse', [ $this, 'filter_oembed_result' ], 10, 3 );
	}

	/**
	 * Optimize HTML for playlists.
	 *
	 * @param string $result The existing oEmbed HTML.
	 * @param object $data   A data object result from an oEmbed provider.
	 * @param string $url    The URL of the content to be embedded.
	 *
	 * @return string
	 */
	public function filter_oembed_result( $result, $data, $url ) {
		if ( 'YouTube' !== $data->provider_name ) {
			return $result;
		}

		$video = $this->get_youtube_id( $url );

		if ( ! $video ) {
			return $result;
		}

		if ( 'video' === $video['type'] ) {
			return $result;

		} elseif ( 'playlist' === $video['type'] ) {
			/**
			 * Use custom playlist markup.
			 *
			 * @see https://developers.google.com/youtube/youtube_player_demo
			 */
			return '<iframe width="' . $data->width . '" height="' . $data->height . '" type="text/html" src="https://www.youtube.com/embed/?listType=playlist&list=' . $video['id'] . '&modestbranding=1&playsinline=1&color=white" frameborder="0" allowfullscreen></iframe>';
		}

		return $result;
	}

	/**
	 * Get the video ID from a YouTube video.
	 *
	 * @link https://stackoverflow.com/a/17030234/1059980
	 *
	 * @param string $url A YouTube URL.
	 *
	 * @return bool|array
	 */
	public function get_youtube_id( $url ) {
		preg_match( "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches );

		if ( ! empty( $matches[1] ) ) {
			return [
				'type' => 'video',
				'id'   => $matches[1],
			];
		}

		// Try to match a playlist
		$result = wp_parse_url( $url );
		parse_str( $result['query'], $result );

		if ( empty( $result['list'] ) ) {
			return false;
		}

		return [
			'type' => 'playlist',
			'id'   => $result['list'],
		];
	}
}

new Youtube();
