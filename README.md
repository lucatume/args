# Args

A library to make argument checking less of a chore.

## Missing type hinting
PHP will not allow [type hinting](http://php.net/manual/en/language.oop5.typehinting.php) scalar values in functions and methods arguments forcing cycles like this to be added in each function body:

    if (!is_string($s)){
        throw ...
    }
    if(strlen($s) > 20 || strlen($s) < 10){
        throw ...
    }
    if(preg_match($this->name_pattern, $s) === false){
        throw ...
    }

The library, still a work in progress aims at making argument check more granular, tested and [DRYer](http://en.wikipedia.org/wiki/Don't_repeat_yourself) ') by introducing a fluent interface.  
The check above could become

    Arg::_($s)->is_string()->length(10, 20)->match($this->name_pattern);

Some array-related functions are there too:

    Arg::_($a)->count(6, 10)->contains('value1', 'value2')->has_key('key_one');

Each method will throw an Exception if the condition is not satisfied. In the array example if array is `['some', 23]` then an exception will be thrown with the message:

    Array must contain at least 6 elements!

I'll be documenting as the work goes on.
