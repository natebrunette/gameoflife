<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\GameOfLife\Engine\Graphics;

/**
 * Class WindowEvent
 *
 * @author Nate Brunette <n@tebru.net>
 */
class WindowEvent
{
    /**#@+
     * Window Event Types
     */
    const KEY_PRESSED = 'keyPressed';
    const CLOSED = 'closed';
    /**#@-*/

    /**
     * Event type
     *
     * @var string
     */
    private $type;

    /**
     * Event value
     *
     * @var string
     */
    private $value;

    /**
     * Get the event type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set the event Type
     *
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * Get the event value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the event value
     *
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}
