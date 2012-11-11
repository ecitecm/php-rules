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
 * Represents a Proposition in formal logic, a statement with at truth value.
 * @package Phprules
 */
class Proposition extends RuleElement {
    /**
    * The Boolean truth value of the Proposition.
    * @access public
    * @var boolean
    */
    public $value;
    /**
    * Constructor initializes {@link $name}, and the {@link $value}.
    * @access public
    */ 
    public function __construct( $name, $truthValue ) {
        $this->value = $truthValue;
        parent::__construct( $name );
    }
    /**
    * Returns &quot;Proposition.&quot;
    * @access public
    * @return string
    */ 
    public function getType() {
        return "Proposition";
    }
    /**
    * Returns a human-readable statement and value.
    * @access public
    * @return string
    */
    public function __toString() {
        $truthValue = "FALSE";
        if( $this->value == true ) {
            $truthValue = "TRUE";
        }
        return "Proposition statement = " . $this->name . ", value = " . $truthValue;
    }
    /**
    * Performs a Boolean AND operation on another {@link Proposition}
    * @access public
    * @param Proposition $proposition
    * @return Proposition
    */ 
    public function logicalAnd( Proposition $proposition ) {
        $resultName  = "( " . $this->name . " AND " . $proposition->getName() . " )";
        $resultValue = ( $this->value and $proposition->value );
        return new self( $resultName, $resultValue );
    }
    /**
    * Performs a Boolean OR operation on another {@link Proposition}
    * @access public
    * @param Proposition $proposition 
    * @return Proposition
    */ 
    public function logicalOr( Proposition $proposition ) {
        $resultName  = "( " . $this->name . " OR " . $proposition->getName() . " )";
        $resultValue = ( $this->value or $proposition->value );
        return new self( $resultName, $resultValue );
    }
    /**
    * Performs a Boolean NOT operation its own value
    * @access public
    * @return Proposition
    */  
    public function logicalNot() {
        $resultName  = "( NOT " . $this->name . " )";
        $resultValue = ( !$this->value );
        return new self( $resultName, $resultValue );
    }
    /**
    * Performs a Boolean XOR operation on another {@link Proposition}
    * @access public
    * @param Proposition $proposition 
    * @return Proposition
    */ 
    public function logicalXor( Proposition $proposition ) {
        $resultName  = "( " . $this->name . " XOR " . $proposition->getName() . " )";
        $resultValue = ( $this->value xor $proposition->value );
        return new self( $resultName, $resultValue );
    }
}
