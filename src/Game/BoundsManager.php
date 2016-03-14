<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\GameOfLife\Game;

/**
 * Class BoundsManager
 *
 * Checks if the current index should fall within the expected 3x3 grid
 *
 * @author Nate Brunette <n@tebru.net>
 */
class BoundsManager
{
    /**
     * Check if the index exists in the 3x3 grid
     *
     * @param int $centerIndexX
     * @param int $centerIndexY
     * @param int $index
     * @param int $numberOfColumns
     * @return bool
     */
    public function withinBounds(int $centerIndexX, int $centerIndexY, int $index, int $numberOfColumns): bool
    {
        list($x, $y) = $this->getXYFromIndex($index, $numberOfColumns);

        if ($x < 0 || $y < 0) {
            return false;
        }

        if ($x !== $centerIndexX - 1 && $x !== $centerIndexX && $x !== $centerIndexX + 1) {
            return false;
        }

        if ($y !== $centerIndexY - 1 && $y !== $centerIndexY && $y !== $centerIndexY + 1) {
            return false;
        }

        return true;
    }

    /**
     * Convert an x/y coordinate to the index
     *
     * @param int $x
     * @param int $y
     * @param int $numberOfColumns
     * @return int
     */
    public function getIndexFromXY(int $x, int $y, int $numberOfColumns): int
    {
        return $x + ($numberOfColumns * $y);
    }

    /**
     * Convert an index to x/y coordinates
     *
     * @param int $index
     * @param int $numberOfColumns
     * @return array
     */
    public function getXYFromIndex(int $index, int $numberOfColumns): array
    {
        return [
            (int) $index % $numberOfColumns,
            (int) floor($index / $numberOfColumns),
        ];
    }
}
