<?php

namespace Test;

use DeimosTest\TestSetUp;

class CSRFTest extends TestSetUp
{

    /**
     * @runInSeparateProcess
     */
    public function testCsrf()
    {

        $key = $this->csrf->getCurrentKey();

        $token = mt_rand();
        if(isset($_POST[$key])) {

            $token = $_POST[$key];
        }

        $this->assertFalse($this->csrf->valid($token));

        $key   = $this->csrf->getKey();
        $token = $this->csrf->token();

        $this->assertEquals($key, $this->csrf->getCurrentKey());
        $this->assertTrue($this->csrf->valid($token));

    }

}
