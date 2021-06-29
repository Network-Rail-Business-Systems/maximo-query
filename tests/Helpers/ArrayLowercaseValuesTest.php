<?php

test('it converts the array values to lowercase', function() {
    $array = ['THIS_SHOULD_BE_LOWERCASE', 'SO_SHOULD_THIS'];

    foreach(array_lowercase_values($array) as $value) {
        $this->assertSame(strtolower($value), $value);
    }
});
