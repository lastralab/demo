<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/13/2024
 */

declare(strict_types=1);

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'Tan_Review',
    __DIR__);
