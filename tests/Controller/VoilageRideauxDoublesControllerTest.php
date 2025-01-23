<?php

namespace App\Tests\Controller;

use App\Entity\VoilageRideauxDoubles;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class VoilageRideauxDoublesControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/voilage/rideaux/doubles/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(VoilageRideauxDoubles::class);

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
        self::assertPageTitleContains('VoilageRideauxDouble index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'voilage_rideaux_double[title]' => 'Testing',
            'voilage_rideaux_double[picture]' => 'Testing',
            'voilage_rideaux_double[usetxt]' => 'Testing',
            'voilage_rideaux_double[width]' => 'Testing',
            'voilage_rideaux_double[height]' => 'Testing',
            'voilage_rideaux_double[lining]' => 'Testing',
            'voilage_rideaux_double[fabric]' => 'Testing',
            'voilage_rideaux_double[curtain_head_finishing]' => 'Testing',
            'voilage_rideaux_double[time]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new VoilageRideauxDoubles();
        $fixture->setTitle('My Title');
        $fixture->setPicture('My Title');
        $fixture->setUsetxt('My Title');
        $fixture->setWidth('My Title');
        $fixture->setHeight('My Title');
        $fixture->setLining('My Title');
        $fixture->setFabric('My Title');
        $fixture->setCurtain_head_finishing('My Title');
        $fixture->setTime('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('VoilageRideauxDouble');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new VoilageRideauxDoubles();
        $fixture->setTitle('Value');
        $fixture->setPicture('Value');
        $fixture->setUsetxt('Value');
        $fixture->setWidth('Value');
        $fixture->setHeight('Value');
        $fixture->setLining('Value');
        $fixture->setFabric('Value');
        $fixture->setCurtain_head_finishing('Value');
        $fixture->setTime('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'voilage_rideaux_double[title]' => 'Something New',
            'voilage_rideaux_double[picture]' => 'Something New',
            'voilage_rideaux_double[usetxt]' => 'Something New',
            'voilage_rideaux_double[width]' => 'Something New',
            'voilage_rideaux_double[height]' => 'Something New',
            'voilage_rideaux_double[lining]' => 'Something New',
            'voilage_rideaux_double[fabric]' => 'Something New',
            'voilage_rideaux_double[curtain_head_finishing]' => 'Something New',
            'voilage_rideaux_double[time]' => 'Something New',
        ]);

        self::assertResponseRedirects('/voilage/rideaux/doubles/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getPicture());
        self::assertSame('Something New', $fixture[0]->getUsetxt());
        self::assertSame('Something New', $fixture[0]->getWidth());
        self::assertSame('Something New', $fixture[0]->getHeight());
        self::assertSame('Something New', $fixture[0]->getLining());
        self::assertSame('Something New', $fixture[0]->getFabric());
        self::assertSame('Something New', $fixture[0]->getCurtain_head_finishing());
        self::assertSame('Something New', $fixture[0]->getTime());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new VoilageRideauxDoubles();
        $fixture->setTitle('Value');
        $fixture->setPicture('Value');
        $fixture->setUsetxt('Value');
        $fixture->setWidth('Value');
        $fixture->setHeight('Value');
        $fixture->setLining('Value');
        $fixture->setFabric('Value');
        $fixture->setCurtain_head_finishing('Value');
        $fixture->setTime('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/voilage/rideaux/doubles/');
        self::assertSame(0, $this->repository->count([]));
    }
}
