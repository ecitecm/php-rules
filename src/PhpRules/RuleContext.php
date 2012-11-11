<?php
/** 
 * Rulecontext.php
 * @author Greg Swindle <greg@swindle.net>
 * @version 0.2
 * @package Phprules
 */
namespace PhpRules;

use PhpRules\RuleElement\Proposition,
    PhpRules\RuleElement\Variable,
    PhpRules\RuleElement\Operator,
    Closure;

/**
 * Contains the informational context for the execution of a Rule. It represents
 * this information as a collection of RuleElements that may be Propositions or 
 * Variables but not Operators.
 * @package Phprules
 */
class RuleContext {
	public $name;
	public $elements;
	
    public function __construct( $name='' ) {
        $this->name = $name;
        // elements is a dictionary - a set of {name, value} pairs
        // The names are Proposition or Variable names and
        // the values are the Propositions or Variables themselves
        $this->elements = array();
    }

    /**
     *
     */
    public function getName()
    {
        return $this->name;
    }

    /**
    * Adds a Proposition to the array of {@link $elements}.
    * @access public
    * @param string $statement The Proposition's statement.
    * @param boolean $value Whether the Proposition is TRUE or FALSE.
    */	
    public function addProposition( $statement, $value ) {
        $this->elements[ $statement ] = new Proposition( $statement, $value );

        return $this;
    }
    /**
    * Adds a Variable to the array of {@link $elements}.
    * @access public
    * @param string $name The Variable's name.
    * @param mixed $value The Variable's value.
    */	
    public function addVariable( $name, $value ) {
        $this->elements[ $name ] = new Variable( $name, $value );

        return $this;
    }
    /**
    * Find and return a RuleElement by name, if it exists.
    * @access public
    * @param string $name The name (i.e., "key") of the RuleElement.
    * @return RuleElement
    */		
    public function findElement( $name ) {
        $result = $this->elements[ $name ];
        if ($result->value instanceof Closure) {
            // lazy load of context values, e.g. from database
            $func = $result->value;
            $result->value = $func();
        }

        return $result;
    }
    public function hasElement( $name ) {
        return isset($this->elements[ $name ]);
    }
    // prepend?
    public function append( RuleContext $ruleContext ) {
        $this->elements = $ruleContext->elements + $this->elements;

        return $this;
    }
    /**
    * Returns an infixed, readable representation of the RuleContext.
    * @access public
    * @return string
    */	
    public function __toString() {
        // we first have to loop through and evaluate all lazy loaded values
        foreach($this->elements as $elem) {
            if ($elem->value instanceof Closure) {
                $func = $elem->value;
                $elem->value = $func();
            }
        }
        return implode("\n", array_values( $this->elements ) );
    }
}
