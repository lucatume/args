<?php
class PassingCheckState extends AbstractCheckState
{
    /**
     * @return PassingCheckState
     */
    public function pass()
    {
        return new PassingCheckState;
    }

    /**
     * @return PassingCheckState
     */
    public function or_condition()
    {
        return new PassingCheckState;
    }

    /**
     * @return FailingCheckState
     */
    public function fail()
    {
        return new FailingCheckState;
    }

    /**
     * @return ThrownCheckState
     */
    public function throw_exception()
    {
        return new ThrownCheckState;
    }
}
