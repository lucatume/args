<?php
interface CheckState
{
    public function pass();
    public function fail();
    public function or_condition();
    public function throw_exception();
}
