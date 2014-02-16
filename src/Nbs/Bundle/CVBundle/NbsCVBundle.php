<?php

namespace Nbs\Bundle\CVBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class NbsCVBundle extends Bundle
{
    public function getParent()
    {
        return 'MremiContactBundle';
    }
}
