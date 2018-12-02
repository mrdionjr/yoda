<?php

namespace Yoda\Commands;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ParseFileCommandTest extends TestCase
{
    public function testExecute()
    {
        $application = new Application('Yoda CLI', '0.1.0');
        $application->add(new ParseFileCommand());
        $command = $application->find('parse');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'path' => dirname(__DIR__) . '/template-test.txt',
            'keys' => ['name' => 'Kaelo']
        ]);
        $output = $commandTester->getDisplay();
        $this->assertTrue(true);
    }
}
