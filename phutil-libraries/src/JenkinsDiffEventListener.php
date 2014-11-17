<?php

class JenkinsDiffEventListener extends PhutilEventListener {

	const JENKINS = 'http://jenkins.ia-toki.org/buildByToken/buildWithParameters';
	const TOKEN = 'ab4bae17d3d52a3d266d7e5381b640a6';

	public function register() {
		$this->listen(ArcanistEventType::TYPE_DIFF_WASCREATED);
	}

	public function handleEvent(PhutilEvent $event) {
		$workflow = $event->getvalue('workflow');
		$diff_id = $event->getValue('diffID');
		$name = $workflow->getConfigFromAnySource('project.name');

		$url = self::JENKINS;
		$url .= '?job=' . $name;
		$url .= '&token=' . self::TOKEN;
		$url .= '&DIFF_ID=' . $diff_id;

		file_get_contents($url);
	}
}
