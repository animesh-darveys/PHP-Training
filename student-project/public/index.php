<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Student;
use App\Teacher;
use App\Management;
use Helpers\StringHelper;
use Services\EmailService;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$student = new Student();
$teacher = new Teacher();
$management = new Management();
$helper = new StringHelper();
$email = new EmailService();

echo $student->getName();
echo "<br>";
echo $teacher->getName();
echo "<br>";
echo $management->getName();
echo "<br>";
echo $helper->upper("hello");
echo "<br>";
echo $email->send();

$log = new Logger('student-project');

$log->pushHandler(
    new StreamHandler(__DIR__ . '/../app.log', Logger::INFO)
);

$log->info('Student project started');