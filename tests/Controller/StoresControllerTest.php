<?php

namespace App\Tests\Controller;

use App\Entity\Stores;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class StoresControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/stores/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Stores::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Store index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'store[title]' => 'Testing',
            'store[picture]' => 'Testing',
            'store[usetxt]' => 'Testing',
            'store[width]' => 'Testing',
            'store[height]' => 'Testing',
            'store[lining]' => 'Testing',
            'store[fabric]' => 'Testing',
            'store[time]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Stores();
        $fixture->setTitle('My Title');
        $fixture->setPicture('My Title');
        $fixture->setUsetxt('My Title');
        $fixture->setWidth('My Title');
        $fixture->setHeight('My Title');
        $fixture->setLining('My Title');
        $fixture->setFabric('My Title');
        $fixture->setTime('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Store');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Stores();
        $fixture->setTitle('Value');
        $fixture->setPicture('Value');
        $fixture->setUsetxt('Value');
        $fixture->setWidth('Value');
        $fixture->setHeight('Value');
        $fixture->setLining('Value');
        $fixture->setFabric('Value');
        $fixture->setTime('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'store[title]' => 'Something New',
            'store[picture]' => 'Something New',
            'store[usetxt]' => 'Something New',
            'store[width]' => 'Something New',
            'store[height]' => 'Something New',
            'store[lining]' => 'Something New',
            'store[fabric]' => 'Something New',
            'store[time]' => 'Something New',
        ]);

        self::assertResponseRedirects('/stores/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getPicture());
        self::assertSame('Something New', $fixture[0]->getUsetxt());
        self::assertSame('Something New', $fixture[0]->getWidth());
        self::assertSame('Something New', $fixture[0]->getHeight());
        self::assertSame('Something New', $fixture[0]->getLining());
        self::assertSame('Something New', $fixture[0]->getFabric());
        self::assertSame('Something New', $fixture[0]->getTime());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Stores();
        $fixture->setTitle('Value');
        $fixture->setPicture('Value');
        $fixture->setUsetxt('Value');
        $fixture->setWidth('Value');
        $fixture->setHeight('Value');
        $fixture->setLining('Value');
        $fixture->setFabric('Value');
        $fixture->setTime('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/stores/');
        self::assertSame(0, $this->repository->count([]));
    }
}
