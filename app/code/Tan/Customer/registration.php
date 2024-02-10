<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/10/2024
 */

declare(strict_types=1);

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'Tan_Customer',
    __DIR__);
