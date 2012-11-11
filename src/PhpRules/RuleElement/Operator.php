<?php
/** 
 * Ruleelement.php
 * @author Greg Swindle <greg@swindle.net>
 * @version 0.2
 * @package Phprules
 */
namespace PhpRules\RuleElement;

use PhpRules\RuleElement,
    Exception;

 /**
 * Represents a Boolean operator or a quantifier operator.
 * @package Phprules
 */
class Operator extends RuleElement {
    /**
    * The name of the RuleElement.
    * @access private
    * @var array
    */
    private $operators;
    
    const LOGICAL_OR               = 'OR';
    const LOGICAL_AND              = 'AND';
    const LOGICAL_NOT              = 'NOT';
    const LOGICAL_XOR              = 'XOR';
    const EQUAL_TO                 = 'EQUALTO';
    const NOT_EQUAL_TO             = 'NOTEQUALTO';
    const LESS_THAN                = 'LESSTHAN';
    const LESS_THAN_OR_EQUAL_TO    = 'LESSTHANOREQUALTO';
    const GREATER_THAN             = 'GREATERTHAN';
    const GREATER_THAN_OR_EQUAL_TO = 'GREATERTHANOREQUALTO';
    
    /**
    * Constructor initializes {@link $name}, i.e., the operator.
    * @access public
    */ 
    public function __construct( $operator ) {
        $operator = strtr($operator, array(
            // Shorthands
            '=' => 'EQUALTO',
            '!=' => 'NOTEQUALTO',
            '<' => 'LESSTHAN',
            '>' => 'GREATERTHAN',
            '<=' => 'LESSTHANOREQUALTO',
            '>=' => 'GREATERTHANOREQUALTO',
        ));
        $this->operators = array( "AND", "OR", "NOT", "XOR", "EQUALTO", "NOTEQUALTO", "LESSTHAN", "GREATERTHAN", "LESSTHANOREQUALTO", "GREATERTHANOREQUALTO" );
        if( ! in_array( $operator, $this->operators ) ) {
            throw new Exception( $operator . " is not a valid operator." );
        }

        parent::__construct( $operator );
    }
    /**
     * Returns "Operator."
     * @access public
     * @return string
     */
    public function getType() {
        return "Operator";
    }
}
