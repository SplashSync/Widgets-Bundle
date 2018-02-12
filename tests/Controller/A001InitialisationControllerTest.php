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
    
    /**
     * @abstract    Clear CAche for All Environements
     * @dataProvider envTestNamesProvider     
     */    
    public function testCacheClear($Environement)
    {
        //====================================================================//
        // Create Process
        $process = new Process("php bin/console cache:clear --no-debug --env=" . $Environement);
        
        //====================================================================//
        // Clean Working Dir
        $WorkingDirectory   =   $process->getWorkingDirectory();
        if (strrpos($WorkingDirectory, "/app") == (strlen($WorkingDirectory) - 4) ){
            $process->setWorkingDirectory(substr($WorkingDirectory, 0, strlen($WorkingDirectory) - 4));
        }     
        
        //====================================================================//
        // Run Process
        $process->run();
        
        if ( !$process->isSuccessful() ) {
            echo $process->getCommandLine() . PHP_EOL;
            echo $process->getOutput();
        }
        
        $this->assertTrue($process->isSuccessful());
        
    }  

    /**
     * @abstract    Test All Environement Are Loadable
     * @dataProvider envTestNamesProvider     
     */    
    public function testEnvironements($Environement)
    {
        //====================================================================//
        // Create Process
        $process = new Process("php bin/console debug:router --no-debug --env=" . $Environement);
        
        //====================================================================//
        // Clean Working Dir
        $WorkingDirectory   =   $process->getWorkingDirectory();
        if (strrpos($WorkingDirectory, "/app") == (strlen($WorkingDirectory) - 4) ){
            $process->setWorkingDirectory(substr($WorkingDirectory, 0, strlen($WorkingDirectory) - 4));
        }     
        
        //====================================================================//
        // Run Process
        $process->run();
        
        //====================================================================//
        // Fail => Display Process Outputs
        if ( !$process->isSuccessful() ) {
            echo $process->getCommandLine() . PHP_EOL;
            echo $process->getOutput();
        }
        
        $this->assertTrue($process->isSuccessful());
        
    }        
    
    public function envTestNamesProvider()
    {
        return array(
            array("dev"),
            array("test"),
        );        
    }       
}
