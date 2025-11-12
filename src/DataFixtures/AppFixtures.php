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
            ['name' => 'Administration', 'description' => 'System administration and management'],
            ['name' => 'Human Resources', 'description' => 'HR, payroll, and recruitment management'],
            ['name' => 'Finance & Accounting', 'description' => 'Financial operations and accounting'],
            ['name' => 'Sales & Marketing', 'description' => 'Sales and customer relationship management'],
            ['name' => 'Procurement & Inventory', 'description' => 'Procurement and inventory operations'],
            ['name' => 'Production', 'description' => 'Production and quality control'],
            ['name' => 'Project Management', 'description' => 'Project planning and execution'],
            ['name' => 'General Staff', 'description' => 'General employees and contributors'],
        ];

        foreach ($departmentData as $data) {
            $department = new Department();
            $department->setName($data['name']);
            $department->setDescription($data['description']);
            $department->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($department);
            $departments[] = $department;
        }

        // Create PayRolls (28 users total: 1 super admin + 27 others)
        $payrolls = [];
        $salaries = [
            // Super Admin
            180000,
            // Admins (3)
            140000, 135000, 130000,
            // HR (3)
            110000, 105000, 100000,
            // Finance (3)
            120000, 115000, 110000,
            // Sales (3)
            105000, 100000, 95000,
            // Procurement (3)
            95000, 90000, 88000,
            // Production (3)
            100000, 95000, 92000,
            // Project Managers (3)
            115000, 110000, 108000,
            // Staff (6)
            75000, 72000, 70000, 68000, 65000, 62000,
            // Guests (3)
            50000, 48000, 45000,
        ];
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October'];

        foreach ($salaries as $salary) {
            $payroll = new PayRoll();
            $payroll->setBaseSalary($salary);
            $payroll->setBonus(rand(0, $salary * 0.1));
            $payroll->setDeduction(rand(500, 2000));
            $payroll->setMonth($months[array_rand($months)]);
            $payroll->setPaymentDateAt(new \DateTimeImmutable('-' . rand(1, 30) . ' days'));
            $payroll->setStatus(['pending', 'paid', 'processing'][rand(0, 2)]);
            $manager->persist($payroll);
            $payrolls[] = $payroll;
        }

        // Create Attendance records
        $attendances = [];
        foreach ($salaries as $index => $salary) {
            $attendance = new Attendance();
            $date = new \DateTimeImmutable('-' . rand(1, 30) . ' days');
            $checkIn = $date->setTime(8 + rand(0, 1), rand(0, 59));
            $checkOut = $checkIn->setTime(17 + rand(0, 2), rand(0, 59));
            
            $attendance->setCheckInAt($checkIn);
            $attendance->setCheckOutAt($checkOut);
            $attendance->setTotalHours(($checkOut->getTimestamp() - $checkIn->getTimestamp()) / 3600);
            $attendance->setDateAt($date);
            $manager->persist($attendance);
            $attendances[] = $attendance;
        }

        // Create Users with simplified roles
        $users = [];
        $userData = [
            // ROLE_SUPER_ADMIN (1 user) - Department 0
            ['email' => 'superadmin@techcorp.com', 'firstName' => 'Alex', 'lastName' => 'Supreme', 'roles' => ['ROLE_SUPER_ADMIN'], 'dept' => 0],
            
            // ROLE_ADMIN (3 users) - Department 0
            ['email' => 'admin1@techcorp.com', 'firstName' => 'Sarah', 'lastName' => 'Administrator', 'roles' => ['ROLE_ADMIN'], 'dept' => 0],
            ['email' => 'admin2@techcorp.com', 'firstName' => 'Michael', 'lastName' => 'Systems', 'roles' => ['ROLE_ADMIN'], 'dept' => 0],
            ['email' => 'admin3@techcorp.com', 'firstName' => 'Jennifer', 'lastName' => 'Config', 'roles' => ['ROLE_ADMIN'], 'dept' => 0],
            
            // ROLE_HR (3 users) - Department 1
            ['email' => 'hr1@techcorp.com', 'firstName' => 'Emily', 'lastName' => 'Harrison', 'roles' => ['ROLE_HR'], 'dept' => 1],
            ['email' => 'hr2@techcorp.com', 'firstName' => 'David', 'lastName' => 'Personnel', 'roles' => ['ROLE_HR'], 'dept' => 1],
            ['email' => 'hr3@techcorp.com', 'firstName' => 'Lisa', 'lastName' => 'Recruitment', 'roles' => ['ROLE_HR'], 'dept' => 1],
            
            // ROLE_FINANCE (3 users) - Department 2
            ['email' => 'finance1@techcorp.com', 'firstName' => 'Robert', 'lastName' => 'Treasury', 'roles' => ['ROLE_FINANCE'], 'dept' => 2],
            ['email' => 'finance2@techcorp.com', 'firstName' => 'Anna', 'lastName' => 'Accounting', 'roles' => ['ROLE_FINANCE'], 'dept' => 2],
            ['email' => 'finance3@techcorp.com', 'firstName' => 'James', 'lastName' => 'Numbers', 'roles' => ['ROLE_FINANCE'], 'dept' => 2],
            
            // ROLE_SALES (3 users) - Department 3
            ['email' => 'sales1@techcorp.com', 'firstName' => 'Maria', 'lastName' => 'Martinez', 'roles' => ['ROLE_SALES'], 'dept' => 3],
            ['email' => 'sales2@techcorp.com', 'firstName' => 'Thomas', 'lastName' => 'Business', 'roles' => ['ROLE_SALES'], 'dept' => 3],
            ['email' => 'sales3@techcorp.com', 'firstName' => 'Patricia', 'lastName' => 'Customer', 'roles' => ['ROLE_SALES'], 'dept' => 3],
            
            // ROLE_PROCUREMENT (3 users) - Department 4
            ['email' => 'procurement1@techcorp.com', 'firstName' => 'William', 'lastName' => 'Buyer', 'roles' => ['ROLE_PROCUREMENT'], 'dept' => 4],
            ['email' => 'procurement2@techcorp.com', 'firstName' => 'Elizabeth', 'lastName' => 'Warehouse', 'roles' => ['ROLE_PROCUREMENT'], 'dept' => 4],
            ['email' => 'procurement3@techcorp.com', 'firstName' => 'Christopher', 'lastName' => 'Inventory', 'roles' => ['ROLE_PROCUREMENT'], 'dept' => 4],
            
            // ROLE_PRODUCTION (3 users) - Department 5
            ['email' => 'production1@techcorp.com', 'firstName' => 'Jessica', 'lastName' => 'Operations', 'roles' => ['ROLE_PRODUCTION'], 'dept' => 5],
            ['email' => 'production2@techcorp.com', 'firstName' => 'Daniel', 'lastName' => 'Quality', 'roles' => ['ROLE_PRODUCTION'], 'dept' => 5],
            ['email' => 'production3@techcorp.com', 'firstName' => 'Nancy', 'lastName' => 'Supervisor', 'roles' => ['ROLE_PRODUCTION'], 'dept' => 5],
            
            // ROLE_PROJECT_MANAGER (3 users) - Department 6
            ['email' => 'pm1@techcorp.com', 'firstName' => 'Matthew', 'lastName' => 'Projects', 'roles' => ['ROLE_PROJECT_MANAGER'], 'dept' => 6],
            ['email' => 'pm2@techcorp.com', 'firstName' => 'Karen', 'lastName' => 'Planning', 'roles' => ['ROLE_PROJECT_MANAGER'], 'dept' => 6],
            ['email' => 'pm3@techcorp.com', 'firstName' => 'Steven', 'lastName' => 'Execution', 'roles' => ['ROLE_PROJECT_MANAGER'], 'dept' => 6],
            
            // ROLE_STAFF (6 users) - Department 7
            ['email' => 'staff1@techcorp.com', 'firstName' => 'Betty', 'lastName' => 'Worker', 'roles' => ['ROLE_STAFF'], 'dept' => 7],
            ['email' => 'staff2@techcorp.com', 'firstName' => 'Richard', 'lastName' => 'Employee', 'roles' => ['ROLE_STAFF'], 'dept' => 7],
            ['email' => 'staff3@techcorp.com', 'firstName' => 'Susan', 'lastName' => 'Contributor', 'roles' => ['ROLE_STAFF'], 'dept' => 7],
            ['email' => 'staff4@techcorp.com', 'firstName' => 'Joseph', 'lastName' => 'Assistant', 'roles' => ['ROLE_STAFF'], 'dept' => 7],
            ['email' => 'staff5@techcorp.com', 'firstName' => 'Linda', 'lastName' => 'Support', 'roles' => ['ROLE_STAFF'], 'dept' => 7],
            ['email' => 'staff6@techcorp.com', 'firstName' => 'Charles', 'lastName' => 'Helper', 'roles' => ['ROLE_STAFF'], 'dept' => 7],
            
            // ROLE_GUEST (3 users) - Department 7
            ['email' => 'guest1@techcorp.com', 'firstName' => 'Mark', 'lastName' => 'Observer', 'roles' => ['ROLE_GUEST'], 'dept' => 7],
            ['email' => 'guest2@techcorp.com', 'firstName' => 'Barbara', 'lastName' => 'Visitor', 'roles' => ['ROLE_GUEST'], 'dept' => 7],
            ['email' => 'guest3@techcorp.com', 'firstName' => 'Paul', 'lastName' => 'ReadOnly', 'roles' => ['ROLE_GUEST'], 'dept' => 7],
        ];

        foreach ($userData as $index => $data) {
            $user = new Users();
            $user->setEmail($data['email']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
            $user->setRoles($data['roles']);
            $user->setFirstName($data['firstName']);
            $user->setLastName($data['lastName']);
            $user->setCreatedAt(new \DateTimeImmutable('-' . rand(30, 365) . ' days'));
            $user->setDepartment($departments[$data['dept']]);
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
            ['name' => 'Tech Innovators Co', 'email' => 'info@techinnovators.com', 'phone' => '+1-555-0600', 'address' => '987 Tech Street'],
            ['name' => 'Digital Solutions Group', 'email' => 'contact@digitalsolutions.com', 'phone' => '+1-555-0700', 'address' => '147 Digital Ave'],
            ['name' => 'Future Systems LLC', 'email' => 'business@futuresys.com', 'phone' => '+1-555-0800', 'address' => '258 Future Pkwy'],
            ['name' => 'Smart Industries', 'email' => 'info@smartind.com', 'phone' => '+1-555-0900', 'address' => '369 Smart Blvd'],
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
            ['name' => 'Industrial Materials Inc', 'email' => 'sales@industrial.com', 'phone' => '+1-555-1300', 'address' => '400 Industrial Way'],
            ['name' => 'Business Equipment Co', 'email' => 'info@busequip.com', 'phone' => '+1-555-1400', 'address' => '500 Equipment Rd'],
            ['name' => 'Quality Parts Supplier', 'email' => 'orders@qualityparts.com', 'phone' => '+1-555-1500', 'address' => '600 Parts Ave'],
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
            ['item_name' => 'Printer Laser Color', 'sku' => 'PRI-LAS-007', 'quantity' => 15, 'price' => 599.99],
            ['item_name' => 'Scanner Document', 'sku' => 'SCA-DOC-008', 'quantity' => 20, 'price' => 299.99],
            ['item_name' => 'Desk Ergonomic', 'sku' => 'DSK-ERG-009', 'quantity' => 30, 'price' => 499.99],
            ['item_name' => 'Chair Office Executive', 'sku' => 'CHR-OFF-010', 'quantity' => 45, 'price' => 349.99],
            ['item_name' => 'Router Network Pro', 'sku' => 'NET-ROU-011', 'quantity' => 28, 'price' => 199.99],
            ['item_name' => 'UPS Battery Backup', 'sku' => 'UPS-BAK-012', 'quantity' => 32, 'price' => 249.99],
        ];

        // Get procurement users for inventory
        $procurementUsers = array_filter($users, fn($u) => in_array('ROLE_PROCUREMENT', $u->getRoles()));

        foreach ($inventoryData as $data) {
            $inventory = new Inventory();
            $inventory->setItemName($data['item_name']);
            $inventory->setSku($data['sku']);
            $inventory->setQuantity($data['quantity']);
            $inventory->setPrice($data['price']);
            $inventory->setSupplierName($suppliers[array_rand($suppliers)]->getName());
            $inventory->setLastUpdatedAt(new \DateTimeImmutable());
            $inventory->setSupplier($suppliers[array_rand($suppliers)]);
            $inventory->setUsers($procurementUsers[array_rand($procurementUsers)]);
            $manager->persist($inventory);
            $inventories[] = $inventory;
        }

        // Create Projects
        $projects = [];
        $projectData = [
            ['title' => 'E-commerce Platform Development', 'description' => 'Build a modern e-commerce solution with payment integration', 'budget' => 150000, 'status' => 'In Progress'],
            ['title' => 'Mobile App Redesign', 'description' => 'Complete redesign of existing mobile application', 'budget' => 75000, 'status' => 'Planning'],
            ['title' => 'CRM Integration', 'description' => 'Integrate CRM system with existing tools and workflows', 'budget' => 50000, 'status' => 'Completed'],
            ['title' => 'Data Analytics Dashboard', 'description' => 'Create comprehensive analytics dashboard for business intelligence', 'budget' => 100000, 'status' => 'In Progress'],
            ['title' => 'Security Audit & Implementation', 'description' => 'Complete security audit and implementation of recommendations', 'budget' => 60000, 'status' => 'Pending'],
            ['title' => 'ERP System Implementation', 'description' => 'Implement enterprise resource planning system', 'budget' => 200000, 'status' => 'In Progress'],
            ['title' => 'Inventory Management System', 'description' => 'Develop automated inventory tracking system', 'budget' => 80000, 'status' => 'Planning'],
            ['title' => 'Payroll System Upgrade', 'description' => 'Upgrade and modernize payroll processing system', 'budget' => 90000, 'status' => 'In Progress'],
            ['title' => 'Cloud Migration Project', 'description' => 'Migrate legacy systems to cloud infrastructure', 'budget' => 120000, 'status' => 'Planning'],
            ['title' => 'Customer Portal Development', 'description' => 'Build customer self-service portal', 'budget' => 65000, 'status' => 'In Progress'],
        ];

        // Get project managers and staff
        $projectManagers = array_filter($users, fn($u) => in_array('ROLE_PROJECT_MANAGER', $u->getRoles()));
        $staffMembers = array_filter($users, fn($u) => in_array('ROLE_STAFF', $u->getRoles()));
        $allContributors = array_merge($projectManagers, $staffMembers);

        foreach ($projectData as $data) {
            $project = new Project();
            $project->setTitle($data['title']);
            $project->setDescription($data['description']);
            $project->setStartDateAt(new \DateTimeImmutable('-' . rand(30, 180) . ' days'));
            $project->setEndDateAt(new \DateTimeImmutable('+' . rand(30, 180) . ' days'));
            $project->setBudget($data['budget']);
            $project->setStatus($data['status']);
            $project->setClient($clients[array_rand($clients)]);
            
            // Add project manager
            $project->addUser($projectManagers[array_rand($projectManagers)]);
            
            // Add staff contributors
            $numContributors = rand(2, 5);
            $selectedContributors = array_rand($allContributors, min($numContributors, count($allContributors)));
            if (!is_array($selectedContributors)) {
                $selectedContributors = [$selectedContributors];
            }
            foreach ($selectedContributors as $contributorIndex) {
                $project->addUser($allContributors[$contributorIndex]);
            }
            
            // Add inventory to project
            $projectInventories = array_rand($inventories, rand(2, 6));
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
            ['title' => 'Design database schema', 'description' => 'Create comprehensive database design with ERD diagrams', 'status' => 'Completed', 'priority' => 'high'],
            ['title' => 'Implement authentication', 'description' => 'Build user authentication system with 2FA', 'status' => 'In Progress', 'priority' => 'high'],
            ['title' => 'Create API endpoints', 'description' => 'Develop RESTful API endpoints for all modules', 'status' => 'In Progress', 'priority' => 'medium'],
            ['title' => 'Write unit tests', 'description' => 'Implement comprehensive unit testing coverage', 'status' => 'Pending', 'priority' => 'medium'],
            ['title' => 'Deploy to staging', 'description' => 'Deploy application to staging environment', 'status' => 'Pending', 'priority' => 'low'],
            ['title' => 'UI/UX improvements', 'description' => 'Enhance user interface and experience based on feedback', 'status' => 'In Progress', 'priority' => 'high'],
            ['title' => 'Performance optimization', 'description' => 'Optimize application performance and reduce load times', 'status' => 'Pending', 'priority' => 'medium'],
            ['title' => 'Documentation', 'description' => 'Write comprehensive technical documentation', 'status' => 'Pending', 'priority' => 'low'],
            ['title' => 'Security testing', 'description' => 'Conduct security penetration testing', 'status' => 'In Progress', 'priority' => 'high'],
            ['title' => 'Database optimization', 'description' => 'Optimize database queries and add proper indexes', 'status' => 'Pending', 'priority' => 'medium'],
            ['title' => 'Integration testing', 'description' => 'Test all third-party integrations', 'status' => 'In Progress', 'priority' => 'high'],
            ['title' => 'User training materials', 'description' => 'Create training documentation for end users', 'status' => 'Pending', 'priority' => 'low'],
            ['title' => 'Code review', 'description' => 'Review and refactor existing codebase', 'status' => 'In Progress', 'priority' => 'medium'],
            ['title' => 'Bug fixing sprint', 'description' => 'Address all critical and high-priority bugs', 'status' => 'In Progress', 'priority' => 'high'],
            ['title' => 'Mobile responsiveness', 'description' => 'Ensure full mobile responsiveness', 'status' => 'Pending', 'priority' => 'medium'],
        ];

        foreach ($taskData as $data) {
            $task = new Task();
            $task->setTitle($data['title']);
            $task->setDescription($data['description']);
            $task->setStatus($data['status']);
            $task->setPriority($data['priority']);
            $task->setDeadLineAt(new \DateTimeImmutable('+' . rand(7, 90) . ' days'));
            $task->setProject($projects[array_rand($projects)]);
            // Assign to project managers or staff
            $task->setAssignedTo($allContributors[array_rand($allContributors)]);
            $manager->persist($task);
        }

        // Create Invoices
        // Get finance users for invoice creation
        $financeUsers = array_filter($users, fn($u) => in_array('ROLE_FINANCE', $u->getRoles()));

        foreach ($projects as $index => $project) {
            // Create 1-2 invoices per project
            $numInvoices = rand(1, 2);
            for ($i = 0; $i < $numInvoices; $i++) {
                $invoice = new Invoice();
                $invoice->setInvoiceNumber('INV-' . str_pad(($index * 2 + $i + 1), 5, '0', STR_PAD_LEFT));
                $invoice->setIssueDateAt(new \DateTimeImmutable('-' . rand(1, 60) . ' days'));
                $invoice->setDueDateAt(new \DateTimeImmutable('+' . rand(15, 45) . ' days'));
                $invoice->setTotalAmount($project->getBudget() * rand(15, 40) / 100);
                $invoice->setStatus(['pending', 'paid', 'overdue'][rand(0, 2)]);
                $invoice->setClient($project->getClient());
                $invoice->setProject($project);
                $invoice->setAuthor($financeUsers[array_rand($financeUsers)]);
                $manager->persist($invoice);
            }
        }

        // Create Leave Requests
        $leaveReasons = [
            'Annual vacation leave',
            'Medical appointment',
            'Family emergency',
            'Personal day',
            'Sick leave',
            'Wedding ceremony',
            'Maternity/Paternity leave',
            'Bereavement leave',
            'Professional development',
            'Religious observance',
            'Mental health day',
            'Moving/Relocation',
        ];

        // Get HR users to approve/reject
        $hrUsers = array_filter($users, fn($u) => in_array('ROLE_HR', $u->getRoles()));

        for ($i = 0; $i < 25; $i++) {
            $leaveRequest = new LeaveRequest();
            $leaveRequest->setReason($leaveReasons[array_rand($leaveReasons)]);
            $leaveRequest->setStatus(['pending', 'approved', 'rejected'][rand(0, 2)]);
            $startDate = new \DateTimeImmutable('+' . rand(1, 90) . ' days');
            $endDate = $startDate->modify('+' . rand(1, 14) . ' days');
            $leaveRequest->setStartDateAt($startDate);
            $leaveRequest->setEndDateAt($endDate);
            $leaveRequest->setUsers($users[array_rand($users)]);
            $manager->persist($leaveRequest);
        }

        // Create Notifications
        $notificationData = [
            ['title' => 'New Project Assigned', 'message' => 'You have been assigned to a new project. Please review the project details.'],
            ['title' => 'Task Deadline Approaching', 'message' => 'Your task "Implement authentication" is due in 3 days.'],
            ['title' => 'Leave Request Approved', 'message' => 'Your leave request for next week has been approved by HR.'],
            ['title' => 'Invoice Payment Received', 'message' => 'Payment received for invoice INV-00001 from Acme Corporation.'],
            ['title' => 'Team Meeting Reminder', 'message' => 'Team meeting scheduled for tomorrow at 10 AM in Conference Room A.'],
            ['title' => 'Payroll Processed', 'message' => 'Your salary for this month has been processed and will be deposited on the 25th.'],
            ['title' => 'New Task Assigned', 'message' => 'A new high-priority task has been assigned to you: Security testing.'],
            ['title' => 'Project Milestone Reached', 'message' => 'Congratulations! The E-commerce Platform project has reached a major milestone.'],
            ['title' => 'Inventory Low Stock Alert', 'message' => 'Warning: Laptop Dell XPS 15 inventory is running low (5 units remaining).'],
            ['title' => 'System Maintenance Notice', 'message' => 'System maintenance scheduled for this weekend from 2 AM to 6 AM.'],
            ['title' => 'Performance Review Due', 'message' => 'Your quarterly performance review is due next week. Please prepare your self-assessment.'],
            ['title' => 'Training Session Scheduled', 'message' => 'New employee training session scheduled for next Monday at 9 AM.'],
            ['title' => 'Invoice Overdue', 'message' => 'Invoice INV-00005 from StartUp Ventures is now 15 days overdue.'],
            ['title' => 'Project Budget Update', 'message' => 'The budget for CRM Integration project has been adjusted to $55,000.'],
            ['title' => 'New Client Onboarded', 'message' => 'New client "Future Systems LLC" has been successfully onboarded.'],
            ['title' => 'Security Alert', 'message' => 'Multiple failed login attempts detected on your account. Please verify your recent activity.'],
            ['title' => 'Document Upload Required', 'message' => 'Please upload your updated certifications to the HR portal by end of month.'],
            ['title' => 'Project Deadline Extended', 'message' => 'The deadline for Data Analytics Dashboard has been extended by 2 weeks.'],
            ['title' => 'Equipment Request Approved', 'message' => 'Your request for a new monitor has been approved and will arrive next week.'],
            ['title' => 'Holiday Schedule Posted', 'message' => 'The holiday schedule for next quarter has been posted to the company calendar.'],
        ];

        foreach ($notificationData as $data) {
            $notification = new Notification();
            $notification->setTitle($data['title']);
            $notification->setMessage($data['message']);
            $notification->setIsRead((bool)rand(0, 1));
            $notification->setCreatedAt(new \DateTimeImmutable('-' . rand(1, 30) . ' days'));
            $notification->setReceiver($users[array_rand($users)]);
            // Sender could be admin, HR, or finance users, or null for system notifications
            if (rand(0, 1)) {
                $possibleSenders = array_merge(
                    array_filter($users, fn($u) => in_array('ROLE_ADMIN', $u->getRoles())),
                    $hrUsers,
                    $financeUsers
                );
                $notification->setSender($possibleSenders[array_rand($possibleSenders)]);
            } else {
                $notification->setSender(null); // System notification
            }
            $manager->persist($notification);
        }

        $manager->flush();
    }
}