<?php

/*
 * This file is part of the VinceCmsSonataAdmin bundle.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vince\Bundle\CmsSonataAdminBundle\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vince\Bundle\CmsBundle\Entity\Template;
use Vince\Bundle\CmsSonataAdminBundle\Form\Transformer\ContentsTransformer;

/**
 * TemplateType manage contents list grouped by template name
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class TemplateType extends AbstractType
{

    /**
     * Object manager
     *
     * @var ObjectManager
     */
    protected $em;

    /**
     * Contents class name
     *
     * @var string
     */
    protected $class;

    /**
     * Set object manager
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param ObjectManager $objectManager
     *
     * @return MetaGroupType
     */
    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->em = $objectManager;

        return $this;
    }

    /**
     * Set Contents class name
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param string $class Class name
     *
     * @return MetaGroupType
     */
    public function setContentsClassName($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new ContentsTransformer($this->em, $this->class));
        $list = $this->em->getRepository('VinceCmsBundle:Template')->findAll();
        foreach ($list as $template) {
            /** @var Template $template */
            $builder->add($template->getSlug(), 'area', array(
                    'label' => false,
                    'areas' => $template->getAreas(),
                    'attr'  => array('template_id' => $template->getId())
                )
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'template';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'form';
    }
}