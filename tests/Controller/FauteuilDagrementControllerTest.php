<?php

namespace App\Tests\Controller;

use App\Entity\FauteuilDagrement;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class FauteuilDagrementControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/fauteuil/dagrement/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(FauteuilDagrement::class);

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
        self::assertPageTitleContains('FauteuilDagrement index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'fauteuil_dagrement[title]' => 'Testing',
            'fauteuil_dagrement[usagetxt]' => 'Testing',
            'fauteuil_dagrement[largeur]' => 'Testing',
            'fauteuil_dagrement[profondeur]' => 'Testing',
            'fauteuil_dagrement[hauteur]' => 'Testing',
            'fauteuil_dagrement[recouverture]' => 'Testing',
            'fauteuil_dagrement[materiaux]' => 'Testing',
            'fauteuil_dagrement[tissu]' => 'Testing',
            'fauteuil_dagrement[finition]' => 'Testing',
            'fauteuil_dagrement[temps]' => 'Testing',
            'fauteuil_dagrement[detail]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new FauteuilDagrement();
        $fixture->setTitle('My Title');
        $fixture->setUsagetxt('My Title');
        $fixture->setLargeur('My Title');
        $fixture->setProfondeur('My Title');
        $fixture->setHauteur('My Title');
        $fixture->setRecouverture('My Title');
        $fixture->setMateriaux('My Title');
        $fixture->setTissu('My Title');
        $fixture->setFinition('My Title');
        $fixture->setTemps('My Title');
        $fixture->setDetail('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('FauteuilDagrement');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new FauteuilDagrement();
        $fixture->setTitle('Value');
        $fixture->setUsagetxt('Value');
        $fixture->setLargeur('Value');
        $fixture->setProfondeur('Value');
        $fixture->setHauteur('Value');
        $fixture->setRecouverture('Value');
        $fixture->setMateriaux('Value');
        $fixture->setTissu('Value');
        $fixture->setFinition('Value');
        $fixture->setTemps('Value');
        $fixture->setDetail('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'fauteuil_dagrement[title]' => 'Something New',
            'fauteuil_dagrement[usagetxt]' => 'Something New',
            'fauteuil_dagrement[largeur]' => 'Something New',
            'fauteuil_dagrement[profondeur]' => 'Something New',
            'fauteuil_dagrement[hauteur]' => 'Something New',
            'fauteuil_dagrement[recouverture]' => 'Something New',
            'fauteuil_dagrement[materiaux]' => 'Something New',
            'fauteuil_dagrement[tissu]' => 'Something New',
            'fauteuil_dagrement[finition]' => 'Something New',
            'fauteuil_dagrement[temps]' => 'Something New',
            'fauteuil_dagrement[detail]' => 'Something New',
        ]);

        self::assertResponseRedirects('/fauteuil/dagrement/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getUsagetxt());
        self::assertSame('Something New', $fixture[0]->getLargeur());
        self::assertSame('Something New', $fixture[0]->getProfondeur());
        self::assertSame('Something New', $fixture[0]->getHauteur());
        self::assertSame('Something New', $fixture[0]->getRecouverture());
        self::assertSame('Something New', $fixture[0]->getMateriaux());
        self::assertSame('Something New', $fixture[0]->getTissu());
        self::assertSame('Something New', $fixture[0]->getFinition());
        self::assertSame('Something New', $fixture[0]->getTemps());
        self::assertSame('Something New', $fixture[0]->getDetail());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new FauteuilDagrement();
        $fixture->setTitle('Value');
        $fixture->setUsagetxt('Value');
        $fixture->setLargeur('Value');
        $fixture->setProfondeur('Value');
        $fixture->setHauteur('Value');
        $fixture->setRecouverture('Value');
        $fixture->setMateriaux('Value');
        $fixture->setTissu('Value');
        $fixture->setFinition('Value');
        $fixture->setTemps('Value');
        $fixture->setDetail('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/fauteuil/dagrement/');
        self::assertSame(0, $this->repository->count([]));
    }
}
