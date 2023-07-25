<?php

namespace App\Controller\Admin;

use App\Entity\Transactions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TransactionsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Transactions::class;
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
