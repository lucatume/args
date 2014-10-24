<?php
class Check
{
    /**
     * @var CheckState
     */
    private $state;

    public function __construct(CheckState $state)
    {
        $this->setState($state);
    }

    /**
     * @throws IllegalStateTransitionException
     */
    public function pass()
    {
        $this->setState($this->state->pass());
    }

    /**
     * @throws IllegalStateTransitionException
     */
    public function fail()
    {
        $this->setState($this->state->fail());
    }

    /**
     * @throws IllegalStateTransitionException
     */
    public function or_condition()
    {
        $this->setState($this->state->or_condition());
    }

    /**
     * @throws IllegalStateTransitionException
     */
    public function throw_exception()
    {
        $this->setState($this->state->throw_exception());
    }

    /**
     * @return bool
     */
    public function is_passing()
    {
        return $this->state instanceof PassingCheckState;
    }

    /**
     * @return bool
     */
    public function is_failing()
    {
        return $this->state instanceof FailingCheckState;
    }

    /**
     * @return bool
     */
    public function is_or_failing()
    {
        return $this->state instanceof OrFailingCheckState;
    }

    /**
     * @return bool
     */
    public function is_failed()
    {
        return $this->state instanceof FailedCheckState;
    }

    /**
     * @return bool
     */
    public function has_thrown()
    {
        return $this->state instanceof ThrownCheckState;
    }

    private function setState(CheckState $state)
    {
        $this->state = $state;
    }
}
