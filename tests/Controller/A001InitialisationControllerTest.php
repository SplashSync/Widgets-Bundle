<?php

namespace Splash\Widgets\Tests\Controller;

use Symfony\Component\Process\Process;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class A001InitialisationControllerTest extends KernelTestCase
{
    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();
    }        

    /**
     * @abstract    Show Logo
     */    
    public function testDisplayLogo()
    {
        echo PHP_EOL;
        echo " ______     ______   __         ______     ______     __  __    " . PHP_EOL;
        echo "/\  ___\   /\  == \ /\ \       /\  __ \   /\  ___\   /\ \_\ \   " . PHP_EOL;
        echo "\ \___  \  \ \  _-/ \ \ \____  \ \  __ \  \ \___  \  \ \  __ \  " . PHP_EOL;
        echo " \/\_____\  \ \_\    \ \_____\  \ \_\ \_\  \/\_____\  \ \_\ \_\ " . PHP_EOL;
        echo "  \/_____/   \/_/     \/_____/   \/_/\/_/   \/_____/   \/_/\/_/ " . PHP_EOL;
        echo "                                                                " . PHP_EOL;
        $this->assertTrue(True);
    }
    
}
