<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\FrameworkBundle\Infrastructure\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Faker;

abstract class DevelopmentFixtureWithSeed extends Fixture implements FixtureGroupInterface
{
    final public const string FIXTURE_GROUP = 'development';

    public function __construct(public int $seed = 3674787802, public string $locale = 'de_AT')
    {
    }

    /**
     * To ensure we get the same fixtures in all environments we need a seed for faker. The reason why it its lazy like
     * this is that the seed actually modifies how an internal random number generator is initialized. To ensure we always
     * get the same data we initialize the seed right before we actually need it and in addition make the seed specific
     * for every fixture class so there is no way the order of the fixture loading or anything else affects the
     * generation and it is ensured we do get same data on every run.
     */
    protected Faker\Generator $faker {
        get {
            if (isset($this->faker)) {
                return $this->faker;
            }

            $this->faker = Faker\Factory::create(locale: $this->locale);
            $this->faker->seed($this->seed + crc32(static::class));

            return $this->faker;
        }
    }

    public static function getGroups(): array
    {
        return [
            self::FIXTURE_GROUP,
            static::class,
        ];
    }
}
