<?php 
/** 
 * ActivityRule.php
 * @author Greg Swindle <greg@swindle.net>
 * @version 0.2
 * @package Phprules
 */
namespace PhpRules;

/**
 * An ActivityRule represents a type of Rule that automatically executes an 
 * activity when it evaluates to TRUE.
 */
abstract class ActivityRule extends Rule {
	
	abstract public function execute();

    /**
     * Evaluates a RuleContext. The RuleContext contains Propositions and Variables that have
     * specific values. To apply the context, simply copy these values 
     * into the corresponding Propositions and Variables in the Rule. If the result of 
     * evaluation is TRUE, then the activity is executed.
     * @access public
     * @param RuleContext $ruleContext
     * @return Proposition
     */		
	public function evaluate( RuleContext $ruleContext ) {
		$proposition = parent::evaluate( $ruleContext );
		if ($proposition->value == TRUE){
			$this->execute();
		}
		return $proposition;
	}
}
