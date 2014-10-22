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

	/**
	 * @test
	 * it should allow asserting
	 */
	public function it_should_allow_asserting() {
		$sut     = Arg::_( 'foo' );
		$message = 'Foo!';
		$this->setExpectedException( 'InvalidArgumentException', $message );

		$sut->assert( false, $message );
	}

	/**
	 * @test
	 * it should allow defaulting an array
	 */
	public function it_should_allow_defaulting_an_array() {
		$model = [ 'foo' => 'baz', 'some' => 'more' ];
		$in    = [ 'some' => 23, 'bar' => 21 ];

		$sut = Arg::_( $in )->defaults( $model );

		$default = [ 'foo' => 'baz', 'some' => 23, 'bar' => 21 ];
		$this->assertEquals( $default, $sut->value );
	}

	/**
	 * @test
	 * it should allow checking an array structure
	 */
	public function it_should_allow_checking_an_array_structure() {
		$model = array(
			'key1' => null,
			'key2' => null,
			'key3' => array(
				'key1' => null,
				'key2' => null
			)
		);

		$arr = array(
			'key1' => 'some',
			'key2' => 23,
			'key3' => array(
				'key1' => 24,
				'key2' => 12
			)
		);

		$sut = Arg::_( $arr )->has_structure( $model );

		$this->setExpectedException( 'InvalidArgumentException' );
		$sut = Arg::_( [ 'foo' => 'baz' ] )->has_structure( $model );
	}

	/**
	 * @test
	 * it should allow checking an object has a property
	 */
	public function it_should_allow_checking_an_object_has_a_property() {
		$obj       = new stdClass();
		$obj->some = 'foo';
		$sut       = Arg::_( $obj )->is_set( 'some' );

		$this->setExpectedException( 'InvalidArgumentException' );
		$sut->is_set( 'more' );
	}

	/**
	 * @test
	 * it should allow checkin an object has many properties
	 */
	public function it_should_allow_checkin_an_object_has_many_properties() {
		$obj       = new stdClass();
		$obj->some = 'foo';
		$obj->foo  = 21;
		$sut       = Arg::_( $obj )->is_set( 'some', 'foo' );

		$this->setExpectedException( 'InvalidArgumentException' );
		$sut->is_set( 'more', 'one' );
	}

	/**
	 * @test
	 * it should allow setting negation using not
	 */
	public function it_should_allow_setting_negation_using_not() {
		$obj = new stdClass();
		$sut = Arg::_( $obj )->not();

		$this->assertEquals( false, $sut->match_true );
	}

	/**
	 * @test
	 * it should allow setting negation for next method using not
	 */
	public function it_should_allow_setting_negation_for_next_method_using_not() {
		$obj       = new stdClass();
		$obj->some = 'foo';
		$obj->foo  = 21;
		$this->setExpectedException( 'InvalidArgumentException' );

		$sut = Arg::_( $obj )->not()->is_set( 'some' );
	}

	/**
	 * @test
	 * it should allow checking minimum string length
	 */
	public function it_should_allow_checking_minimum_string_length() {
		Arg::_( 'some' )->length( 0, 4 );
		$this->setExpectedException( 'InvalidArgumentException' );

		$sut = Arg::_( 'foo' )->length( 10 );
	}

	/**
	 * @test
	 * it should allow cecking max string length
	 */
	public function it_should_allow_cecking_max_string_length() {

		Arg::_( 'some' )->length( 0, 4 );
		$this->setExpectedException( 'InvalidArgumentException' );

		$sut = Arg::_( 'lorem ipsum' )->length( 0, 3 );
	}

	/**
	 * @test
	 * it should allow checking if string matches pattern
	 */
	public function it_should_allow_checking_if_string_matches_pattern() {
		$sut = Arg::_( 'lorem ipsum' )->match( '/\\w+\\s+\\w+/' );
		$this->setExpectedException( 'InvalidArgumentException' );
//		$this->assertTrue( preg_match( "/\\w+\\s+\\w+/um", 'lorem ipsum' ));
		$sut = Arg::_( 'lorem ipsum' )->match( '/\\w+\\s+\\d+/' );
	}

	/**
	 * @test
	 * it should allow checkin an array count
	 */
	public function it_should_allow_checkin_an_array_count() {
		Arg::_( [ 1, 2, 3, 4, 5, 6, 7, 8 ] )->count( 2, 10 );

		$this->setExpectedException( 'InvalidArgumentException' );

		Arg::_( [ 1, 2, 3 ] )->count( 4 );

		$this->setExpectedException( 'InvalidArgumentException' );

		Arg::_( [ 1, 2, 3, 4, 5, 6, 7 ] )->count( 2, 5 );
	}

	/**
	 * @test
	 * it should normally throw for the first exception
	 */
	public function it_should_normally_throw_for_the_first_exception() {
		$message = 'Foo must be a boolean';
		$this->setExpectedException('InvalidArgumentException', $message);

		Arg::_(23, 'Foo')->is_bool()->is_scalar();
	}

	/**
	 * @test
	 * it should allow setting a passing or condition
	 */
	public function it_should_allow_setting_a_passing_or_condition() {
		$passed = Arg::_('foo')->is_int()->vel()->is_string()->did_pass();

		$this->assertTrue($passed);
	}

	/**
	 * @test
	 * it should allow specifying an OR condition
	 */
	public function it_should_allow_specifying_an_or_condition() {
		$this->setExpectedException('InvalidArgumentException');

		Arg::_('foo')->is_int()->vel()->is_array();
	}
}