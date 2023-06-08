<?php

declare(strict_types=1);

use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\PackageBuilder\Console\Formatter\ColorConsoleDiffFormatter;

use Symplify\PackageBuilder\Console\Output\ConsoleDiffer;
use Symplify\PackageBuilder\Diff\Output\CompleteUnifiedDiffOutputBuilderFactory;
use Symplify\PackageBuilder\Reflection\PrivatesAccessor;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->set(ColorConsoleDiffFormatter::class);
    $services->set(ConsoleDiffer::class);
    $services->set(CompleteUnifiedDiffOutputBuilderFactory::class);

    // @comment-by-Leo Replaced this line with the code below
    // $services->set(Differ::class);
    $services->set(UnifiedDiffOutputBuilder::class)
        ->factory([service(CompleteUnifiedDiffOutputBuilderFactory::class), 'create']);
    $services->set(Differ::class)
        ->arg('$outputBuilder', service(UnifiedDiffOutputBuilder::class));

    $services->set(PrivatesAccessor::class);
};
