<?php

session_start();

include_once dirname(__DIR__) . '/vendor/autoload.php';

class Builder extends Deimos\Builder\Builder
{
    public function helper()
    {
        return $this->once(function ()
        {
            return new Deimos\Helper\Helper($this);
        }, __METHOD__);
    }
}

$builder = new Builder();
$helper  = $builder->helper();
$cookie  = new Deimos\Cookie\Cookie($builder);
$session = new Deimos\Session\Session($builder);

$csrf = new Deimos\CSRF\CSRF($session, $cookie, $helper);

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $token = $_POST[$csrf->getCurrentKey()];

    var_dump($csrf->valid($token));
    die;
}

$key   = $csrf->getKey();
$token = $csrf->token();

?>

<form method="post">
    <div>
        <input type="text" name="name" value="Max"/>
    </div>

    <div>
        <input type="text" name="<?php echo $key; ?>" value="<?php echo $token; ?>"/>
    </div>

    <button>Submit</button>
</form>