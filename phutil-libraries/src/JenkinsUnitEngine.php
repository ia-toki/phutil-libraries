<?php

class JenkinsUnitEngine extends ArcanistUnitTestEngine {

	public function run() {
		$result = new ArcanistUnitTestResult();
		$result->setName('Jenkins');
		$result->setResult(ArcanistUnitTestResult::RESULT_POSTPONED);
		return array($result);
	}
}
