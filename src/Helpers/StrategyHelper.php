<?php

namespace EscolaLms\Jitsi\Helpers;

class StrategyHelper
{
    private string $namespace;

    public function __construct(string $baseStrategyName)
    {
        $this->setNamespace($baseStrategyName);
    }

    /**
     * This method used strategy pattern and execute method given in the parameters
     * Strategy dir it must contain minimum to file: BaseStrategy contain in pattern {{parentDir}}Strategy
     * in localization ?/Strategies/{{parentDir}} and strategy class in the same localization
     *
     * @param string $className
     * @param string $baseStrategyName
     * @param string $method
     * @param ...$params
     * @return mixed|null
     */
    public static function useStrategyPattern(
        string $className,
        string $baseStrategyName,
        string $method,
        ...$params
    ) {
        $strategyHelper = new StrategyHelper($baseStrategyName);
        $class = $strategyHelper->namespace . '\\' . $className;
        $baseStrategyClass = $strategyHelper->namespace . '\\' . $baseStrategyName;
        dd(class_exists($class),
            class_exists($baseStrategyClass),
            method_exists($baseStrategyClass, $method));
        if (
            class_exists($class) &&
            class_exists($baseStrategyClass) &&
            method_exists($baseStrategyClass, $method)
        ) {
            return (new $baseStrategyClass(
                new $class()
            ))->$method($params);
        }
        return null;
    }

    private function setNamespace(string $baseStrategyName): void
    {
        $this->namespace = 'EscolaLms\Jitsi\Strategies\\' .
            preg_replace('/^(.*)Strategy$/', '$1', $baseStrategyName);
    }
}
