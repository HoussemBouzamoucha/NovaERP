<?php

namespace App\Tests\Controller;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ProjectControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $projectRepository;
    private string $path = '/project/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->projectRepository = $this->manager->getRepository(Project::class);

        foreach ($this->projectRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Project index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'project[title]' => 'Testing',
            'project[description]' => 'Testing',
            'project[startDate_at]' => 'Testing',
            'project[endDate_at]' => 'Testing',
            'project[budget]' => 'Testing',
            'project[status]' => 'Testing',
            'project[yes]' => 'Testing',
            'project[Client]' => 'Testing',
            'project[inventories]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->projectRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Project();
        $fixture->setTitle('My Title');
        $fixture->setDescription('My Title');
        $fixture->setStartDate_at('My Title');
        $fixture->setEndDate_at('My Title');
        $fixture->setBudget('My Title');
        $fixture->setStatus('My Title');
        $fixture->setYes('My Title');
        $fixture->setClient('My Title');
        $fixture->setInventories('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Project');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Project();
        $fixture->setTitle('Value');
        $fixture->setDescription('Value');
        $fixture->setStartDate_at('Value');
        $fixture->setEndDate_at('Value');
        $fixture->setBudget('Value');
        $fixture->setStatus('Value');
        $fixture->setYes('Value');
        $fixture->setClient('Value');
        $fixture->setInventories('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'project[title]' => 'Something New',
            'project[description]' => 'Something New',
            'project[startDate_at]' => 'Something New',
            'project[endDate_at]' => 'Something New',
            'project[budget]' => 'Something New',
            'project[status]' => 'Something New',
            'project[yes]' => 'Something New',
            'project[Client]' => 'Something New',
            'project[inventories]' => 'Something New',
        ]);

        self::assertResponseRedirects('/project/');

        $fixture = $this->projectRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getStartDate_at());
        self::assertSame('Something New', $fixture[0]->getEndDate_at());
        self::assertSame('Something New', $fixture[0]->getBudget());
        self::assertSame('Something New', $fixture[0]->getStatus());
        self::assertSame('Something New', $fixture[0]->getYes());
        self::assertSame('Something New', $fixture[0]->getClient());
        self::assertSame('Something New', $fixture[0]->getInventories());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Project();
        $fixture->setTitle('Value');
        $fixture->setDescription('Value');
        $fixture->setStartDate_at('Value');
        $fixture->setEndDate_at('Value');
        $fixture->setBudget('Value');
        $fixture->setStatus('Value');
        $fixture->setYes('Value');
        $fixture->setClient('Value');
        $fixture->setInventories('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/project/');
        self::assertSame(0, $this->projectRepository->count([]));
    }
}
