<?php

namespace App\Tests\Controller;

use App\Entity\LeaveRequest;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class LeaveRequestControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $leaveRequestRepository;
    private string $path = '/leave/request/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->leaveRequestRepository = $this->manager->getRepository(LeaveRequest::class);

        foreach ($this->leaveRequestRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('LeaveRequest index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'leave_request[startDate_at]' => 'Testing',
            'leave_request[endDate_at]' => 'Testing',
            'leave_request[reason]' => 'Testing',
            'leave_request[status]' => 'Testing',
            'leave_request[users]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->leaveRequestRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new LeaveRequest();
        $fixture->setStartDate_at('My Title');
        $fixture->setEndDate_at('My Title');
        $fixture->setReason('My Title');
        $fixture->setStatus('My Title');
        $fixture->setUsers('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('LeaveRequest');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new LeaveRequest();
        $fixture->setStartDate_at('Value');
        $fixture->setEndDate_at('Value');
        $fixture->setReason('Value');
        $fixture->setStatus('Value');
        $fixture->setUsers('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'leave_request[startDate_at]' => 'Something New',
            'leave_request[endDate_at]' => 'Something New',
            'leave_request[reason]' => 'Something New',
            'leave_request[status]' => 'Something New',
            'leave_request[users]' => 'Something New',
        ]);

        self::assertResponseRedirects('/leave/request/');

        $fixture = $this->leaveRequestRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getStartDate_at());
        self::assertSame('Something New', $fixture[0]->getEndDate_at());
        self::assertSame('Something New', $fixture[0]->getReason());
        self::assertSame('Something New', $fixture[0]->getStatus());
        self::assertSame('Something New', $fixture[0]->getUsers());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new LeaveRequest();
        $fixture->setStartDate_at('Value');
        $fixture->setEndDate_at('Value');
        $fixture->setReason('Value');
        $fixture->setStatus('Value');
        $fixture->setUsers('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/leave/request/');
        self::assertSame(0, $this->leaveRequestRepository->count([]));
    }
}
