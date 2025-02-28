<?php

namespace App\DataFixtures;

use App\Entity\Student;
use App\Entity\Document;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class AppFixtures extends Fixture
{
    //command to load fake data: php bin/console doctrine:fixtures:load 
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Create 40 students
        for ($i = 0; $i < 40; $i++) {
            $student = new Student();
            $student->setIdentificationNumber($faker->unique()->numerify('#-###-####'));
            $student->setFirstName($faker->firstName());
            $student->setLastName($faker->lastName());

            $manager->persist($student);

            // Create 2 documents per student
            for ($j = 0; $j < 2; $j++) {
                $document = new Document();
                $document->setStudent($student);
                $document->setStudentNumber($student->getIdentificationNumber());
                $pdfFileName = $this->generateFakePdfFileName($faker);
                $document->setPdfFile($pdfFileName);

                $manager->persist($document);
            }
        }

        // Flush all data to the database
        $manager->flush();
    }

    private function generateFakePdfFileName(\Faker\Generator $faker): string
    {
        $fileName = 'student-report-' . $faker->date('Y-m-d') . '.pdf';
        return $fileName;
    }
}
