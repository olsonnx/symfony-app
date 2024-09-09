<?php
/**
 * Tag fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

/**
 * Class TagFixtures.
 */
class TagFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        if (!$this->manager instanceof ObjectManager || !$this->faker instanceof Generator) {
            return;
        }

        // Tworzymy 50 tagów
        $this->createMany(50, 'tags', function (int $i) {
            $tag = new Tag();
            $tag->setTitle($this->faker->word); // Używamy metody Faker do generowania losowych słów jako tytuły tagów

            // Automatycznie ustawione zostaną createdAt i updatedAt przez Timestampable

            return $tag;
        });

        $this->manager->flush();
    }
}