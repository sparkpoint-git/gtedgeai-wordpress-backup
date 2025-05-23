<?php
/**
 * The Forminator_Calculator_Symbol_Operator_Abstract class.
 *
 * @package Forminator
 */

/**
 * This class is the base class for all symbols that are of the type "(binary) operator".
 * The textual representation of an operator consists of a single char that is not a letter.
 * It is worth noting that a operator has the same power as a function with two parameters.
 * Operators are always binary. To mimic a unary operator you might want to create a function
 * that accepts one parameter.
 */
abstract class Forminator_Calculator_Symbol_Operator_Abstract extends Forminator_Calculator_Symbol_Abstract {

	/**
	 * The operator precedence determines which operators to perform first
	 * in order to evaluate a given term.
	 * You are supposed to overwrite this constant in the concrete constant class.
	 * Take a look at other operator classes to see the precedences of the predefined operators.
	 * 0: default, > 0: higher, < 0: lower
	 *
	 * @var int
	 */
	protected $precedence = 0;

	/**
	 * Usually operators are binary, they operate on two operands (numbers).
	 * But some can operate on one operand (number). The operand of a unary
	 * operator is always positioned after the operator (=prefix notation).
	 * Good example: "-1" Bad Example: "1-"
	 * If you want to create a unary operator that operates on the left
	 * operand, you should use a function instead. Functions with one
	 * parameter execute unary operations in functional notation.
	 * Notice: Operators can be unary AND binary (but this is a rare case)
	 *
	 * @var bool
	 */
	protected $operates_unary = false;

	/**
	 * Usually operators are binary, they operate on two operands (numbers).
	 * Notice: Operators can be unary AND binary (but this is a rare case)
	 *
	 * @var bool
	 */
	protected $operates_binary = true;

	/**
	 * This method is called when the operator has to execute a binary operation on two numbers.
	 * The arguments will always be of type int or float. They will never be null.
	 * Overwrite this method in the concrete operator class.
	 * If this class does NOT return a value of type int or float,
	 * an exception will be thrown.
	 *
	 * @param  int|float $left_number  The number that stand left to the operator.
	 * @param  int|float $right_number The number that stands right to the operator.
	 *
	 * @return int|float
	 */
	abstract public function operate( $left_number, $right_number );

	/**
	 * Getter for the self::PRECEDENCE constant.
	 * This is just a convenient helper that makes accessing
	 * the constant a little bit more elegant.
	 *
	 * @return int
	 */
	public function get_precedence() {
		return $this->precedence;
	}

	/**
	 * Getter for the self::OPERATES_UNARY constant
	 * This is just a convenient helper that makes accessing
	 * the constant a little bit more elegant.
	 *
	 * @return bool
	 */
	public function get_operates_unary() {
		return $this->operates_unary;
	}

	/**
	 * Getter for the self::OPERATES_BINARY constant
	 * This is just a convenient helper that makes accessing
	 * the constant a little bit more elegant.
	 *
	 * @return bool
	 */
	public function get_operates_binary() {
		return $this->operates_binary;
	}
}