<?php

namespace App\RecordReplay;

use ReflectionClass;
use ReflectionMethod;

class Generator
{

    const string CLASS_BASENAME = 'RecordReplay_';

    public function __construct(
        private readonly RecordReplay $recordReplay,
    )
    {
    }

    function createProxy(object $target): object
    {
        $methods = $this->getTargetMethods($target);

        $methodsCode = $this->getMethodsCode($methods);

        $proxyClassName = $this->getProxyClassName($target);

        if (!class_exists($proxyClassName)) {
            $classCode = $this->getClassCode($proxyClassName, $target, $methodsCode);
            eval($classCode);
        }

        return new $proxyClassName($target, $this->recordReplay);
    }

    private function getTargetMethods(object $target): array
    {
        $reflection = new ReflectionClass($target);
        $methods = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->getName() === "__construct") {
                continue;
            }
            $methods[$method->getName()] = $method;
        }
        return $methods;
    }

    private function getMethodsCode(array $methods): string
    {
        $methodsCode = '';
        foreach ($methods as $method) {
            $methodName = $method->getName();
            $parameters = [];
            $arguments = [];

            foreach ($method->getParameters() as $param) {
                $paramString = '$' . $param->getName();
                if ($param->hasType()) {
                    $type = $param->getType();
                    $paramString = ($type->allowsNull() ? '?' : '') . $type . ' ' . $paramString;
                }
                if ($param->isDefaultValueAvailable()) {
                    $paramString .= ' = ' . var_export($param->getDefaultValue(), true);
                }

                $parameters[] = $paramString;
                $arguments[] = '$' . $param->getName();
            }

            $parametersString = implode(', ', $parameters);
            $argumentsString = implode(', ', $arguments);

            $returnType = '';
            $returnStatement = 'return';
            if ($method->hasReturnType()) {
                $type = $method->getReturnType();
                $returnType = ': ' . ($type->allowsNull() ? '?' : '') . $type;

                if ((string)$type === 'void') {
                    $returnStatement = '';
                }
            }

            $methodsCode .= "
                public function $methodName($parametersString)$returnType {
                    $returnStatement \$this->call('$methodName', [$argumentsString]);
                }
            ";
        }
        return $methodsCode;
    }

    private function getProxyClassName(object $target): string
    {
        return self::CLASS_BASENAME . (new ReflectionClass($target))->getShortName() . spl_object_id($target);
    }

    private function getClassCode(string $proxyClassName, object $target, string $methodsCode): string
    {
        return '
        class ' . $proxyClassName . ' extends ' . $target::class . ' {
            use App\RecordReplay\RecordReplayTrait;

            public function __construct(private readonly mixed $target,private readonly App\RecordReplay\RecordReplay $recordReplay) {

            }

            ' . $methodsCode . '
        }
    ';

    }


}
