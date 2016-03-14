<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\GameOfLife\Game;

use ArrayIterator;
use IteratorAggregate;

/**
 * Class Grid
 *
 * @author Nate Brunette <n@tebru.net>
 */
class Grid implements IteratorAggregate
{
    /**
     * Array of numbers corresponding to which cells are alive in a 3x3 grid
     *
     * @var array
     */
    private $bitmap = [];

    /**
     * Number of columns
     *
     * @var int
     */
    private $numberOfColumns;

    /**
     * Number of rows
     *
     * @var int
     */
    private $numberOfRows;

    /**
     * Constructor
     *
     * @param array $bitmap
     * @param int $numberOfColumns
     */
    public function __construct(array $bitmap, int $numberOfColumns)
    {
        $this->bitmap = $bitmap;
        $this->numberOfColumns = $numberOfColumns;
    }

    /**
     * Get the bitmap
     *
     * @return array
     */
    public function getBitmap(): array
    {
        return $this->bitmap;
    }

    /**
     * Set the bitmap
     */
    public function setBitmap(array $bitmap)
    {
        $this->bitmap = $bitmap;
    }

    /**
     * Get the size of the grid
     *
     * @return int
     */
    public function getSize(): int
    {
        return sizeof($this->bitmap);
    }

    /**
     * Get the number of columns
     *
     * @return int
     */
    public function getNumberOfColumns(): int
    {
        return $this->numberOfColumns;
    }

    /**
     * Get the number of rows
     *
     * @return int
     */
    public function getNumberOfRows(): int
    {
        if (null === $this->numberOfRows) {
            $this->numberOfRows = sizeof($this->bitmap) / $this->numberOfColumns;
        }

        return $this->numberOfRows;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->bitmap);
    }
}
