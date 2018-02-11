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
            'options'   => array(),
            'channel'   => 'demo',
            'template'  => 'SplashWidgetsBundle:Widget:base.html.twig',
            'edit'      => False,
            'attr'      => array(),
            'preset'    => "M"
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
                array('channel',    'text', array('required' => true)),
                array('editable',   'bool', array('required' => false)),
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
        // Prepare Widget Options
        if ( $Settings["preset"] != False ) {
            $WidgetOptions  =   array_merge($this->getDatesArray($Settings["preset"]), $Settings["options"]);
        } else {
            $WidgetOptions  =   $Settings["options"];
        } 
        
        //==============================================================================
        // Read Widget Contents 
        $Widget =   Null;
        if ( !empty($Settings["service"]) && !empty($Settings["type"])  ) {
            $Widget =   $this->WidgetsManager->getWidget($Settings["service"], $Settings["type"], $WidgetOptions);
        }
        
        //==============================================================================
        // Merge Passed Rendering Options to Widget Options
        $Widget->setOptions( array_merge( $Widget->getOptions() , $Settings["attr"] ) );

        //==============================================================================
        // Validate Widget Contents 
        if (is_null($Widget)  ) {
            $Widget =   $this->WidgetFactory->buildErrorWidget($Settings["service"], $Settings["type"], "An Error Occured During Widget Loading");
        }
        //==============================================================================
        // Render Response 
        return $this->renderResponse($blockContext->getTemplate(), array(
                "Widget"    => $Widget,
                "Edit"      => $Settings["edit"],
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
