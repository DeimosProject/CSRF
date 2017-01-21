<?php

namespace Deimos\CSRF;

use Deimos\Cookie\Cookie;
use Deimos\Helper\Helper;
use Deimos\Session\Session;

class CSRF
{

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Cookie
     */
    protected $cookie;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $generate;

    /**
     * @var string
     */
    protected $currentKey;

    /**
     * @var string
     */
    protected $currentValue;

    /**
     * CSRF constructor.
     *
     * @param Session $session
     * @param Cookie  $cookie
     * @param Helper  $helper
     */
    public function __construct(Session $session, Cookie $cookie, Helper $helper)
    {
        $this->session = $session;
        $this->cookie  = $cookie;
        $this->helper  = $helper;
    }

    /**
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function getCurrentKey()
    {
        if (!$this->currentKey)
        {
            $this->currentKey = $this->session->flash('__csrfKey');
        }

        return $this->currentKey;
    }

    /**
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    protected function getCurrentValue()
    {
        if (!$this->currentValue)
        {
            $this->currentValue = $this->session->flash('__csrfValue');
        }

        return $this->currentValue;
    }

    /**
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function generate()
    {
        if (!$this->generate)
        {
            $this->generate = $this->helper->str()->random();
            $this->session->flash('__csrfValue', $this->generate);
        }

        return $this->generate;
    }

    /**
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function getKey()
    {
        if (!$this->key)
        {
            $this->key = '__csrf' . $this->helper->str()->random();
            $this->session->flash('__csrfKey', $this->key);
        }

        return $this->key;
    }

    /**
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function getSecret()
    {
        if (!$this->secret)
        {
            $this->key    = $this->getKey();
            $this->secret = $this->helper->str()->random();
        }

        return $this->secret;
    }

    /**
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function getToken()
    {
        $secret = $this->getCurrentKey();

        if (!$secret)
        {
            return $this->token();
        }

        return $this->cookie->flash('__csrfToken', null, [
            Cookie::SECURE_SECRET => $secret
        ]);
    }

    /**
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function token()
    {
        if (!$this->token)
        {
            $this->token = $this->generate();

            $this->cookie->flash('__csrfToken', $this->token, [
                Cookie::SECURE_SECRET => $this->getSecret()
            ]);
        }

        return $this->token;
    }

    /**
     * @param $token
     *
     * @return bool
     *
     * @throws \InvalidArgumentException
     */
    public function valid($token)
    {
        $lastToken = $this->getToken();

        return $token === $lastToken;
    }

}