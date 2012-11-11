<?php
/** 
 * Ruleelement.php
 * @author Greg Swindle <greg@swindle.net>
 * @version 0.2
 * @package Phprules
 */
 
namespace PhpRules\RuleElement;

use PhpRules\RuleElement;

 /**
 * A symbol that represents a value.
 * @package Phprules
 */
class Variable extends RuleElement {
    public $value;
    /**
    * Constructor initializes {@link $name}, and the {@link $value}.
    * @access public
    */     
    public function __construct( $name, $value ) {
        $this->value = $value;
        parent::__construct( $name );
    }
    /**
    * Returns &quot;Variable.&quot;
    * @access public
    * @return string
    */     
    public function getType() {
        return "Variable";
    }
    /**
    * Returns a human-readable statement and value.
    * @access public
    * @return string
    */    
    public function __toString() {
        return "Variable name = " . $this->name . ", value = " . $this->value;
    }
    /**
    * Determines whether another Variable's value is equal to its own value.
    * @public
    * @param Variable $variable
    * @return Proposition
    */    
    public function equalTo( Variable $variable ) {
        $statement = "( " . $this->name . " == " . $variable->getName() . " )";
        $truthValue = ( $this->value == $variable->value );
        return new Proposition( $statement, $truthValue );
    }
    /**
    * Determines whether another Variable's value is <em>not</em> equal to its own value.
    * @public
    * @param Variable $variable
    * @return Proposition
    */    
    public function notEqualTo( Variable $variable ) {
        $statement = "( " . $this->name . " != " . $variable->getName() . " )";
        $truthValue = ( $this->value != $variable->value );
        return new Proposition( $statement, $truthValue );
    }
    /**
    * Determines whether another Variable's value is less than to its own value.
    * @public
    * @param Variable $variable
    * @return Proposition
    */    
    public function lessThan( Variable $variable ) {
        $statement = "( " . $this->name . " < " . $variable->getName() . " )";
        $truthValue = ( $this->value < $variable->value );
        return new Proposition( $statement, $truthValue );
    }
    /**
    * Determines whether another Variable's value is less than or equal to to its own value.
    * @public
    * @param Variable $variable
    * @return Proposition
    */   
    public function lessThanOrEqualTo( Variable $variable ) {
        $statement = "( " . $this->name . " <= " . $variable->getName() . " )";
        $truthValue = ( $this->value <= $variable->value );
        return new Proposition( $statement, $truthValue );
    }
    /**
    * Determines whether another Variable's value is greater than to its own value.
    * @public
    * @param Variable $variable
    * @return Proposition
    */
    public function greaterThan( Variable $variable ) {
        $statement = "( " . $this->name . " > " . $variable->getName() . " )";
        $truthValue = ( $this->value > $variable->value );
        return new Proposition( $statement, $truthValue );
    }
    /**
     * Determines whether another Variable's value is greater than or equal to its own value.
     * @public
     * @param Variable $variable
     * @return Proposition
     */    
    public function greaterThanOrEqualTo( Variable $variable ) {
        $statement = "( " . $this->name . " >= " . $variable->getName() . " )";
        $truthValue = ( $this->value >= $variable->value );
        return new Proposition( $statement, $truthValue );
    }
}

