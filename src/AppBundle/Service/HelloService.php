<?php
/**
 * Created by PhpStorm.
 * User: allth
 * Date: 08/09/2017
 * Time: 09:41
 */

namespace AppBundle\Service;


use Symfony\Component\HttpFoundation\Tests\StringableObject;

class HelloService
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var HelloRenderer
     */
    private $renderer;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * HelloService constructor.
     * @param $name
     * @param HelloRenderer $renderer
     */
    public function __construct($name, HelloRenderer $renderer)
    {
        $this->name = $name;
        $this->renderer = $renderer;
    }

    public function sayHello()
    {
        return $this->renderer->render("Hello {$this->name}");
    }
}