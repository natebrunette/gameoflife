<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\GameOfLife\Game;

use Tebru\GameOfLife\Engine\Graphics\AsciiImage;
use Tebru\GameOfLife\Engine\Graphics\Drawable;
use Tebru\GameOfLife\Game\Factory\BitmapFactory;

/**
 * Class World
 *
 * @author Nate Brunette <n@tebru.net>
 */
class World implements Drawable
{
    /**
     * Image to display if cell is alive
     *
     * @var AsciiImage
     */
    private $aliveImage;

    /**
     * Image to display if cell is dead
     *
     * @var AsciiImage
     */
    private $deadImage;

    /**
     * The grid
     *
     * @var Grid
     */
    private $grid;

    /**
     * Window x coordinate
     *
     * @var int
     */
    private $x = 0;

    /**
     * Window y coordinate
     *
     * @var int
     */
    private $y = 0;

    /**
     * Lookup table to determine if the next generation will be alive or dead
     *
     * @var array
     */
    private $bitmapLookup;

    /**
     * Creates a new bitmap
     *
     * @var BitmapFactory
     */
    private $bitmapFactory;

    /**
     * Handles bounds checking
     *
     * @var BoundsManager
     */
    private $boundsManager;

    /**
     * Constructor
     *
     * @param AsciiImage $aliveImage
     * @param AsciiImage $deadImage
     * @param Grid $grid
     * @param array $bitmapLookup
     * @param BitmapFactory $bitmapFactory
     * @param BoundsManager $boundsManager
     */
    public function __construct(
        AsciiImage $aliveImage,
        AsciiImage $deadImage,
        Grid $grid,
        array $bitmapLookup,
        BitmapFactory $bitmapFactory,
        BoundsManager $boundsManager = null
    ) {
        if (null === $boundsManager) {
            $boundsManager = new BoundsManager();
        }

        $this->aliveImage = $aliveImage;
        $this->deadImage = $deadImage;
        $this->grid = $grid;
        $this->bitmapLookup = $bitmapLookup;
        $this->bitmapFactory = $bitmapFactory;
        $this->boundsManager = $boundsManager;
    }

    /**
     * Update the world elements
     */
    public function update()
    {
        $bitmap = $this->grid->getBitmap();

        foreach ($this->grid as $key => &$number) {
            if ($number === 0) {
                continue;
            }

            $willBeAlive = $this->bitmapLookup[$number];
            $isAlive = $this->isAlive($number);

            if (($isAlive && $willBeAlive) || (!$isAlive && !$willBeAlive)) {
                continue;
            }

            $modifier = ($willBeAlive) ? 1 : -1;
            $numberOfColumns = $this->grid->getNumberOfColumns();
            list($centerIndexX, $centerIndexY) = $this->boundsManager->getXYFromIndex($key, $numberOfColumns);
            $indexes = $this->getIndexes($key);

            foreach ($indexes as $index => $neighborNumber) {
                if (!isset($bitmap[$index])) {
                    continue;
                }

                if (!$this->boundsManager->withinBounds($centerIndexX, $centerIndexY, $index, $numberOfColumns)) {
                    continue;
                }

                $bitmap[$index] = $bitmap[$index] + ($neighborNumber * $modifier);
            }
        }

        $this->grid->setBitmap($bitmap);
    }

    /**
     * Toggle an element on or off
     *
     * @param int $x
     * @param int $y
     */
    public function toggle(int $x, int $y)
    {
        $grid = [];
        $targetIndex = $this->boundsManager->getIndexFromXY($x, $y, $this->grid->getNumberOfColumns());
        foreach ($this->grid as $index => $item) {
            if ($index === $targetIndex) {
                // toggle current state
                $grid[] = !(($item & 16) !== 0);
                continue;
            }

            // set current state
            $grid[] = ($item & 16) !== 0;
        }

        $bitmap = $this->bitmapFactory->makeFromGrid($grid, $this->grid->getNumberOfColumns());

        $this->grid->setBitmap($bitmap);
    }

    /**
     * Draw the world
     *
     * @return string
     */
    public function draw(): string
    {
        $output = array_map(function ($value) {
            return (($value & 16) === 0) ? $this->deadImage : $this->aliveImage;
        }, $this->grid->getBitmap());

        $chunked = array_chunk($output, $this->grid->getNumberOfColumns());

        return array_reduce($chunked, function (string $carry = null, array $value): string {
            return sprintf('%s%s%s', $carry, implode(' ', $value), PHP_EOL);
        });
    }

    /**
     * Get the width
     *
     * @return int
     */
    public function getWidth(): int
    {
        return ($this->grid->getNumberOfColumns() * 2) - 1;
    }

    /**
     * Get the height
     *
     * @return int
     */
    public function getHeight(): int
    {
        return $this->grid->getNumberOfRows();
    }

    /**
     * Get the x coordinate
     *
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * Set the x coordinate
     *
     * @param int $x
     */
    public function setX(int $x)
    {
        $this->x = $x;
    }

    /**
     * Get the y coordinate
     *
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }

    /**
     * Set the y coordinate
     *
     * @param int $y
     */
    public function setY(int $y)
    {
        $this->y = $y;
    }

    /**
     * Get neighbor indexes
     *
     * @param int $key
     * @return array
     */
    private function getIndexes(int $key): array
    {
        $colSize = $this->grid->getNumberOfColumns();

        return [
            $key - $colSize - 1 => 1,
            $key - $colSize => 2,
            $key - $colSize + 1 => 4,
            $key - 1 => 8,
            $key => 16,
            $key + 1 => 32,
            $key + $colSize - 1 => 64,
            $key + $colSize => 128,
            $key + $colSize + 1 => 256,
        ];
    }

    /**
     * Check if a cell is currently alive
     *
     * @param int $number
     * @return bool
     */
    private function isAlive(int $number): bool
    {
        return ($number & 16) !== 0;
    }
}
