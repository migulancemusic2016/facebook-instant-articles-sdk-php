<?php
/**
 * Copyright (c) 2016-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */
namespace Facebook\InstantArticles\Elements;

use Facebook\InstantArticles\Validators\Type;

/**
 * An Interactive graphic.
 *
 * Example:
 * <figure class="op-interactive">
 *   <iframe class="no-margin" src="http://example.com/custom-interactive" height="60"></iframe>
 *   <figcaption>This graphic is awesome.</figcaption>
 * </figure>
 *
 * @see {link:https://developers.intern.facebook.com/docs/instant-articles/reference/interactive}
 */
class Interactive extends Element
{
    const NO_MARGIN = 'no-margin';
    const COLUMN_WIDTH = 'column-width';

    /**
     * @var Caption Descriptive text for your social embed.
     */
    private $caption;

    /**
     * @var int The height of your interactive graphic.
     */
    private $height;

    /**
     * @var string The source of the content for your interactive graphic.
     */
    private $source;

    /**
     * @var string The width setting for the interactive graphic.
     * @see Interactive::NO_MARGIN
     * @see Interactive::COLUMN_WIDTH
     */
    private $width;

    /**
     * @var \DOMNode The HTML of the content.
     */
    private $html;

    private function __construct()
    {
    }

    public static function create()
    {
        return new self();
    }

    /**
     * Sets the caption for the social embed.
     *
     * @param Caption $caption - Descriptive text for your social embed.
     */
    public function withCaption($caption)
    {
        Type::enforce($caption, Caption::getClassName());
        $this->caption = $caption;

        return $this;
    }

    /**
     * Sets the height of your interactive graphic.
     *
     * @param int The height of your interactive graphic.
     */
    public function withHeight($height)
    {
        Type::enforce($height, Type::INTEGER);
        $this->height = $height;

        return $this;
    }

    /**
     * Sets the source for the interactive graphic.
     *
     * @param string The source of the content for your interactive graphic.
     */
    public function withSource($source)
    {
        Type::enforce($source, Type::STRING);
        $this->source = $source;

        return $this;
    }

    /**
     * Sets the width setting of your interactive graphic.
     *
     * @param string The width setting of your interactive graphic.
     * @see Interactive::NO_MARGIN
     * @see Interactive::COLUMN_WIDTH
     */
    public function withWidth($width)
    {
        Type::enforceWithin(
            $width,
            [
                Interactive::NO_MARGIN,
                Interactive::COLUMN_WIDTH
            ]
        );
        $this->width = $width;

        return $this;
    }

    /**
     * Sets the unescaped HTML of your interactive graphic.
     *
     * @param \DOMNode The unescaped HTML of your interactive graphic.
     */
    public function withHTML($html)
    {
        Type::enforce($html, 'DOMNode');
        $this->html = $html;

        return $this;
    }

    /**
     * @return Caption the caption element
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @return int the height
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return string url source
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return int the width
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return \DOMNode unescaped html
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * Structure and create the full Interactive in a DOMElement.
     *
     * @param DOMDocument $document - The document where this element will be appended (optional).
     */
    public function toDOMElement($document = null)
    {
        if (!$document) {
            $document = new \DOMDocument();
        }
        $figure = $document->createElement('figure');
        $iframe = $document->createElement('iframe');

        $figure->appendChild($iframe);
        $figure->setAttribute('class', 'op-interactive');

        // Caption markup optional
        if ($this->caption) {
            $figure->appendChild($this->caption->toDOMElement($document));
        }

        if ($this->source) {
            $iframe->setAttribute('src', $this->source);
        }

        if ($this->width) {
            $iframe->setAttribute('class', $this->width);
        }

        if ($this->height) {
            $iframe->setAttribute('height', $this->height);
        }

        // Ad markup
        if ($this->html) {
            // Here we do not care about what is inside the iframe
            // because it'll be rendered in a sandboxed webview
            $this->dangerouslyAppendUnescapedHTML($iframe, $this->html);
        } else {
            $iframe->appendChild($document->createTextNode(''));
        }

        return $figure;
    }
}
