<?php 

namespace MYD\PlatformBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use AppBundle\Entity\BlogPost;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Doctrine\Common\Collections\ArrayCollection;


class AdvertAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
        ->with('Content', array('class' => 'col-md-9'))
        ->add('title', 'text')
        ->add('content', 'textarea')
        ->add('date', 'datetime')
        ->add('author', 'text')
        ->end()

        ->with('Meta data', array('class' => 'col-md-3'))
        ->add('categories', 'entity', array(
            'class' => 'MYD\PlatformBundle\Entity\Category', 
            'choice_label' => 'name', 'multiple' => true
            ))
        ->end()
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
        ->addIdentifier('title')
        ->add('content', 'textarea')
        ->add('date', 'datetime')
        ->add('author', 'text')
        ;
    }

    public function toString($object)
    {
        return $object instanceof Advert
        ? $object->getTitle()
        : 'Advert'; // shown in the breadcrumb on the create view
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
        ->add('title')
        ->add('categories', null, array(), 'entity', array(
            'class'    => 'MYD\PlatformBundle\Entity\Category',
            'choice_label' => 'name', // In Symfony2: 'property' => 'name'
        ));
    }
}












