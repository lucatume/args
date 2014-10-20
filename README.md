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

    Arg::_($s)->is_string()->lenght(10, 20)->match($this->name_pattern);

I'll be documenting the class as the work goes on.
