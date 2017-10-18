<?php


namespace Splash\Widgets\Block;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\CoreBundle\Model\Metadata;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sonata\BlockBundle\Block\Service\AbstractAdminBlockService;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

use Doctrine\ORM\EntityManager;

use Splash\Widgets\Entity\WidgetCollection;

/**
 * @author Bernard Paquier <eshop.bpaquier@gmail.com>
 */
class WidgetCollectionBlock extends AbstractAdminBlockService
{
//    /**
//     * @var FactoryInterface
//     */
//    private $factory;
//
    /**
     * @abstract    Widget Collections Repository
     */
    private $Repository;
    
    /**
     * @param string                $name
     * @param EngineInterface       $templating
     * @param EntityManager         $Manager
     */
    public function __construct($name, EngineInterface $templating, EntityManager $Manager)
    {
        parent::__construct($name, $templating);
        
        $this->Manager      =   $Manager;
        $this->Repository   =   $Manager->getRepository('SplashWidgetsBundle:WidgetCollection');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'url'       => false,
            'title'     => 'Splash Widget Collection Block',
            'collection'=> 'demo-block',
            'channel'   => 'demo',
            'template'  => 'SplashWidgetsBundle:Blocks:Collection.html.twig',
            'editable'  => True,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'keys' => array(
                array('title',      'text', array('required' => false)),
                array('collection', 'text', array('required' => true)),
                array('channel',    'text', array('required' => true)),
            ),
        ));
    }

    /**
     * {@inheritdoc}
     */
//    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
//    {
//        $errorElement
//            ->with('settings[url]')
//                ->assertNotNull(array())
//                ->assertNotBlank()
//            ->end()
//            ->with('settings[title]')
//                ->assertNotNull(array())
//                ->assertNotBlank()
//                ->assertLength(array('max' => 50))
//            ->end();
//    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $Settings = $blockContext->getSettings();
//dump($response);
        //==============================================================================
        // Load Collection from DataBase
        $Collection =   $this->Repository->findOneByType($Settings["collection"]);         
        
        //==============================================================================
        // Create Collection if not found
        if ( !$Collection ) {
            $Collection =   new WidgetCollection();
            $this->Manager->persist($Collection);
        } 
        //==============================================================================
        // Update Collection Parameters
        $Collection->setType($Settings["collection"]);
        $this->Manager->flush();
        
        
        return $this->renderResponse($blockContext->getTemplate(), array(
            "Collection"    =>  $Collection,
            "Title"         =>  $Settings["title"],
            "Channel"       =>  $Settings["channel"],
            "Edit"          =>  $Settings["editable"],
        ), $response);
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
