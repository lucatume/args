<?php


class ArgTest extends \PHPUnit_Framework_TestCase {

	protected function setUp() {
	}

	protected function tearDown() {
	}

	public function args() {
		return [
			[ false, 'Boolean_Arg' ],
			[ true, 'Boolean_Arg' ],
			[ 0, 'Integer_Arg' ],
			[ 23, 'Integer_Arg' ],
			[ 32.3, 'Double_Arg' ],
			[ 1.0, 'Double_Arg' ],
			[ 'foo', 'String_Arg' ],
			[ '', 'String_Arg' ],
			[ new stdClass(), 'Object_Arg' ],
			[ null, 'NULL_Arg' ],
			[ [ ], 'Array_Arg' ],
			[ [ 'foo', 21 ], 'Array_Arg' ]
		];
	}

	/**
	 * @test
	 * it should return the right kind of argument object
	 * @dataProvider args
	 */
	public function it_should_return_the_right_kind_of_argument_object( $arg, $class ) {
		$sut = Arg::_( $arg );

		$this->assertInstanceOf( $class, $sut );
	}
}