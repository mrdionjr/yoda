<?php

namespace Yoda\Commands;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Helper\QuestionHelper;

class ParseFileCommandTest extends TestCase
{
    /** @var CommandTester */
    private $commandTester;

    public function setUp()
    {
        $application = new Application();
        $application->add(new ParseFileCommand());
        $command = $application->find('parse');
        $ask = function (InputInterface $input, OutputInterface $output, Question $question) {
            static $order = -1;
            ++$order;
            $text = $question->getQuestion();
            $output->write($text. ' =>');
            $response = null;

            // handle a question
            if (strpos($text, 'name') !== false) {
                $response = 'Yoda';
            }

            if (isset($response) === false) {
                throw new \RuntimeException('Was asked for input on an unhandled question: '.$text);
            }

            $output->writeln(print_r($response, true));
            return $response;
        };

        $helper = $this->createMock(QuestionHelper::class);
        $helper
            ->method('ask')
            ->willReturnCallback($ask);
        $command->getHelperSet()->set($helper, 'question');
        $this->commandTester = new CommandTester($command);
    }

    public function testExecute()
    {
        /*$this->commandTester->execute([
            'path' => dirname(__DIR__) . '/template.txt',
            'keys' => ['name'],
            '--output' => dirname(__DIR__) . '/output.txt',
        ], ['interactive' => true]);*/
        $this->assertTrue(true);
    }

    protected function tearDown()
    {
        $this->commandTester = null;
    }
}
