<?php
/**
 * Notice management app
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
 */

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * Kernel
 */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}
