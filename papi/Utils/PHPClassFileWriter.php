<?php

declare(strict_types=1);

namespace papi\Utils;

use RuntimeException;

/**
 * Creates PHP Class files based on provided data
 */
class PHPClassFileWriter
{
    private string $name;

    private string $dir;

    private string $namespace;

    private string $extends = '';

    private string $implements = '';

    private array $variables = [];

    private array $functions = [];

    private array $imports = [];

    public function __construct(
        string $name,
        string $namespace,
        string $dir,
        ?string $extends = null,
        ?string $implements = null
    ) {
        $this->name = $name;
        $this->namespace = $namespace;
        $this->dir = $dir;
        if ($extends !== null) {
            $this->extends = "extends $extends";
        }
        if ($implements !== null) {
            $this->implements = "implements $implements";
        }
    }

    /**
     * Write contained data into a PHP Class file
     */
    public function write(): void
    {
        $path = $this->dir."/$this->name.php";
        if (file_exists($path)) {
            throw new RuntimeException("File $path already exists");
        }
        if (! is_dir($this->dir)) {
            if (! mkdir($concurrentDirectory = $this->dir, 0777, true) && ! is_dir($concurrentDirectory)) {
                throw new RuntimeException("Directory $concurrentDirectory was not created");
            }
        }
        file_put_contents($this->dir."/$this->name.php", $this->getTemplate());
    }

    /**
     * Get formatted PHP Class code
     *
     * @return string
     */
    private function getTemplate(): string
    {
        $vars = $this->getVars();
        $functions = $this->getFunctions();
        $imports = $this->getImports();
        $className = rtrim("class $this->name $this->extends $this->implements");

        return "<?php
declare(strict_types=1);

namespace $this->namespace;

$imports

$className
{
$vars$functions
}
";
    }

    /**
     * Get formatted class variables
     *
     * @return string
     */
    private function getVars(): string
    {
        if (! $this->variables) {
            return '';
        }
        $template = '';
        foreach ($this->variables as $key => $variable) {
            $template .= "    $variable;\n";
            if (array_key_last($this->variables) !== $key) {
                $template .= "\n";
            }
        }

        return "$template\n";
    }

    /**
     * Get formatted class methods
     *
     * @return string
     */
    private function getFunctions(): string
    {
        $template = '';
        foreach ($this->functions as $key => $function) {
            $template .= $function;
            if (array_key_last($this->functions) !== $key) {
                $template .= "\n\n";
            }
        }

        return $template;
    }

    /**
     * Get formatted class use statements
     *
     * @return string
     */
    private function getImports(): string
    {
        $template = "";
        foreach ($this->imports as $key => $import) {
            $template .= "use $import;";
            if (array_key_last($this->imports) !== $key) {
                $template .= "\n";
            }
        }

        return $template;
    }

    /**
     * Add class method
     *
     * @param string $access
     * @param string $returnType
     * @param string $name
     * @param string $content
     * @param array  $args
     */
    public function addFunction(
        string $access,
        string $returnType,
        string $name,
        string $content,
        array $args = []
    ): void {
        $text = "    $access function $name(";
        foreach ($args as $key => $arg) {
            $text .= $arg;
            if (array_key_last($args) !== $key) {
                $text .= ',';
            }
        }
        $text .= "): $returnType
    {
        $content
    }";
        $this->functions[] = $text;
    }

    /**
     * Add class variable
     *
     * @param string     $access
     * @param string     $type
     * @param string     $name
     * @param mixed|null $defaultValue
     */
    public function addVariable(
        string $access,
        string $type,
        string $name,
        mixed $defaultValue = null
    ): void {
        $var = "$access $type $$name";
        if ($defaultValue !== null) {
            if (is_string($defaultValue)) {
                $defaultValue = "'$defaultValue'";
            }
            $var .= " = $defaultValue";
        }
        $this->variables[] = $var;
    }

    /**
     * Add use statement to class
     *
     * @param string $path
     */
    public function addImport(string $path): void
    {
        $this->imports[] = $path;
    }
}
