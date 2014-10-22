<?php


	use Doctrine\Instantiator\Exception\InvalidArgumentException;

	class Arg {

		/** @var  Arg_Object */
		protected $arg;

		public function __construct( $arg, $arg_name = null ) {
			$type = gettype( $arg );
			$arg_name = $arg_name ? $arg_name : $type;
			$this->arg = $this->get_arg_for_type( $type, $arg, $arg_name );
		}

		private function get_arg_for_type( $type, $arg, $arg_name ) {
			$class_name = ucwords( $type . '_Arg' );
			$class_name = str_replace( ' ', '_', $class_name );

			return new $class_name( $type, $arg, $arg_name );
		}

		public static function _( $arg, $arg_name = null ) {
			$instance = new self( $arg, $arg_name );

			return $instance->arg;
		}
	}


	abstract class Arg_Object {

		/**
		 * @var mixed
		 */
		public $value;
		/**
		 * @var string
		 */
		protected $name;
		/**
		 * @var string
		 */
		protected $type;

		/** @var  bool */
		public $match_true = true;

		/**
		 * @var bool
		 */
		protected $skip = false;

		/**
		 * @var string
		 */
		protected $message = '';

		/**
		 * @var bool/string
		 */
		protected $reason = false;
		/**
		 * @var bool/string
		 */
		protected $previous_reason = false;

		/** @var  bool */
		protected $or_condition;

		/**
		 * @return string
		 */
		protected function get_negation() {
			$negation = $this->match_true ? '' : ' not';
			$this->reset_negation();

			return $negation;
		}

		public function not() {
			$this->match_true = false;

			return $this;
		}

		public function __construct( $type, $value, $name ) {
			$this->type = $type;
			$this->value = $value;
			$this->name = $name;
		}

		public function __destruct() {
			if ( $this->reason ) {
				throw new InvalidArgumentException( $this->reason );
			}
		}

		public function get_name() {
			return $this->name;
		}

		public function assert( $condition, $reason ) {
			if ( ! $condition && ! $this->skip ) {
				$this->reason = $this->reason ? $this->reason . ' or ' . $reason : $reason;
				$this->skip = true;
			} else if ( $condition && $this->or_condition ) {
				$this->skip = false;
				$this->reason = false;
				$this->or_condition = false;
			}

			return $this;
		}

		public function is_bool() {
			$condition = $this->match_true === is_bool( $this->value );
			$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' be a boolean.' );

			return $this;
		}

		public function is_object() {
			$condition = $this->match_true === is_object( $this->value );
			$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' be an object.' );

			return $this;
		}

		public function is_array() {
			$condition = $this->match_true === is_array( $this->value );
			$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' be an array.' );

			return $this;
		}

		public function is_associative_array() {
			$condition = $this->match_true === is_associative_array( $this->value );
			$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' be an associative array.' );

			return $this;
		}

		public function is_scalar() {
			$condition = $this->match_true === is_scalar( $this->value );
			$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' be a scalar.' );

			return $this;
		}

		public function did_pass() {
			return ! (bool) $this->reason;
		}

		public function is_int() {
			$condition = $this->match_true === is_int( $this->value );
			$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' be an int.' );

			return $this;
		}

		public function is_numeric() {
			$this->assert( $this->match_true === is_numeric( $this->value ), $this->name . ' must' . $this->get_negation() . ' be an int.' );

			return $this;
		}

		public function is_float() {
			$this->assert( $this->match_true === is_float( $this->value ), $this->name . ' must' . $this->get_negation() . ' be a float.' );

			return $this;
		}

		public function is_double() {
			$condition = $this->match_true === is_double( $this->value );
			$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' be a double.' );

			return $this;
		}

		public function is_string() {
			$condition = $this->match_true === is_string( $this->value );
			$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' be a string.' );

			return $this;
		}

		public function is_resource() {
			$condition = $this->match_true === is_resource( $this->value );
			$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' be a resource.' );

			return $this;
		}

		public function is_null() {
			$condition = $this->match_true === is_null( $this->value );
			$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' be a resource.' );

			return $this;
		}

		private function reset_negation() {
			$this->match_true = true;
		}

		public function vel() {
			return $this->_or();
		}

		public function _or() {
			$this->or_condition = true;

			return $this;
		}

		public function aut() {
			return $this->vel();
		}

	}


	abstract class Scalar_Arg extends Arg_Object {

		public function at_least( $value ) {
			$condition = $this->match_true === $this->value < $value;
			$this->assert( $condition, $this->name . ' must be at least ' . $value );

			return $this;
		}

		public function at_most( $value ) {
			$condition = $this->match_true === $this->value > $value;
			$this->assert( $condition, $this->name . ' must be at most ' . $value );

			return $this;
		}

		public function greater_than( $value ) {
			$condition = $this->match_true === $this->value <= $value;
			$this->assert( $condition, $this->name . ' must be greater than ' . $value );

			return $this;
		}

		public function less_than( $value ) {
			$condition = $this->match_true === $this->value >= $value;
			$this->assert( $condition, $this->name . ' must be less than ' . $value );

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

		public function length( $min, $max = null ) {
			$len = strlen( $this->value );
			$condition = $this->match_true === ( $len >= $min );
			$this->assert( $condition, $this->name . ' must have a minimum length of ' . $min );

			$condition = $max && $this->match_true === $len <= $max;
			$this->assert( $condition, $this->name . ' must have a maximum length of ' . $max );

			return $this;
		}

		public function match( $pattern ) {
			$condition = $this->match_true === (bool) preg_match( $pattern, $this->value );
			$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' match pattern ' . $pattern );
		}
	}


	class  Array_Arg extends Arg_Object {

		public function count( $min, $max = null ) {
			$count = count( $this->value );
			$condition = $this->match_true === $count >= $min;
			$this->assert( $condition, $this->name . ' must contain at least ' . $min . ' elements' );
			$condition = $max && $this->match_true === $count <= $max;
			$this->assert( $condition, $this->name . ' must contain at most ' . $max . ' elements' );

			return $this;
		}

		public function has_structure( $structure ) {
			$condition = $this->match_true === array_has_structure( $this->value, $structure );
			$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' have the structure\\n' . print_r( $structure, true ) );

			return $this;
		}

		public function defaults( $defaults ) {
			$this->value = array_merge( $defaults, $this->value );

			return $this;
		}

		public function contains( $value ) {
			$values = func_get_args();
			foreach ( $values as $value ) {
				$condition = $this->match_true !== in_array( $value, $this->value );
				$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' contain ' . $value );
			}

			return $this;
		}

		public function has_key( $key ) {
			$keys = func_get_args();
			foreach ( $keys as $key ) {
				$condition = $this->match_true !== array_key_exists( $key, $this->value );
				$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' have the ' . $key . ' key' );
			}

			return $this;
		}

	}


	class  Object_Arg extends Arg_Object {

		public function is_set( $property ) {
			$properties = func_get_args();
			foreach ( $properties as $property ) {
				$condition = $this->match_true === isset( $this->value->$property );
				$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' have the ' . $property . 'property' );
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
			$merged = @array_merge_recursive( $arr, $structure );
			$diffed = @array_diff( $merged, $arr );
			$has_structure = count( $diffed ) === 0;

			return $has_structure;
		}
	}
