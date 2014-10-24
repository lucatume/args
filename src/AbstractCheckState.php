<?php
abstract class AbstractCheckState implements CheckState
{
    /**
     * @throws IllegalStateTransitionException
     */
    public function pass()
    {
        throw new IllegalStateTransitionException;
    }

    /**
     * @throws IllegalStateTransitionException
     */
    public function fail()
    {
        throw new IllegalStateTransitionException;
    }

    /**
     * @throws IllegalStateTransitionException
     */
    public function or_condition()
    {
        throw new IllegalStateTransitionException;
    }

    /**
     * @throws IllegalStateTransitionException
     */
    public function throw_exception()
    {
        throw new IllegalStateTransitionException;
    }
}
