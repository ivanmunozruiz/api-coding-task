<?php

declare(strict_types=1);

namespace App\Shared\Domain;

trait MessageExtractor
{
    public function messageAggregateContext(): string
    {
        $classNameSplit = explode('\\', $this->aggregateName());
        $aggregateContext = ClassFunctions::toKebabCase($classNameSplit[1]);

        return str_replace('-context', '', $aggregateContext);
    }

    /** @throws \Throwable */
    public function messageAggregateAction(): string
    {
        $value = ClassFunctions::extractClassName($this);
        $actionName = ClassFunctions::toKebabCase($value);

        return str_replace($this->messageAggregateName() . '-', '', $actionName);
    }

    public function messageAggregateName(): string
    {
        $aggregateName = ClassFunctions::extractClassNameFromString($this->aggregateName(), '');

        return ClassFunctions::toKebabCase($aggregateName);
    }

    abstract public function aggregateName(): string;

    abstract public function messageType(): string;

    public function messageApplicationId(): ?string
    {
        return null;
    }
}
