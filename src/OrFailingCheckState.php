<?php
class OrFailingCheckState extends AbstractCheckState
{
    /**
     * @return FailedCheckState
     */
    public function fail()
    {
        return new FailedCheckState;
    }

    /**
     * @return OrFailingCheckState
     */
    public function or_condition()
    {
        return new OrFailingCheckState;
    }

    /**
     * @return PassingCheckState
     */
    public function pass()
    {
        return new PassingCheckState;
    }

    /**
     * @return ThrownCheckState
     */
    public function throw_exception()
    {
        return new ThrownCheckState;
    }
}
