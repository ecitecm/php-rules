<?php

namespace PhpRules;

use PhpRules\RuleElement\Proposition,
    Exception;

class RuleSet {
    const TYPE_AND = 'and';
    const TYPE_OR = 'or';

	var $name;
	var $rules;
	var $ruleOverrides;
    var $actionQueue;
    var $evaluationType = 'and';

	public function __construct( $name ) {
		$this->name = $name;
		$this->rules = array();
		$this->ruleOverrides = array();
	}
	
	public function addRule( RuleInterface $rule ) {
		$this->rules[] = $rule;
	}
	
	public function addRuleOverride( RuleOverride $ruleOverride ) {
        $this->ruleOverrides[] = $ruleOverride;
	}
	
	public function evaluate( RuleContext $ruleContext ) {
		// Each Rule in the RuleSet is evaluated, and the 
		// results ANDed together taking account of any RuleOverrides
		$resultsForRules = array();
		// Accumulate the results of evaluating the Rules
		foreach( $this->rules as $r ) {
			$result = $r->evaluate( $ruleContext );
			$resultsForRules[ $r->getName() ] = $result;
		}
		// Apply the RuleOverrides
		foreach( $this->ruleOverrides as $ro ) {
			$result = $resultsForRules[ $ro->ruleName ];
			if( $result ) {
				$result->value = $ro->value;
			}
		}
		// Work out the final result
        if ($this->evaluationType === 'or') {
            $finalResult = array_reduce($resultsForRules, function($a, $b) { return $a || $b->value; }, false);
        } elseif ($this->evaluationType === 'and') {
            $finalResult = array_reduce($resultsForRules, function($a, $b) { return $a && $b->value; }, true);
        } else {
            throw new Exception("Invalid evaluation type");
        }

		return new Proposition( $this->name, $finalResult );
	}

    /**
     *
     */
    public function getActionQueue()
    {
        return $this->actionQueue;
    }

    /**
     *
     */
    public function setEvaluationType($type)
    {
        $valid = self::getValidEvaluationTypes();
        $type = strtolower($type);
        if (!in_array($type, $valid)) {
            throw new Exception("Unknown evaluation type, expected: " . implode('|', $valid));
        }

        $this->evaluationType = $type;
    }

    /**
     *
     */
    static public function getValidEvaluationTypes()
    {
        return array(
            self::TYPE_AND,
            self::TYPE_OR,
        );
    }
}
