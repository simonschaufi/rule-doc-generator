<?php

declare(strict_types=1);

use Rector\Contract\Rector\RectorInterface;

/**
 * Rector registers backward compatibility class aliases in an autoload file of its own
 * nested vendor autoloader. That file runs on the first loaded Rector class. If one of
 * the aliased classes itself is the very first loaded class, PHP skips the nested
 * autoloading recursively and Rector triggers a "class not found" warning.
 *
 * Loading any other Rector class upfront completes that nested autoloading, so all
 * aliased classes resolve cleanly afterwards.
 */
interface_exists(RectorInterface::class);
