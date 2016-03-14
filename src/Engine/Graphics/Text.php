<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\GameOfLife\Engine\Graphics;

/**
 * Class Text
 *
 * Used to display text
 *
 * @author Nate Brunette <n@tebru.net>
 */
class Text implements Drawable
{
    /**
     * The text to display
     *
     * @var string
     */
    private $text = '';

    /**
     * How to format the text
     *
     * @var string
     */
    private $format = '%s';

    /**
     * X coordinate
     *
     * @var int
     */
    private $x = 0;

    /**
     * Y coordinate
     *
     * @var int
     */
    private $y = 0;

    /**
     * Text width
     *
     * @var int
     */
    private $width;

    /**
     * Text height
     *
     * @var int
     */
    private $height;

    /**
     * Constructor
     *
     * @param string $text
     * @param string $format
     */
    public function __construct(string $text, string $format = '%s')
    {
        $this->format = $format;
        $this->setWithFormat($text);
    }

    final public function setWithFormat(...$text)
    {
        $this->setText(vsprintf($this->format, $text));
    }


    final public function setText(string $text)
    {
        if (substr($text, -1) !== PHP_EOL) {
            $text = $text . PHP_EOL;
        }

        $this->text = $text;
    }

    public function setFormat(string $format)
    {
        $this->format = $format;
    }

    public function draw(): string
    {
        return $this->text;
    }

    /**
     * Get the width at the widest point
     *
     * @return int
     */
    public function getWidth(): int
    {
        if (null === $this->width) {
            $parts = explode(PHP_EOL, $this->text);
            $width = 0;
            foreach ($parts as $part) {
                $partWidth = strlen($part);
                if ($partWidth > $width) {
                    $width = $partWidth;
                }
            }

            $this->width = $width;
        }

        return $this->width;
    }

    /**
     * Get text height
     *
     * @return int
     */
    public function getHeight(): int
    {
        if (null === $this->height) {
            $this->height = substr_count($this->text, PHP_EOL);
        }

        return $this->height;
    }

    /**
     * Get x coordinate
     *
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * Set x coordinate
     *
     * @param int $x
     */
    public function setX(int $x)
    {
        $this->x = $x;
    }

    /**
     * Get y coordinate
     *
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }

    /**
     * Set y coordinate
     *
     * @param int $y
     */
    public function setY(int $y)
    {
        $this->y = $y;
    }
}
