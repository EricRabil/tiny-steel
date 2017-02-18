<?php

namespace TinySteel;

/**
 * Shortcut functions for array handling
 * 
 * @since   1.0
 * @author  Eric Rabil <ericjrabil@gmail.com>
 */
class ArrayMethods {

    public static function lastKey($array) {
        end($array);
        return key($array);
    }

    public static function lastValue($array) {

        return end($array);
    }

}
