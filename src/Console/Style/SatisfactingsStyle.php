<?php

/*
 * This file is part of the `robfrawley/satisfactings-application` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Satisfactings\Console\Style;

use Satisfactings\Utility\ReflectionUtility;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class SatisfactingsStyle
 */
class SatisfactingsStyle extends SymfonyStyle
{
    /**
     * @var \ReflectionMethod[]
     */
    private array $privateReflectionClassMethods = [];

    /**
     * @var \ReflectionProperty[]
     */
    private array $privateReflectionClassProperties = [];

    /**
     * SatisfactingsStyle constructor.
     */
    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->setupAccessiblePrivateMethodsCache();
        $this->setupAccessiblePrivatePropertiesCache();

        parent::__construct($input, $output);
    }

    /**
     * @param array|string $messages
     *
     * @return $this
     */
    public function block($messages, ?string $type = null, ?string $style = null, string $prefix = ' ', bool $padding = false, bool $escape = true): self
    {
        parent::block($messages, $type, $style, $prefix, $padding, $escape);

        return $this;
    }

    /**
     * @return $this
     */
    public function title(string $message): self
    {
        parent::title($message);

        return $this;
    }

    /**
     * @param mixed ...$replacements
     *
     * @return $this
     */
    public function compileTitle(string $message, ...$replacements): self
    {
        return $this->title(sprintf($message, ...$replacements));
    }

    /**
     * @return $this
     */
    public function section(string $message): self
    {
        parent::section($message);

        return $this;
    }

    /**
     * @param mixed ...$replacements
     *
     * @return $this
     */
    public function compileSection(string $message, ...$replacements): self
    {
        return $this->section(sprintf($message, ...$replacements));
    }

    /**
     * @return $this
     */
    public function listing(array $elements): self
    {
        parent::listing($elements);

        return $this;
    }

    /**
     * @param array ...$elementInstructions
     *
     * @return $this
     */
    public function compileListing(array ...$elementInstructions): self
    {
        return $this->listing(
            $this->compileMessage(...$elementInstructions)
        );
    }

    /**
     * @param array|string $message
     *
     * @return $this
     */
    public function text($message): self
    {
        parent::text($message);

        return $this;
    }

    /**
     * @param string|array ...$messageInstructions
     *
     * @return $this
     */
    public function compileText(...$messageInstructions): self
    {
        return $this->text(
            $this->compileMessage(...$messageInstructions)
        );
    }

    /**
     * @param array|string $message
     *
     * @return $this
     */
    public function comment($message): self
    {
        parent::comment($message);

        return $this;
    }

    /**
     * @param string|array ...$messageInstructions
     *
     * @return $this
     */
    public function compileComment(...$messageInstructions): self
    {
        return $this->comment(
            $this->compileMessage(...$messageInstructions)
        );
    }

    /**
     * @param array|string $message
     *
     * @return $this
     */
    public function success($message): self
    {
        parent::success($message);

        return $this;
    }

    /**
     * @param string|array ...$messageInstructions
     *
     * @return $this
     */
    public function compileSuccess(...$messageInstructions): self
    {
        return $this->success(
            $this->compileMessage(...$messageInstructions)
        );
    }

    /**
     * @param array|string $message
     *
     * @return $this
     */
    public function error($message): self
    {
        parent::error($message);

        return $this;
    }

    /**
     * @param string|array ...$messageInstructions
     *
     * @return $this
     */
    public function compileError(...$messageInstructions): self
    {
        return $this->error(
            $this->compileMessage(...$messageInstructions)
        );
    }

    /**
     * @param string|array $message
     */
    public function errorExit($message, int $code = 1): int
    {
        parent::error($message);

        return $code;
    }

    /**
     * @param string|array ...$messageInstructions
     */
    public function compileErrorExit(...$messageInstructions): int
    {
        return $this->errorExit(
            $this->compileMessage(...$messageInstructions)
        );
    }

    /**
     * @param array|string $message
     *
     * @return $this
     */
    public function warning($message): self
    {
        parent::warning($message);

        return $this;
    }

    /**
     * @param string|array ...$messageInstructions
     *
     * @return $this
     */
    public function compileWarning(...$messageInstructions): self
    {
        return $this->warning(
            $this->compileMessage(...$messageInstructions)
        );
    }

    /**
     * @param array|string $message
     *
     * @return $this
     */
    public function note($message): self
    {
        parent::note($message);

        return $this;
    }

    /**
     * @param string|array ...$messageInstructions
     *
     * @return $this
     */
    public function compileNote(...$messageInstructions): self
    {
        return $this->note(
            $this->compileMessage(...$messageInstructions)
        );
    }

    /**
     * @param array|string $message
     *
     * @return $this
     */
    public function caution($message): self
    {
        parent::caution($message);

        return $this;
    }

    /**
     * @param string|array ...$messageInstructions
     *
     * @return $this
     */
    public function compileCaution(...$messageInstructions): self
    {
        return $this->caution(
            $this->compileMessage(...$messageInstructions)
        );
    }

    /**
     * @return $this
     */
    public function table(array $headers, array $rows): self
    {
        parent::table($headers, $rows);

        return $this;
    }

    /**
     * @return $this
     */
    public function horizontalTable(array $headers, array $rows): self
    {
        parent::horizontalTable($headers, $rows);

        return $this;
    }

    /**
     * @param mixed ...$list
     *
     * @return $this
     */
    public function definitionList(...$list): self
    {
        parent::definitionList(...$list);

        return $this;
    }

    /**
     * @return $this
     */
    public function progressStart(int $max = 0): self
    {
        parent::progressStart($max);

        return $this;
    }

    /**
     * @return $this
     */
    public function progressAdvance(int $step = 1): self
    {
        parent::progressAdvance($step);

        return $this;
    }

    /**
     * @return $this
     */
    public function progressFinish(): self
    {
        parent::progressFinish();

        return $this;
    }

    /**
     * @param iterable|string $messages
     *
     * @return $this
     */
    public function writeln($messages, int $type = self::OUTPUT_NORMAL): self
    {
        parent::writeln($messages, $type);

        return $this;
    }

    /**
     * @param iterable|string $messages
     *
     * @return $this
     */
    public function write($messages, bool $newline = false, int $type = self::OUTPUT_NORMAL): self
    {
        parent::write($messages, $newline, $type);

        return $this;
    }

    /**
     * @return $this
     */
    public function newLine(int $count = 1): self
    {
        parent::newLine($count);

        return $this;
    }

    /**
     * @return $this
     */
    public function getErrorStyle(): self
    {
        return new self($this->getInput(), $this->getErrorOutput());
    }

    protected function getInput(): InputInterface
    {
        return $this->handleProxyMethodForReflectionPropertyValue(__FUNCTION__);
    }

    protected function getQuestionHelper(): ?QuestionHelper
    {
        return $this->handleProxyMethodForReflectionPropertyValue(__FUNCTION__);
    }

    protected function getProgressBar(): ?ProgressBar
    {
        return $this->handleProxyMethodForReflectionPropertyValue(__FUNCTION__);
    }

    protected function getLineLength(): int
    {
        return $this->handleProxyMethodForReflectionPropertyValue(__FUNCTION__);
    }

    protected function getBufferedOutput(): BufferedOutput
    {
        return $this->handleProxyMethodForReflectionPropertyValue(__FUNCTION__);
    }

    protected function runGetProgressBar(): ProgressBar
    {
        return $this->handleProxyMethodForReflectionMethodInvocation(__FUNCTION__);
    }

    /**
     * @return $this
     */
    protected function runAutoPrependBlock(): self
    {
        $this->handleProxyMethodForReflectionMethodInvocation(__FUNCTION__);

        return $this;
    }

    /**
     * @return $this
     */
    protected function runAutoPrependText(): self
    {
        $this->handleProxyMethodForReflectionMethodInvocation(__FUNCTION__);

        return $this;
    }

    /**
     * @return $this
     */
    protected function runWriteBuffer(): self
    {
        $this->handleProxyMethodForReflectionMethodInvocation(__FUNCTION__);

        return $this;
    }

    /**
     * @param mixed ...$messageInstructions
     */
    private function compileMessage(...$messageInstructions): array
    {
        return array_map(function ($instructions): string {
            return \is_array($instructions) ? sprintf(...$instructions) : $instructions;
        }, $messageInstructions);
    }

    private function setupAccessiblePrivateMethodsCache(): void
    {
        $this->setupAccessiblePrivateItemCache(__FUNCTION__);
    }

    private function setupAccessiblePrivatePropertiesCache(): void
    {
        $this->setupAccessiblePrivateItemCache(__FUNCTION__);
    }

    private function setupAccessiblePrivateItemCache(string $type): void
    {
        $t = preg_replace('/^setupAccessiblePrivate([A-Z][a-z]+)Cache$/', '$1', $type);
        $m = sprintf('getParentReflectionClass%s', $t);
        $p = sprintf('privateReflectionClass%s', $t);

        foreach (\call_user_func([ReflectionUtility::class, $m], $this, ReflectionUtility::IS_PRIVATE) as $r) {
            $r->setAccessible(true);
            $this->{$p}[mb_strtolower($r->getName())] = $r;
        }
    }

    /**
     * @return mixed
     */
    private function handleProxyMethodForReflectionPropertyValue(string $name)
    {
        return $this
            ->privateReflectionClassProperties[mb_strtolower(mb_substr($name, 3))]
            ->getValue($this);
    }

    /**
     * @param mixed ...$parameters
     *
     * @return mixed
     */
    private function handleProxyMethodForReflectionMethodInvocation(string $name, ...$parameters)
    {
        return $this
            ->privateReflectionClassMethods[mb_strtolower(mb_substr($name, 3))]
            ->invoke($this, ...$parameters);
    }
}
