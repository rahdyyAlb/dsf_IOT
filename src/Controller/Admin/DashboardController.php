<?php

namespace App\Controller\Admin;

use App\Entity\Caisse;
use App\Entity\Categories;
use App\Entity\Customers;
use App\Entity\Products;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');

    }


	public function configureDashboard(): Dashboard
	{
		return Dashboard::new()
			// the name visible to end users
			->setTitle('ACME Corp.')
			// you can include HTML contents too (e.g. to link to an image)
			->setTitle('<img src="..."> ACME <span class="text-small">Corp.</span>')

			->setFaviconPath('favicon.svg')

			->setTranslationDomain('my-custom-domain')

			->setTextDirection('ltr')

			->renderContentMaximized()

			->renderSidebarMinimized()

			->disableDarkMode()

			->generateRelativeUrls()
			->setLocales(['fr'])
			->setLocales([
				'fr' => 'france',
			])
			;
	}
	public function configureMenuItems(): iterable
	{
		return [
			MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

			MenuItem::section('Blog'),
			MenuItem::linkToCrud('Categories', 'fa fa-tags', Caisse::class),
			MenuItem::linkToCrud('Blog Posts', 'fa fa-file-text', Categories::class),

			MenuItem::section('Users'),
			MenuItem::linkToCrud('Comments', 'fa fa-comment', Customers::class),
			MenuItem::linkToCrud('Users', 'fa fa-user', Products::class),

		];
	}}
