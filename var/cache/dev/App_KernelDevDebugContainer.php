<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerXqPvURE\App_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerXqPvURE/App_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerXqPvURE.legacy');

    return;
}

if (!\class_exists(App_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerXqPvURE\App_KernelDevDebugContainer::class, App_KernelDevDebugContainer::class, false);
}

return new \ContainerXqPvURE\App_KernelDevDebugContainer([
    'container.build_hash' => 'XqPvURE',
    'container.build_id' => 'f6e20bb1',
    'container.build_time' => 1602343240,
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerXqPvURE');
