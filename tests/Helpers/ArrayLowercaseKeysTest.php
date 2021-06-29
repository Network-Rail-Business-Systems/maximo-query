<?php

test('it converts the array keys to lowercase', function() {
   $array = [
       'THIS_SHOULD_BE_LOWERCASE' => 'some value',
       'SO_SHOULD_THIS' => 'another value',
   ];

   foreach(array_keys(array_lowercase_keys($array)) as $key) {
       $this->assertSame(strtolower($key), $key);
   }
});
