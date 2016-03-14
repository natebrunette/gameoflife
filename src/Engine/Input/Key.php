<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\GameOfLife\Engine\Input;

use Tebru\Enum\AbstractEnum;

/**
 * Class Key
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class Key extends AbstractEnum
{
    const A = 'a';
    const B = 'b';
    const C = 'c';
    const D = 'd';
    const E = 'e';
    const F = 'f';
    const G = 'g';
    const H = 'h';
    const I = 'i';
    const J = 'j';
    const K = 'k';
    const L = 'l';
    const M = 'm';
    const N = 'n';
    const O = 'o';
    const P = 'p';
    const Q = 'q';
    const R = 'r';
    const S = 's';
    const T = 't';
    const U = 'u';
    const V = 'v';
    const W = 'w';
    const X = 'x';
    const Y = 'y';
    const Z = 'z';
    const NUM0 = '0';
    const NUM1 = '1';
    const NUM2 = '2';
    const NUM3 = '3';
    const NUM4 = '4';
    const NUM5 = '5';
    const NUM6 = '6';
    const NUM7 = '7';
    const NUM8 = '8';
    const NUM9 = '9';
    const SUBTRACT = '-';
    const ADD = '+';
    const EQUAL = '=';

    /**
     * Return an array of enum class constants
     *
     * @return array
     */
    public static function getConstants()
    {
        return [
            self::A,
            self::B,
            self::C,
            self::D,
            self::E,
            self::F,
            self::G,
            self::H,
            self::I,
            self::J,
            self::K,
            self::L,
            self::M,
            self::N,
            self::O,
            self::P,
            self::Q,
            self::R,
            self::S,
            self::T,
            self::U,
            self::V,
            self::W,
            self::X,
            self::Y,
            self::Z,
            self::NUM0,
            self::NUM1,
            self::NUM2,
            self::NUM3,
            self::NUM4,
            self::NUM5,
            self::NUM6,
            self::NUM7,
            self::NUM8,
            self::NUM9,
            self::SUBTRACT,
            self::ADD,
            self::EQUAL,
        ];
    }
}
