<?php

declare(strict_types=1);

namespace App\Test\Controller;

use App\Entity\TransactionIteme;
use App\Repository\TransactionItemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TransactionItemeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private TransactionItemeRepository $repository;
    private string $path = '/transaction/iteme/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(TransactionIteme::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('TransactionIteme index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = \count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'transaction_iteme[quantity]' => 'Testing',
            'transaction_iteme[total]' => 'Testing',
            'transaction_iteme[transactionIteme]' => 'Testing',
            'transaction_iteme[product_id]' => 'Testing',
        ]);

        self::assertResponseRedirects('/transaction/iteme/');

        self::assertSame($originalNumObjectsInRepository + 1, \count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new TransactionIteme();
        $fixture->setQuantity('My Title');
        $fixture->setTotal('My Title');
        $fixture->setTransactionIteme('My Title');
        $fixture->setProduct_id('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('TransactionIteme');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new TransactionIteme();
        $fixture->setQuantity('My Title');
        $fixture->setTotal('My Title');
        $fixture->setTransactionIteme('My Title');
        $fixture->setProduct_id('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'transaction_iteme[quantity]' => 'Something New',
            'transaction_iteme[total]' => 'Something New',
            'transaction_iteme[transactionIteme]' => 'Something New',
            'transaction_iteme[product_id]' => 'Something New',
        ]);

        self::assertResponseRedirects('/transaction/iteme/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getQuantity());
        self::assertSame('Something New', $fixture[0]->getTotal());
        self::assertSame('Something New', $fixture[0]->getTransactionIteme());
        self::assertSame('Something New', $fixture[0]->getProduct_id());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = \count($this->repository->findAll());

        $fixture = new TransactionIteme();
        $fixture->setQuantity('My Title');
        $fixture->setTotal('My Title');
        $fixture->setTransactionIteme('My Title');
        $fixture->setProduct_id('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, \count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, \count($this->repository->findAll()));
        self::assertResponseRedirects('/transaction/iteme/');
    }
}
