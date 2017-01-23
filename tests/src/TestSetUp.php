<?php

namespace DeimosTest;

use Deimos\Cookie\Cookie;
use Deimos\CSRF\CSRF;
use Deimos\Session\Session;

class TestSetUp extends \PHPUnit_Framework_TestCase
{

    protected $csrf;

    public function setUp()
    {

        $builder = new Builder();
        $helper  = $builder->helper();
        $cookie  = new Cookie($builder);
        $session = new Session($builder);

        $this->csrf = new CSRF($session, $cookie, $helper);

    }

}
