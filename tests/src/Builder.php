<?php

namespace DeimosTest;

use Deimos\Helper\Helper;

class Builder extends \Deimos\Builder\Builder
{
    public function helper()
    {
        return $this->once(function ()
        {
            return new Helper($this);
        }, __METHOD__);
    }
}
