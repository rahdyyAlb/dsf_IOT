<?php

namespace App\Test\Controller;

use App\Entity\Day;
use App\Repository\DayRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DayControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private DayRepository $repository;
    private string $path = '/day/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Day::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Day index');

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
            'day[date]' => 'Testing',
            'day[cash_total]' => 'Testing',
            'day[card_total]' => 'Testing',
            'day[cheque_total]' => 'Testing',
            'day[caisse_id]' => 'Testing',
        ]);

        self::assertResponseRedirects('/day/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Day();
        $fixture->setDate('My Title');
        $fixture->setCash_total('My Title');
        $fixture->setCard_total('My Title');
        $fixture->setCheque_total('My Title');
        $fixture->setCaisse_id('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Day');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Day();
        $fixture->setDate('My Title');
        $fixture->setCash_total('My Title');
        $fixture->setCard_total('My Title');
        $fixture->setCheque_total('My Title');
        $fixture->setCaisse_id('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'day[date]' => 'Something New',
            'day[cash_total]' => 'Something New',
            'day[card_total]' => 'Something New',
            'day[cheque_total]' => 'Something New',
            'day[caisse_id]' => 'Something New',
        ]);

        self::assertResponseRedirects('/day/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDate());
        self::assertSame('Something New', $fixture[0]->getCash_total());
        self::assertSame('Something New', $fixture[0]->getCard_total());
        self::assertSame('Something New', $fixture[0]->getCheque_total());
        self::assertSame('Something New', $fixture[0]->getCaisse_id());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Day();
        $fixture->setDate('My Title');
        $fixture->setCash_total('My Title');
        $fixture->setCard_total('My Title');
        $fixture->setCheque_total('My Title');
        $fixture->setCaisse_id('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/day/');
    }
}
