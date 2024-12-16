<?php

namespace DaveLiddament\PhpstanPhpLanguageExtensions\Build\PHPStan\Rules;

use DaveLiddament\PhpstanPhpLanguageExtensions\Helpers\Assert;
use Nette\Neon\Neon;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/** @implements Rule<InClassNode> */
final class CheckRuleIsInExtension implements Rule
{
    /** @var list<string> */
    private array $classes;

    public function __construct()
    {
        $file = Neon::decodeFile(__DIR__.'/../../../extension.neon');

        if (!is_array($file)) {
            throw new \Exception('Expecting neon file to be parseable');
        }

        $services = $file['services'] ?? [];
        Assert::assertArray($services);

        $classes = [];
        foreach ($services as $service) {
            Assert::assertArray($service);
            $class = $service['class'] ?? null;
            if (null === $class) {
                continue;
            }

            Assert::assertString($class);
            $classes[] = $class;
        }

        $this->classes = $classes;
    }

    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $classReflection = $scope->getClassReflection();
        if (null === $classReflection) {
            return [];
        }

        if (!$classReflection->isSubclassOf(Rule::class)) {
            return [];
        }

        if ($classReflection->isAbstract()) {
            return [];
        }

        $className = $classReflection->getName();

        if (str_starts_with(haystack: $className, needle: 'DaveLiddament\PhpstanPhpLanguageExtensions\Build')) {
            return [];
        }

        if (in_array($className, $this->classes)) {
            return [];
        }

        return [
            RuleErrorBuilder::message("Rule [$className] not in extension.neon.")->identifier('phpstanExtensionLibrary.misconfigured')->build(),
        ];
    }
}
