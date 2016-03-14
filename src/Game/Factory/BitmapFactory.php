<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\GameOfLife\Game\Factory;

use Tebru\GameOfLife\Game\BoundsManager;

/**
 * Class BitmapFactory
 *
 * Create the bitmap array
 *
 * @author Nate Brunette <n@tebru.net>
 */
class BitmapFactory
{
    private $boundsManager;
    public function __construct(BoundsManager $boundsManager = null)
    {
        if (null === $boundsManager) {
            $boundsManager = new BoundsManager();
        }

        $this->boundsManager = $boundsManager;
    }

    /**
     * Make from number of rows and columns given the chance of life
     *
     * @param int $rows
     * @param int $cols
     * @param float $chanceOfLife
     * @return array
     */
    public function make(int $rows, int $cols, float $chanceOfLife): array
    {
        $grid = $this->createInitialGrid($rows, $cols, $chanceOfLife);

        return $this->toBitMap($grid, $cols);
        
    }

    /**
     * Make from a 1-dimensional grid of true/false values representing life
     *
     * @param array $grid
     * @param int $cols
     * @return array
     */
    public function makeFromGrid(array $grid, int $cols): array
    {
        return $this->toBitMap($grid, $cols);
    }

    /**
     * Create the initial grid based on chance of life
     *
     * @param int $rows
     * @param int $cols
     * @param float $chanceOfLife
     * @return array
     */
    private function createInitialGrid(int $rows, int $cols, float $chanceOfLife): array
    {
        $grid = [];
        for ($i = 0; $i < $rows * $cols; $i++) {
            $grid[] = (mt_rand(0, 100) < $chanceOfLife) ? true : false;
        }
        
        return $grid;
    }

    /**
     * Convert a grid to the bitmap
     *
     * @param array $grid
     * @param int $cols
     * @return array
     */
    private function toBitMap(array $grid, int $cols): array
    {
        $bitmap = [];

        foreach ($grid as $index => $value) {
            $bitmap[] = $this->getBitmapNumber($grid, $index, $cols);
        }

        return $bitmap;
    }

    /**
     * Get the current number for the cell
     *
     * @param array $grid
     * @param int $centerIndex
     * @param int $cols
     * @return mixed
     */
    private function getBitmapNumber(array $grid, int $centerIndex, int $cols)
    {
        $indexes = [
            $centerIndex - $cols - 1 => 256,
            $centerIndex - $cols => 128,
            $centerIndex - $cols + 1 => 64,
            $centerIndex - 1 => 32,
            $centerIndex => 16,
            $centerIndex + 1 => 8,
            $centerIndex + $cols - 1 => 4,
            $centerIndex + $cols => 2,
            $centerIndex + $cols + 1 => 1,
        ];

        list($centerIndexX, $centerIndexY) = $this->boundsManager->getXYFromIndex($centerIndex, $cols);

        $number = array_reduce(
            array_keys($indexes),
            function (int $carry, int $index) use ($indexes, $grid, $centerIndexX, $centerIndexY, $cols): int
            {
                if (!isset($grid[$index])) {
                    return $carry;
                }

                if (false === $grid[$index]) {
                    return $carry;
                }

                if (!$this->boundsManager->withinBounds($centerIndexX, $centerIndexY, $index, $cols)) {
                    return $carry;
                }

                return $carry + $indexes[$index];
            },
            0
        );

        return $number;
    }
}
