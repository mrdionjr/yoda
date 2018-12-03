<?php

namespace Yoda\Commands;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ParseFileCommandTest extends TestCase
{
    /** @var CommandTester */
    private $commandTester;

    public function setUp()
    {
        $application = new Application();
        $application->add(new ParseFileCommand());
        $command = $application->find('parse');
        $this->commandTester = new CommandTester($command);
    }

    public function testExecute()
    {
        $this->commandTester->execute([
            'path' => dirname(__DIR__) . '/template.txt',
            '--dump' => true,
            '--output' => dirname(__DIR__),
            'keys' => ['name']
        ], ['interactive' => ['name' => 'Yoda']]);
        $output = $this->commandTester->getOutput();
        $this->assertContains('Yoda', $output);
    }

    protected function tearDown()
    {
        $this->commandTester = null;
    }
}
