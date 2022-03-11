<?php

class T
{
    static $test = 'aaaa';
    public static function test()
    {
        echo 'hello' . self::$test;
    }
}

T::test();
