<?php

final class CheckstyleLinter extends ArcanistFutureLinter {

    private static $repo = '.ivy2/cache';
    private static $conf = '../iatoki-commons/checkstyle/config.xml';
    private static $jars = array(
        'com.puppycrawl.tools/checkstyle/jars/checkstyle-6.1.jar',
        'antlr/antlr/jars/antlr-2.7.7.jar',
        'com.google.guava/guava/bundles/guava-18.0.jar',
        'commons-beanutils/commons-beanutils-core/jars/commons-beanutils-core-1.8.3.jar',
        'commons-cli/commons-cli/jars/commons-cli-1.2.jar',
        'org.antlr/antlr4-runtime/jars/antlr4-runtime-4.3.jar',
        'commons-logging/commons-logging/jars/commons-logging-1.1.3.jar'
    );

    public function getLinterName() {
        return 'Checkstyle';
    }

    public function getLinterConfigurationName() {
	    return 'checkstyle';
    }

    public function getInfoURI() {
        return 'http://checkstyle.sourceforge.net/';
    }

    public function getInfoDescription() {
        return pht(
            'Checkstyle is a development tool to help programmers write Java code that adheres to a coding standard. ' .
            'It automates the process of checking Java code to spare humans of this boring (but important) task.'
        );
    }

    protected function buildFutures(array $paths) {
        $home = exec('echo ~');

        $command = self::getCommand($home);

        $futures = array();
        foreach($paths as $path) {
            $futures[$path] = new ExecFuture($command . ' -c ' . self::$conf . ' ' . $path);
        }

        return $futures;
    }

    protected function resolveFuture($path, Future $future) {
        list($err, $stdout, $stderr) = $future->resolve();

        $lines = phutil_split_lines($stdout, false);
        foreach($lines as $line) {
            $this->parseLine($path, $line);
        }
    }

    private function parseLine($path, $line) {
        $matches = array();
        $pattern = '' .
            '/^<error line="(\d+)" ' .
            '(?:column="(\d+)" )?' .
            'severity="(\S+)" ' .
            'message="([^"]*)" ' .
            'source="com\.puppycrawl\.tools\.checkstyle\.checks\.(\S+)Check"\/>$/';

        if (!preg_match($pattern, $line, $matches)) {
            return;
        }

        $message = new ArcanistLintMessage();

        $message->setPath($path);
        $message->setLine($matches[1]);
        if (!empty($matches[2]))
            $message->setChar($matches[2]);
        $message->setCode($matches[5]);
        $message->setDescription(html_entity_decode($matches[4], ENT_QUOTES | ENT_HTML5));
        $message->setSeverity($matches[3]);

        $this->addLintMessage($message);
    }

    private static function getCommand($home) {
        $command = 'java -cp .';
        foreach (self::$jars as $jar) {
            $command .= ':' . $home . '/' . self::$repo . '/' . $jar;
        }

        $command .= ' com.puppycrawl.tools.checkstyle.Main -f xml';
        return $command;
    }
}
