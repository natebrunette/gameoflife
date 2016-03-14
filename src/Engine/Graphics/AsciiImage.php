<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\GameOfLife\Engine\Graphics;

/**
 * Class AsciiImage
 *
 * The class is for text that isn't drawable.
 * This class should probably be rethought.
 *
 * @author Nate Brunette <n@tebru.net>
 */
class AsciiImage
{
    /**
     * @var string
     */
    private $image;

    /**
     * Constructor
     *
     * @param string $image
     */
    public function __construct(string $image = null)
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->image;
    }

    /**
     * Load from a file
     *
     * @param string $filename
     */
    public function loadFromFile(string $filename)
    {
        $this->image = trim(file_get_contents($filename));
    }
}
