<?php


namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class CategoryFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $designCategory = new Category();
        $designCategory->setName('Design');
        $designCategory->setSlug('design');

        $programmingCategory = new Category();
        $programmingCategory->setName('Programming');
        $programmingCategory->setSlug('programming');


        $managerCategory = new Category();
        $managerCategory->setName('Manager');
        $managerCategory->setSlug('manager');

        $administratorCategory = new Category();
        $administratorCategory->setName('Administrator');
        $administratorCategory->setSlug('administrator');

        $manager->persist($designCategory);
        $manager->persist($programmingCategory);
        $manager->persist($managerCategory);
        $manager->persist($administratorCategory);

        $manager->flush();

        $this->addReference('category-design', $designCategory);
        $this->addReference('category-programming', $programmingCategory);
        $this->addReference('category-manager', $managerCategory);
        $this->addReference('category-administrator', $administratorCategory);
    }
}