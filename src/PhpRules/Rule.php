<?php
/** 
 * Rule.php
 * @author Greg Swindle <greg@swindle.net>
 * @version 0.2
 * @package Phprules
 */
namespace PhpRules;

use PhpRules\RuleElement\Proposition,
    PhpRules\RuleElement\Variable,
    PhpRules\RuleElement\Operator;

/**
 * A Rule is a constraint on the operation of business systems. They:
 * <ol>
 * <li>Constrain business strucuture.</li>
 * <li>Constrain busines operations, i.e., they determine the sequence of actions in business workflows.</li>
 * </ol>
 * @package Phprules
 */
class Rule implements RuleInterface
{
    /**
    * The name of the Rule.
    * @access public
    * @var string
    */	
    public $name;
    /**
    * The cannonical name of the Rule, i.e., the unique means of identifying 
    * the Rule. Namespace notation is recommended.
    * @access public
    * @var string
    */		
    public $cannonicalName;
    /**
    * Human-readable description of the Rule's purpose.
    * @access public
    * @var string
    */		
    public $description;
    /**
    * The RuleElements that comprise the Rule.
    * @access public
    * @var array
    * @see RuleElement
    */		
    public $elements;
    /**
    * A stack data structure used during Rule evaluation.
    * @access private
    * @var array
    */	
    private $stack;
    /**
    * Constructor initializes {@link $name{ and the array of {@link $elements}.
    * @access public
    */	
    public function __construct( $name='' ) {
        $this->name = $name;
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
     *
     */
    public function add( RuleElement $element )
    {
        $this->elements[] = $element;
    }
    /**
    * Adds a Proposition to the array of {@link $elements}.
    * @access public
    * @param string $name The Proposition's statement.
    * @param boolean $truthValue Whether the Proposition is TRUE or FALSE.
    */		
    public function addProposition( $name, $truthValue ) {
        $this->elements[] = new Proposition( $name, $truthValue );
    }
    /**
    * Adds a Variable to the array of {@link $elements}.
    * @access public
    * @param string $name The Variable's name.
    * @param mixed $value The Variable's value.
    */		
    public function addVariable( $name, $value ) {
        $this->elements[] = new Variable( $name, $value );
    }
    /**
    * Adds an Operator to the array of {@link $elements}.
    * @access public
    * @param string $operator The Boolean or quantifier operator.
    */		
    public function addOperator( $operator ) {
        $this->elements[] = new Operator( $operator );
    }
    /**
    * Processes RuleElements for RuleContext evaluation.
    * @access private
    * @return RuleElement
    */	
    public function evaluate( RuleContext $ruleContext ) {
        $this->stack = array();
        $iterator = new ContextAwareIterator( $this->elements, $ruleContext );
        foreach( $iterator as $e ) {
            switch ( $e->getType() ) {
                case 'Proposition':
                case 'Variable':
                    $this->stack[] = $e;
                    break;
                case 'Operator':
                    $this->processOperator( $e );
                    break;
                default:
                    throw new UnexpectedValueException( "Expected Proposition, Variable or Operator.  Got : " . $e->getType() );
            }
        }
        return array_pop( $this->stack );
    }

    /**
    * Driver method for processing Operators for RuleContext evaluation.
    * @access private
    */	
    private function processOperator( Operator $operator ) {
        $func = false;
        $lhs = true;

        $operators = array(
            Operator::LOGICAL_AND => 'logicalAnd',
            Operator::LOGICAL_OR => 'logicalOr',
            Operator::LOGICAL_XOR => 'logicalXor',
            Operator::LOGICAL_NOT => array('func'=>'logicalNot', 'lhs'=>false),
            Operator::EQUAL_TO => 'equalTo',
            Operator::NOT_EQUAL_TO => 'notEqualTo',
            Operator::LESS_THAN => 'lessThan',
            Operator::GREATER_THAN => 'greaterThan',
            Operator::LESS_THAN_OR_EQUAL_TO => 'lessThanOrEqualTo',
            Operator::GREATER_THAN_OR_EQUAL_TO => 'greaterThanOrEqualTo',
        );

        if (isset($operators[$operator->name])) {
            $options = $operators[$operator->name];
            $options = is_array($options) ? $options : array('func'=>$options, 'lhs'=>true);

            $rhs = array_pop( $this->stack );
            $func = $options['func'];
            if ($options['lhs']) {
                $lhs = array_pop( $this->stack );
                $this->stack[] = $rhs->$func( $lhs );
            } else {
                $this->stack[] = $rhs->$func();
            }
        }
    }
    /**
    * Returns an infixed, readable representation of the Rule.
    * @access public
    * @return string
    */		
    public function __toString() {
        $result = array($this->name);
        foreach( $this->elements as $e ) {
            $result[] = (string) $e;
        }
        return implode("\n", $result);
    }
}
