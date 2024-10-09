<?php

namespace App\Shared\Infrastructure\CommandLine;

use Assert\Assertion;
use Assert\AssertionFailedException;
use LogicException;
use SimpleXMLElement;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

#[AsCommand(name: 'global:coverage-checker', description: 'Script to ensure that the phpunit coverage is valid')]
class CoverageCheckerCommandLine extends Command
{
    private const CLOVER_FILE = 'tests/coverage/coverage.clover.xml';
    private const COVERAGE = 20.00; // 20% minimum coverage this is only for this task in prod api should be max
    protected function configure(): void
    {
        $this
            ->addArgument(name: 'cloverFile', mode: InputArgument::OPTIONAL, default: self::CLOVER_FILE)
            ->addArgument(name: 'coverage', mode: InputArgument::OPTIONAL, default: self::COVERAGE);
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$output instanceof ConsoleOutputInterface) {
            throw new LogicException('This command accepts only an instance of "ConsoleOutputInterface".');
        }
        try {
            $cloverXml = $this->fileOrFail($input);
            $percentage = $this->percentage($input);
            $coverage = round($this->retrieveCoverageFrom($cloverXml), 2);
            if ($coverage < $percentage) {
                $output->writeln(
                    sprintf(
                        "<error>The current coverage %s is less than required %s</error>",
                        $coverage,
                        $percentage,
                    ),
                );
                return Command::FAILURE;
            }
            $output->writeln(
                sprintf('<fg=black;bg=green> Code coverage is valid, with %.2f%% coverage </>', $coverage),
            );
        } catch (Throwable $throwable) {
            $output->writeln(sprintf('<error>%s</error>', $throwable->getMessage()));
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
    /** @throws AssertionFailedException */
    private function fileOrFail(InputInterface $input): SimpleXMLElement
    {
        $cloverFile = $input->getArgument('cloverFile');
        Assertion::file($cloverFile);
        $xmlFile = simplexml_load_file($cloverFile);
        Assertion::isInstanceOf($xmlFile, SimpleXMLElement::class);
        return $xmlFile;
    }
    private function percentage(InputInterface $input): mixed
    {
        $percentage = $input->getArgument('coverage');
        return min(100, max(0, round($percentage, 2)));
    }
    private function retrieveCoverageFrom(SimpleXMLElement $xml): float|int
    {
        $total = $this->metrics($xml, '');
        $checked = $this->metrics($xml, 'covered');
        return (($total === $checked) ? 1 : $checked / $total) * 100;
    }
    private function metrics(SimpleXMLElement $xml, string $key): int
    {
        /** @phpstan-ignore-next-line */
        return array_sum(array_map('intval', $xml->xpath(sprintf('.//metrics/@%selements', $key))));
    }
}
