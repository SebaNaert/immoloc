<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
// use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Factory::create("fr_FR");

        for ($i = 1; $i <= 30; $i++) {
            $ad = new Ad();
            $title = $faker->sentence();
            $coverImage = "https://picsum.photos/id/" . $i . "/1000/350";
            $introduction = $faker->paragraph(2);
            $content = "<p>" . join('</p><p>', $faker->paragraphs(5)) . "</p>";

            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(rand(40, 200))
                ->setRooms(rand(1, 5));

            $manager->persist($ad);

            // Gestion des images de l'annonce (galerie)
            for ($j = 1; $j <= rand(2, 5); $j++) {

                $image = new Image();
                $image->setAd($ad)
                    ->setUrl("https://picsum.photos/id/" . $j . "/900")
                    ->setCaption($faker->sentence());
                $manager->persist($image);
            }
        }

        $manager->flush();
    }
}
