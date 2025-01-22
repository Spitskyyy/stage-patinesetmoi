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
            'voilage_rideaux_double[usagetxt]' => 'Testing',
            'voilage_rideaux_double[image]' => 'Testing',
            'voilage_rideaux_double[largeur]' => 'Testing',
            'voilage_rideaux_double[hauteur]' => 'Testing',
            'voilage_rideaux_double[doublure]' => 'Testing',
            'voilage_rideaux_double[tissu]' => 'Testing',
            'voilage_rideaux_double[finition]' => 'Testing',
            'voilage_rideaux_double[temps]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new VoilageRideauxDoubles();
        $fixture->setUsagetxt('My Title');
        $fixture->setImage('My Title');
        $fixture->setLargeur('My Title');
        $fixture->setHauteur('My Title');
        $fixture->setDoublure('My Title');
        $fixture->setTissu('My Title');
        $fixture->setFinition('My Title');
        $fixture->setTemps('My Title');

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
        $fixture->setUsagetxt('Value');
        $fixture->setImage('Value');
        $fixture->setLargeur('Value');
        $fixture->setHauteur('Value');
        $fixture->setDoublure('Value');
        $fixture->setTissu('Value');
        $fixture->setFinition('Value');
        $fixture->setTemps('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'voilage_rideaux_double[usagetxt]' => 'Something New',
            'voilage_rideaux_double[image]' => 'Something New',
            'voilage_rideaux_double[largeur]' => 'Something New',
            'voilage_rideaux_double[hauteur]' => 'Something New',
            'voilage_rideaux_double[doublure]' => 'Something New',
            'voilage_rideaux_double[tissu]' => 'Something New',
            'voilage_rideaux_double[finition]' => 'Something New',
            'voilage_rideaux_double[temps]' => 'Something New',
        ]);

        self::assertResponseRedirects('/voilage/rideaux/doubles/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getUsagetxt());
        self::assertSame('Something New', $fixture[0]->getImage());
        self::assertSame('Something New', $fixture[0]->getLargeur());
        self::assertSame('Something New', $fixture[0]->getHauteur());
        self::assertSame('Something New', $fixture[0]->getDoublure());
        self::assertSame('Something New', $fixture[0]->getTissu());
        self::assertSame('Something New', $fixture[0]->getFinition());
        self::assertSame('Something New', $fixture[0]->getTemps());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new VoilageRideauxDoubles();
        $fixture->setUsagetxt('Value');
        $fixture->setImage('Value');
        $fixture->setLargeur('Value');
        $fixture->setHauteur('Value');
        $fixture->setDoublure('Value');
        $fixture->setTissu('Value');
        $fixture->setFinition('Value');
        $fixture->setTemps('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/voilage/rideaux/doubles/');
        self::assertSame(0, $this->repository->count([]));
    }
}
