<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInPlural("Messages")
                    ->setEntityLabelInSingular("Message")
                    ->addFormTheme("@FOSCKEditor/Form/ckeditor_widget.html.twig");




    }

//    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('email'),
            TextField::new('fullName'),
            TextEditorField::new('message')
                                ->setFormType('FOS\CKEditorBundle\Form\Type\CKEditorType'),
        ];
    }
//    */
}
