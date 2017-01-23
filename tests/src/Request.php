<?php

namespace DeimosTest;

class Request extends \Deimos\Request\Request
{

    protected function inputArray($type)
    {
        switch ($type)
        {
            case INPUT_GET:
                return array_merge($_GET, parent::inputArray($type));
            case INPUT_POST:
                return array_merge($_POST, parent::inputArray($type));
            case INPUT_SERVER:
                return array_merge($_SERVER, parent::inputArray($type));
        }
    }

    protected function getInput()
    {
        return array_merge($_REQUEST, parent::getInput());
    }

}