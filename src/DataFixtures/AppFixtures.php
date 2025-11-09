<?php

namespace App\DataFixtures;

use App\Entity\Attendance;
use App\Entity\Client;
use App\Entity\Department;
use App\Entity\Inventory;
use App\Entity\Invoice;
use App\Entity\LeaveRequest;
use App\Entity\Notification;
use App\Entity\PayRoll;
use App\Entity\Project;
use App\Entity\Settings;
use App\Entity\Supplier;
use App\Entity\Task;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Create Settings
        $settings = new Settings();
        $settings->setCompanyName('TechCorp Solutions');
        $settings->setLogoPath('/uploads/logo.png');
        $settings->setCurrency('USD');
        $settings->setAddress('123 Business St, Tech City, TC 12345');
        $settings->setEmail('contact@techcorp.com');
        $settings->setPhone('+1-555-0100');
        $settings->setUpdatedAt(new \DateTimeImmutable());
        $manager->persist($settings);

        // Create Departments
        $departments = [];
        $departmentData = [
            ['name' => 'Engineering', 'description' => 'Software development and technical operations'],
            ['name' => 'Sales', 'description' => 'Client acquisition and business development'],
            ['name' => 'Marketing', 'description' => 'Brand management and promotional activities'],
            ['name' => 'Human Resources', 'description' => 'Employee management and recruitment'],
            ['name' => 'Finance', 'description' => 'Financial planning and accounting'],
        ];

        foreach ($departmentData as $data) {
            $department = new Department();
            $department->setName($data['name']);
            $department->setDescription($data['description']);
            $department->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($department);
            $departments[] = $department;
        }

        // Create PayRolls
        $payrolls = [];
        $salaries = [75000, 85000, 65000, 95000, 70000, 80000, 90000, 72000];
        $months = ['January', 'February', 'March', 'April', 'May', 'June'];

        for ($i = 0; $i < 8; $i++) {
            $payroll = new PayRoll();
            $payroll->setBaseSalary($salaries[$i]);
            $payroll->setBonus(rand(0, 5000));
            $payroll->setDeduction(rand(100, 1000));
            $payroll->setMonth($months[array_rand($months)]);
            $payroll->setPaymentDateAt(new \DateTimeImmutable('-' . rand(1, 30) . ' days'));
            $payroll->setStatus(['pending', 'paid', 'processing'][rand(0, 2)]);
            $manager->persist($payroll);
            $payrolls[] = $payroll;
        }

        // Create Attendance records
        $attendances = [];
        for ($i = 0; $i < 8; $i++) {
            $attendance = new Attendance();
            $date = new \DateTimeImmutable('-' . rand(1, 30) . ' days');
            $checkIn = $date->setTime(8 + rand(0, 2), rand(0, 59));
            $checkOut = $checkIn->setTime(17 + rand(0, 3), rand(0, 59));
            
            $attendance->setCheckInAt($checkIn);
            $attendance->setCheckOutAt($checkOut);
            $attendance->setTotalHours(($checkOut->getTimestamp() - $checkIn->getTimestamp()) / 3600);
            $attendance->setDateAt($date);
            $manager->persist($attendance);
            $attendances[] = $attendance;
        }

        // Create Users
        $users = [];
        $userData = [
            ['email' => 'admin@techcorp.com', 'firstName' => 'John', 'lastName' => 'Admin', 'roles' => ['ROLE_ADMIN']],
            ['email' => 'sarah.manager@techcorp.com', 'firstName' => 'Sarah', 'lastName' => 'Manager', 'roles' => ['ROLE_MANAGER']],
            ['email' => 'mike.dev@techcorp.com', 'firstName' => 'Mike', 'lastName' => 'Developer', 'roles' => ['ROLE_USER']],
            ['email' => 'emily.sales@techcorp.com', 'firstName' => 'Emily', 'lastName' => 'Sales', 'roles' => ['ROLE_USER']],
            ['email' => 'david.marketing@techcorp.com', 'firstName' => 'David', 'lastName' => 'Marketing', 'roles' => ['ROLE_USER']],
            ['email' => 'lisa.hr@techcorp.com', 'firstName' => 'Lisa', 'lastName' => 'HR', 'roles' => ['ROLE_USER']],
            ['email' => 'robert.finance@techcorp.com', 'firstName' => 'Robert', 'lastName' => 'Finance', 'roles' => ['ROLE_USER']],
            ['email' => 'anna.dev@techcorp.com', 'firstName' => 'Anna', 'lastName' => 'Developer', 'roles' => ['ROLE_USER']],
        ];

        foreach ($userData as $index => $data) {
            $user = new Users();
            $user->setEmail($data['email']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
            $user->setRoles($data['roles']);
            $user->setFirstName($data['firstName']);
            $user->setLastName($data['lastName']);
            $user->setCreatedAt(new \DateTimeImmutable('-' . rand(30, 365) . ' days'));
            $user->setDepartment($departments[array_rand($departments)]);
            $user->setPayRoll($payrolls[$index]);
            $user->setAttendance($attendances[$index]);
            $manager->persist($user);
            $users[] = $user;
        }

        // Create Clients
        $clients = [];
        $clientData = [
            ['name' => 'Acme Corporation', 'email' => 'contact@acme.com', 'phone' => '+1-555-0200', 'address' => '456 Client Ave, Business City'],
            ['name' => 'Global Innovations Ltd', 'email' => 'info@globalinn.com', 'phone' => '+1-555-0300', 'address' => '789 Innovation Blvd'],
            ['name' => 'StartUp Ventures', 'email' => 'hello@startup.com', 'phone' => '+1-555-0400', 'address' => '321 Startup Lane'],
            ['name' => 'Enterprise Solutions Inc', 'email' => 'sales@enterprise.com', 'phone' => '+1-555-0500', 'address' => '654 Enterprise Road'],
        ];

        foreach ($clientData as $data) {
            $client = new Client();
            $client->setName($data['name']);
            $client->setEmail($data['email']);
            $client->setPhoneNumber($data['phone']);
            $client->setAdress($data['address']);
            $client->setCreatedAt(new \DateTimeImmutable('-' . rand(60, 365) . ' days'));
            $manager->persist($client);
            $clients[] = $client;
        }

        // Create Suppliers
        $suppliers = [];
        $supplierData = [
            ['name' => 'Tech Hardware Supply', 'email' => 'sales@techhardware.com', 'phone' => '+1-555-1000', 'address' => '100 Supply St'],
            ['name' => 'Office Essentials Co', 'email' => 'info@officeessentials.com', 'phone' => '+1-555-1100', 'address' => '200 Office Park'],
            ['name' => 'Digital Components Ltd', 'email' => 'orders@digitalcomp.com', 'phone' => '+1-555-1200', 'address' => '300 Tech Plaza'],
        ];

        foreach ($supplierData as $data) {
            $supplier = new Supplier();
            $supplier->setName($data['name']);
            $supplier->setContactInfo(['website' => 'www.' . strtolower(str_replace(' ', '', $data['name'])) . '.com', 'fax' => '+1-555-9999']);
            $supplier->setEmail($data['email']);
            $supplier->setPhoneNumber($data['phone']);
            $supplier->setAdress($data['address']);
            $manager->persist($supplier);
            $suppliers[] = $supplier;
        }

        // Create Inventory
        $inventories = [];
        $inventoryData = [
            ['item_name' => 'Laptop Dell XPS 15', 'sku' => 'LAP-DELL-001', 'quantity' => 25, 'price' => 1299.99],
            ['item_name' => 'Monitor Samsung 27"', 'sku' => 'MON-SAM-002', 'quantity' => 40, 'price' => 349.99],
            ['item_name' => 'Keyboard Mechanical', 'sku' => 'KEY-MECH-003', 'quantity' => 60, 'price' => 89.99],
            ['item_name' => 'Mouse Wireless', 'sku' => 'MOU-WIRE-004', 'quantity' => 80, 'price' => 29.99],
            ['item_name' => 'Webcam HD', 'sku' => 'WEB-HD-005', 'quantity' => 35, 'price' => 79.99],
            ['item_name' => 'Headset Professional', 'sku' => 'HEAD-PRO-006', 'quantity' => 50, 'price' => 149.99],
        ];

        foreach ($inventoryData as $data) {
            $inventory = new Inventory();
            $inventory->setItemName($data['item_name']);
            $inventory->setSku($data['sku']);
            $inventory->setQuantity($data['quantity']);
            $inventory->setPrice($data['price']);
            $inventory->setSupplierName($suppliers[array_rand($suppliers)]->getName());
            $inventory->setLastUpdatedAt(new \DateTimeImmutable());
            $inventory->setSupplier($suppliers[array_rand($suppliers)]);
            $inventory->setUsers($users[array_rand($users)]);
            $manager->persist($inventory);
            $inventories[] = $inventory;
        }

        // Create Projects
        $projects = [];
        $projectData = [
            ['title' => 'E-commerce Platform Development', 'description' => 'Build a modern e-commerce solution', 'budget' => 150000, 'status' => 'In Progress'],
            ['title' => 'Mobile App Redesign', 'description' => 'Redesign existing mobile application', 'budget' => 75000, 'status' => 'Planning'],
            ['title' => 'CRM Integration', 'description' => 'Integrate CRM system with existing tools', 'budget' => 50000, 'status' => 'Completed'],
            ['title' => 'Data Analytics Dashboard', 'description' => 'Create comprehensive analytics dashboard', 'budget' => 100000, 'status' => 'In Progress'],
            ['title' => 'Security Audit', 'description' => 'Complete security audit and implementation', 'budget' => 60000, 'status' => 'Pending'],
        ];

        foreach ($projectData as $data) {
            $project = new Project();
            $project->setTitle($data['title']);
            $project->setDescription($data['description']);
            $project->setStartDateAt(new \DateTimeImmutable('-' . rand(30, 180) . ' days'));
            $project->setEndDateAt(new \DateTimeImmutable('+' . rand(30, 180) . ' days'));
            $project->setBudget($data['budget']);
            $project->setStatus($data['status']);
            $project->setClient($clients[array_rand($clients)]);
            
            // Add users to project
            $projectUsers = array_rand($users, rand(2, 4));
            if (!is_array($projectUsers)) {
                $projectUsers = [$projectUsers];
            }
            foreach ($projectUsers as $userIndex) {
                $project->addUser($users[$userIndex]);
            }
            
            // Add inventory to project
            $projectInventories = array_rand($inventories, rand(1, 3));
            if (!is_array($projectInventories)) {
                $projectInventories = [$projectInventories];
            }
            foreach ($projectInventories as $invIndex) {
                $project->addInventory($inventories[$invIndex]);
            }
            
            $manager->persist($project);
            $projects[] = $project;
        }

        // Create Tasks
        $taskData = [
            ['title' => 'Design database schema', 'description' => 'Create comprehensive database design', 'status' => 'Completed', 'priority' => 'high'],
            ['title' => 'Implement authentication', 'description' => 'Build user authentication system', 'status' => 'In Progress', 'priority' => 'high'],
            ['title' => 'Create API endpoints', 'description' => 'Develop RESTful API endpoints', 'status' => 'In Progress', 'priority' => 'medium'],
            ['title' => 'Write unit tests', 'description' => 'Implement comprehensive unit testing', 'status' => 'Pending', 'priority' => 'medium'],
            ['title' => 'Deploy to staging', 'description' => 'Deploy application to staging environment', 'status' => 'Pending', 'priority' => 'low'],
            ['title' => 'UI/UX improvements', 'description' => 'Enhance user interface and experience', 'status' => 'In Progress', 'priority' => 'high'],
            ['title' => 'Performance optimization', 'description' => 'Optimize application performance', 'status' => 'Pending', 'priority' => 'medium'],
            ['title' => 'Documentation', 'description' => 'Write technical documentation', 'status' => 'Pending', 'priority' => 'low'],
        ];

        foreach ($taskData as $data) {
            $task = new Task();
            $task->setTitle($data['title']);
            $task->setDescription($data['description']);
            $task->setStatus($data['status']);
            $task->setPriority($data['priority']);
            $task->setDeadLineAt(new \DateTimeImmutable('+' . rand(7, 90) . ' days'));
            $task->setProject($projects[array_rand($projects)]);
            $task->setAssignedTo($users[array_rand($users)]);
            $manager->persist($task);
        }

        // Create Invoices
        foreach ($projects as $index => $project) {
            $invoice = new Invoice();
            $invoice->setInvoiceNumber('INV-' . str_pad($index + 1, 5, '0', STR_PAD_LEFT));
            $invoice->setIssueDateAt(new \DateTimeImmutable('-' . rand(1, 30) . ' days'));
            $invoice->setDueDateAt(new \DateTimeImmutable('+' . rand(15, 45) . ' days'));
            $invoice->setTotalAmount($project->getBudget() * rand(20, 50) / 100);
            $invoice->setStatus(['pending', 'paid', 'overdue'][rand(0, 2)]);
            $invoice->setClient($project->getClient());
            $invoice->setProject($project);
            $invoice->setAuthor($users[rand(0, 1)]);
            $manager->persist($invoice);
        }

        // Create Leave Requests
        $leaveReasons = [
            'Annual vacation leave',
            'Medical appointment',
            'Family emergency',
            'Personal day',
            'Sick leave',
            'Wedding ceremony',
        ];

        for ($i = 0; $i < 10; $i++) {
            $leaveRequest = new LeaveRequest();
            $leaveRequest->setReason($leaveReasons[array_rand($leaveReasons)]);
            $leaveRequest->setStatus(['pending', 'approved', 'rejected'][rand(0, 2)]);
            $startDate = new \DateTimeImmutable('+' . rand(1, 60) . ' days');
            $endDate = $startDate->modify('+' . rand(1, 10) . ' days');
            $leaveRequest->setStartDateAt($startDate);
            $leaveRequest->setEndDateAt($endDate);
            $leaveRequest->setUsers($users[array_rand($users)]);
            $manager->persist($leaveRequest);
        }

        // Create Notifications
        $notificationData = [
            ['title' => 'New Project Assigned', 'message' => 'You have been assigned to a new project.'],
            ['title' => 'Task Deadline Approaching', 'message' => 'Your task is due in 3 days.'],
            ['title' => 'Leave Request Approved', 'message' => 'Your leave request has been approved.'],
            ['title' => 'Invoice Payment Received', 'message' => 'Payment received for invoice INV-00001.'],
            ['title' => 'Team Meeting Reminder', 'message' => 'Team meeting scheduled for tomorrow at 10 AM.'],
            ['title' => 'Payroll Processed', 'message' => 'Your salary for this month has been processed.'],
            ['title' => 'New Task Assigned', 'message' => 'A new task has been assigned to you.'],
            ['title' => 'Project Milestone Reached', 'message' => 'Project milestone successfully completed.'],
        ];

        foreach ($notificationData as $data) {
            $notification = new Notification();
            $notification->setTitle($data['title']);
            $notification->setMessage($data['message']);
            $notification->setIsRead((bool)rand(0, 1));
            $notification->setCreatedAt(new \DateTimeImmutable('-' . rand(1, 30) . ' days'));
            $notification->setReceiver($users[array_rand($users)]);
            $notification->setSender(rand(0, 1) ? $users[array_rand($users)] : null);
            $manager->persist($notification);
        }

        $manager->flush();
    }
}