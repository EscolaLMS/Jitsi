<?php

namespace EscolaLms\Jitsi\Facades;

use EscolaLms\Jitsi\Services\Contracts\JitsiServiceContract;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Amyisme13\LaravelJitsi\Skeleton\SkeletonClass
 */
class Jitsi extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return JitsiServiceContract::class;
    }
}
