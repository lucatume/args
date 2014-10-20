<?php


class Arg {

	/** @var  Arg_Object */
	protected $arg;

	public function __construct( $arg, $arg_name = null ) {
		$type      = gettype( $arg );
		$arg_name  = $arg_name ? $arg_name : $type;
		$this->arg = $this->get_arg_for_type( $type, $arg, $arg_name );
	}

//	Arg::_($arg)->is_string()->at_least(4)->in($this->allowed());
	public static function _( $arg, $arg_name = null ) {
		$instance = new self( $arg, $arg_name );

		return $instance->arg;
	}

	private function get_arg_for_type( $type, $arg, $arg_name ) {
		$class_name = ucwords( $type . '_Arg' );
		$class_name = str_replace( ' ', '_', $class_name );

		return new $class_name( $type, $arg, $arg_name );
	}
}


abstract class Arg_Object {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var mixed
	 */
	protected $value;

	/**
	 * @var string
	 */
	protected $type;

	public function get_name() {
		return $this->name;
	}

	public function __construct( $type, $value, $name ) {
		$this->type  = $type;
		$this->value = $value;
		$this->name  = $name;
	}

	public function assert( $condition, $reason ) {
		if ( ! $condition ) {

			throw new InvalidArgumentException( $reason );
		}

		return $this;
	}

	public function is_bool() {
		if ( ! is_bool( $this->value ) ) {
			throw new InvalidArgumentException( $this->name . ' must be a boolean.' );
		}

		return $this;
	}

	public function is_object() {
		if ( ! is_object( $this->value ) ) {
			throw new InvalidArgumentException( $this->name . ' must be an object.' );
		}

		return $this;
	}

	public function is_array() {
		if ( ! is_array( $this->value ) ) {
			throw new InvalidArgumentException( $this->name . ' must be an array.' );
		}

		return $this;
	}

	public function is_associative_array() {
		if ( ! is_associative_array( $this->value ) ) {
			throw new InvalidArgumentException( $this->name . ' must be an associative array.' );
		}

		return $this;
	}

	public function is_scalar() {
		if ( ! is_scalar( $this->value ) ) {
			throw new InvalidArgumentException( $this->name . ' must be a scalar.' );
		}

		return $this;
	}

	public function is_int() {
		if ( ! is_int( $this->value ) ) {
			throw new InvalidArgumentException( $this->name . ' must be an int.' );
		}

		return $this;
	}

	public function is_float() {
		if ( ! is_float( $this->value ) ) {
			throw new InvalidArgumentException( $this->name . ' must be a float.' );
		}

		return $this;
	}

	public function is_double() {
		if ( ! is_double( $this->value ) ) {
			throw new InvalidArgumentException( $this->name . ' must be a double.' );
		}

		return $this;
	}

	public function is_string() {
		if ( ! is_string( $this->value ) ) {
			throw new InvalidArgumentException( $this->name . ' must be a string.' );
		}

		return $this;
	}

	public function is_resource() {
		if ( ! is_resource( $this->value ) ) {
			throw new InvalidArgumentException( $this->name . ' must be a resource.' );
		}

		return $this;
	}

	public function is_null() {
		if ( ! is_null( $this->value ) ) {
			throw new InvalidArgumentException( $this->name . ' must be a resource.' );
		}

		return $this;
	}

}


abstract class Scalar_Arg extends Arg_Object {

	public function at_least( $value ) {
		if ( $this->value < $value ) {
			throw new InvalidArgumentException( $this->name . ' must be at least ' . $value );
		}

		return $this;
	}

	public function at_most( $value ) {
		if ( $this->value > $value ) {
			throw new InvalidArgumentException( $this->name . ' must be at most ' . $value );
		}

		return $this;
	}

	public function greater_than( $value ) {
		if ( $this->value <= $value ) {
			throw new InvalidArgumentException( $this->name . ' must be greater than ' . $value );
		}

		return $this;
	}

	public function less_than( $value ) {
		if ( $this->value >= $value ) {
			throw new InvalidArgumentException( $this->name . ' must be less than ' . $value );
		}

		return $this;
	}
}


class  Boolean_Arg extends Scalar_Arg {

}


class  Integer_Arg extends Scalar_Arg {

}


class  Double_Arg extends Scalar_Arg {

}


class  String_Arg extends Scalar_Arg {

}


class  Array_Arg extends Arg_Object {

	public function has_structure( $structure ) {
		if ( ! array_has_structure( $this->value, $structure ) ) {
			throw new InvalidArgumentException( $this->name . ' must have the structure\\n' . print_r( $structure ) );
		}

		return $this;
	}

	public function defaults( $defaults ) {
		$this->value = array_merge( $defaults, $this->value );

		return $this;
	}

	public function contains( $value ) {
		$values = is_array( $value ) ? $value : array( $value );
		foreach ( $values as $value ) {
			if ( ! in_array( $value, $this->value ) ) {
				throw new InvalidArgumentException( $this->name . ' must contain ' . $value );
			}
		}

		return $this;
	}

	public function does_not_contain( $value ) {
		$values = is_array( $value ) ? $value : array( $value );
		foreach ( $values as $value ) {
			if ( in_array( $value, $this->value ) ) {
				throw new InvalidArgumentException( $this->name . ' must contain ' . $value );
			}
		}

		return $this;
	}

	public function key_exists( $key ) {
		if ( ! array_key_exists( $key, $this->value ) ) {
			throw new InvalidArgumentException( $this->name . ' must have the ' . $key . ' key' );
		}

		return $this;
	}

	public function key_does_not_exis( $key ) {

		if ( array_key_exists( $key, $this->value ) ) {
			throw new InvalidArgumentException( $this->name . ' must have the ' . $key . ' key' );
		}

		return $this;
	}
}


class  Object_Arg extends Arg_Object {

	public function is_set( $property ) {

		if ( ! isset( $this->value->$property ) ) {
			throw new InvalidArgumentException( $this->name . ' must have the ' . $property . 'property' );
		}

		return $this;
	}
}


class  Resource_Arg extends Arg_Object {

}


class  NULL_Arg extends Arg_Object {

}


class  Unknown_Type_Arg extends Arg_Object {

}


if ( ! function_exists( 'is_associative_array' ) ) {
	function is_associative_array( $var ) {
		return is_array( $var ) and ( array_values( $var ) !== $var );
	}
}

if ( ! function_exists( 'array_has_structure' ) ) {
	function array_has_structure( array $arr, array $structure ) {
		return count( array_diff( array_merge( $arr, $structure ), $arr ) ) === 0;
	}
}
