<?php 

namespace MYD\PlatformBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class AdvertAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
        ->add('date', 'datetime')
        ->add('title', 'text')
        ->add('author', 'text')
        ->add('content', 'textarea')
        ->add('category', 'sonata_type_model', array(
            'class' => 'MYD\PlatformBundle\Entity\Category',
            'property' => 'name',
        ))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
        ->add('date', 'datetime')
        ->add('title', 'text')
        ->add('author', 'text')
        ->add('content', 'textarea')
        ->add('category', 'sonata_type_model', array(
            'class' => 'MYD\PlatformBundle\Entity\Category',
            'property' => 'name',
        ))
        ;
    }

    public function toString($object)
    {
        return $object instanceof Advert
            ? $object->getTitle()
            : 'Advert'; // shown in the breadcrumb on the create view
    }
}