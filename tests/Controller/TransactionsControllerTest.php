<?php

namespace App\Test\Controller;

use App\Entity\Transactions;
use App\Repository\TransactionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TransactionsControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private TransactionsRepository $repository;
    private string $path = '/transactions/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Transactions::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Transaction index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'transaction[transactions_date]' => 'Testing',
            'transaction[total_amount]' => 'Testing',
            'transaction[cash_amount]' => 'Testing',
            'transaction[card_amount]' => 'Testing',
            'transaction[cheque_amount]' => 'Testing',
        ]);

        self::assertResponseRedirects('/transactions/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Transactions();
        $fixture->setTransactions_date('My Title');
        $fixture->setTotal_amount('My Title');
        $fixture->setCash_amount('My Title');
        $fixture->setCard_amount('My Title');
        $fixture->setCheque_amount('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Transaction');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Transactions();
        $fixture->setTransactions_date('My Title');
        $fixture->setTotal_amount('My Title');
        $fixture->setCash_amount('My Title');
        $fixture->setCard_amount('My Title');
        $fixture->setCheque_amount('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'transaction[transactions_date]' => 'Something New',
            'transaction[total_amount]' => 'Something New',
            'transaction[cash_amount]' => 'Something New',
            'transaction[card_amount]' => 'Something New',
            'transaction[cheque_amount]' => 'Something New',
        ]);

        self::assertResponseRedirects('/transactions/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTransactions_date());
        self::assertSame('Something New', $fixture[0]->getTotal_amount());
        self::assertSame('Something New', $fixture[0]->getCash_amount());
        self::assertSame('Something New', $fixture[0]->getCard_amount());
        self::assertSame('Something New', $fixture[0]->getCheque_amount());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Transactions();
        $fixture->setTransactions_date('My Title');
        $fixture->setTotal_amount('My Title');
        $fixture->setCash_amount('My Title');
        $fixture->setCard_amount('My Title');
        $fixture->setCheque_amount('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/transactions/');
    }
}
