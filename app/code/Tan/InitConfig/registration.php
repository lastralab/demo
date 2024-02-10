<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/06/2024
 */


use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'Tan_InitConfig',
    __DIR__);
