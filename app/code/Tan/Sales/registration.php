<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/14/2024
 */

declare(strict_types=1);

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'Tan_Sales',
    __DIR__);
