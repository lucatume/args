<?php
class FailedCheckState extends AbstractCheckState
{
    /**
     * @return FailedCheckState
     */
    public function fail()
    {
        return new FailedCheckState;
    }

    /**
     * @return FailedCheckState
     */
    public function pass()
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
     * @return ThrownCheckState
     */
    public function throw_exception()
    {
        return new ThrownCheckState;
    }
}
