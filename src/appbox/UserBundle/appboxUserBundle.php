<?php

namespace appbox\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class appboxUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
