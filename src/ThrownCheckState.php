<?php
class ThrownCheckState extends AbstractCheckState
{
    /**
     * @return ThrownCheckState
     */
    public function pass()
    {
        return new ThrownCheckState;
    }

    /**
     * @return ThrownCheckState
     */
    public function fail()
    {
        return new ThrownCheckState;
    }

    /**
     * @return ThrownCheckState
     */
    public function or_condition()
    {
        return new ThrownCheckState;
    }

    /**
     * @return ThrownCheckState
     */
    public function throw_exception()
    {
        return new ThrownCheckState;
    }
}
