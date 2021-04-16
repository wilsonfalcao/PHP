<?php
class sortitem 
{

public function build_sorter($key) {
    return function ($a, $b) use ($key) {
        // pay attention to `{$key}` notation
        return strnatcmp($a->{$key}, $b->{$key});
   };
}
}
?>