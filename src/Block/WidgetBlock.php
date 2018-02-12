<?php


namespace Splash\Widgets\Block;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\CoreBundle\Model\Metadata;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sonata\BlockBundle\Block\Service\AbstractAdminBlockService;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

use Doctrine\ORM\EntityManager;

use Splash\Widgets\Entity\WidgetCollection;
use Splash\Widgets\Services\ManagerService;
use Splash\Widgets\Services\FactoryService;

use Splash\Widgets\Models\Traits\ParametersTrait; 

use Splash\Widgets\Entity\WidgetCache;

/**
 * @author Bernard Paquier <eshop.bpaquier@gmail.com>
 */
class WidgetBlock extends AbstractAdminBlockService
{
    
    use ParametersTrait;
    
    /**
     * @abstract    Splash Widgets Manager
     * @var ManagerService
     */
    private $WidgetsManager;
    
    /**
     * @abstract    Splash Widgets Factory
     * @var FactoryService
     */
    private $WidgetFactory;
    
    /**
     * @param string                $name
     * @param EngineInterface       $templating
     * @param ManagerService        $WidgetsManager
     * @param FactoryService        $WidgetFactory
     */
    public function __construct($name, EngineInterface $templating, ManagerService $WidgetsManager, FactoryService $WidgetFactory)
    {
        parent::__construct($name, $templating);
        
        $this->WidgetsManager   =   $WidgetsManager;
        $this->WidgetFactory    =   $WidgetFactory;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'service'   => null,
            'type'      => null,
            'template'  => 'SplashWidgetsBundle:Blocks:Widget.html.twig',
            'parameters'=> array(),
            'options'   => array(),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'keys' => array(
                array('service',    'text', array('required' => true)),
                array('type',       'text', array('required' => true)),
            ),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        //==============================================================================
        // Get Block Settings
        $Settings = $blockContext->getSettings();
        
        //==============================================================================
        // Merge Passed Rendering Options to Widget Options
        $Options    =   array_merge( $this->WidgetsManager->getWidgetOptions($Settings["service"], $Settings["type"]) , $Settings["options"] );
        
        //==============================================================================
        // Merge Passed Parameters 
        $Parameters =   array_merge( $this->WidgetsManager->getWidgetParameters($Settings["service"], $Settings["type"]) , $Settings["parameters"] );

        //==============================================================================
        // Render Response 
        return $this->renderResponse($blockContext->getTemplate(), array(
                "Service"   =>  $Settings["service"],
                "Type"      =>  $Settings["type"],
                "Options"   =>  $Options,
                "Parameters"=>  $Parameters,
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockMetadata($code = null)
    {
        return new Metadata($this->getName(), (!is_null($code) ? $code : $this->getName()), false, 'SplashWidgetsBundle', array(
            'class' => 'fa fa-television',
        ));
    }
}
