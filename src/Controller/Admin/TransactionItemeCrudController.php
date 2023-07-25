<?php

namespace App\Controller\Admin;

use App\Entity\TransactionIteme;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TransactionItemeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TransactionIteme::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
