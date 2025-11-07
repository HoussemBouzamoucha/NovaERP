<?php

namespace App\Tests\Controller;

use App\Entity\Invoice;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class InvoiceControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $invoiceRepository;
    private string $path = '/invoice/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->invoiceRepository = $this->manager->getRepository(Invoice::class);

        foreach ($this->invoiceRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Invoice index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'invoice[invoiceNumber]' => 'Testing',
            'invoice[issueDate_at]' => 'Testing',
            'invoice[dueDate_at]' => 'Testing',
            'invoice[totalAmount]' => 'Testing',
            'invoice[status]' => 'Testing',
            'invoice[author]' => 'Testing',
            'invoice[Client]' => 'Testing',
            'invoice[Project]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->invoiceRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Invoice();
        $fixture->setInvoiceNumber('My Title');
        $fixture->setIssueDate_at('My Title');
        $fixture->setDueDate_at('My Title');
        $fixture->setTotalAmount('My Title');
        $fixture->setStatus('My Title');
        $fixture->setAuthor('My Title');
        $fixture->setClient('My Title');
        $fixture->setProject('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Invoice');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Invoice();
        $fixture->setInvoiceNumber('Value');
        $fixture->setIssueDate_at('Value');
        $fixture->setDueDate_at('Value');
        $fixture->setTotalAmount('Value');
        $fixture->setStatus('Value');
        $fixture->setAuthor('Value');
        $fixture->setClient('Value');
        $fixture->setProject('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'invoice[invoiceNumber]' => 'Something New',
            'invoice[issueDate_at]' => 'Something New',
            'invoice[dueDate_at]' => 'Something New',
            'invoice[totalAmount]' => 'Something New',
            'invoice[status]' => 'Something New',
            'invoice[author]' => 'Something New',
            'invoice[Client]' => 'Something New',
            'invoice[Project]' => 'Something New',
        ]);

        self::assertResponseRedirects('/invoice/');

        $fixture = $this->invoiceRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getInvoiceNumber());
        self::assertSame('Something New', $fixture[0]->getIssueDate_at());
        self::assertSame('Something New', $fixture[0]->getDueDate_at());
        self::assertSame('Something New', $fixture[0]->getTotalAmount());
        self::assertSame('Something New', $fixture[0]->getStatus());
        self::assertSame('Something New', $fixture[0]->getAuthor());
        self::assertSame('Something New', $fixture[0]->getClient());
        self::assertSame('Something New', $fixture[0]->getProject());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Invoice();
        $fixture->setInvoiceNumber('Value');
        $fixture->setIssueDate_at('Value');
        $fixture->setDueDate_at('Value');
        $fixture->setTotalAmount('Value');
        $fixture->setStatus('Value');
        $fixture->setAuthor('Value');
        $fixture->setClient('Value');
        $fixture->setProject('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/invoice/');
        self::assertSame(0, $this->invoiceRepository->count([]));
    }
}
