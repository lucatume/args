<?php
class PassingCheckTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Check
     */
    private $check;

    /**
     * @covers Check::__construct
     * @covers Check::setState
     */
    protected function setUp()
    {
        $this->check = new Check(new PassingCheckState);
    }

    /**
     * @covers Check::is_passing
     */
    public function testIs_passing()
    {
        $this->assertTrue($this->check->is_passing());
    }

    /**
     * @covers Check::is_failing
     */
    public function testIsNot_failing()
    {
        $this->assertFalse($this->check->is_failing());
    }

    /**
     * @covers Check::is_or_failing
     */
    public function testIsNot_or_failing()
    {
        $this->assertFalse($this->check->is_or_failing());
    }

    /**
     * @covers Check::is_failed
     */
    public function testIsNot_failed()
    {
        $this->assertFalse($this->check->is_failed());
    }

    /**
     * @covers Check::has_thrown
     */
    public function testHas_thrown()
    {
        $this->assertFalse($this->check->has_thrown());
    }

    /**
     * @covers Check::pass
     * @covers PassingCheckState::pass
     * @uses   Check::is_passing
     */
    public function testCan_pass()
    {
        $this->check->pass();
        $this->assertTrue($this->check->is_passing());
    }

    /**
     * @covers Check::fail
     * @covers PassingCheckState::fail
     * @uses   Check::is_failing
     */
    public function testCan_fail()
    {
        $this->check->fail();
        $this->assertTrue($this->check->is_failing());
    }

    /**
     * @covers Check::or_condition
     * @covers PassingCheckState::or_condition
     * @uses   Check::is_passing
     */
    public function testCan_apply_or_condition()
    {
        $this->check->or_condition();
        $this->assertTrue($this->check->is_passing());
    }

    /**
     * @covers Check::throw_exception
     * @covers PassingCheckState::throw_exception
     * @uses   Check::has_thrown
     */
    public function testCan_throw()
    {
        $this->check->throw_exception();
        $this->assertTrue($this->check->has_thrown());
    }
}
