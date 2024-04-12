<?php

namespace MystamystInc\ModelPolicyGenerator\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MystamystInc\ModelPolicyGenerator\ModelPolicyGenerator
 */
class ModelPolicyGenerator extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \MystamystInc\ModelPolicyGenerator\ModelPolicyGenerator::class;
    }
}
