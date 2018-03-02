# Theme\Embed

Optimizes embeds in WordPress themes.

- Allows you to set default sizes for oEmbed contents.
- Wraps oEmbed content with div tags to easier style them for responsiveness. You need to add the CSS yourself, though.
- Optimizes Youtube playlist embeds where the preview image might lack in quality.

## Installation

You can install the package via Composer:

```bash
composer require mindkomm/theme-lib-embed
```

## Usage

**functions.php**

```php
$embeds = new Theme\Embed\Embed();
$embeds->init();
```

The Embed constructor takes two parameters:

- **$width**  
	*(int)* The default width for oEmbed content in pixels. Default 1400.
- **$ratio**  
	*(float)* The ratio to use to calculate the height. Default 16/9.

Here’s an example if you wanted your oEmbed contents to have a width of 800px and a height of 400px. 

```php
$embeds = new Theme\Embed\Embed( 800, 0.5 );
$embeds->init();
```

If you already have an `embed_oembed_html` filter in place that adds divs with classes `responsive-embed` and `keep-aspect-ratio`, you could probably remove it, because this library already adds one, too.

## Support

This is a package that we use at MIND to develop WordPress themes. You’re free to use it, but currently, we don’t provide any support.
