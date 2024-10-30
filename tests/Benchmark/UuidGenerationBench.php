<?php

namespace Castor\Benchmark;

use Castor\Uuid;
use PhpBench\Attributes\Revs;
use Ramsey\Uuid\Uuid as Ramsey;

class UuidGenerationBench
{
    #[Revs(1000)]
    public function benchCastorV1(): void
    {
        Uuid\Version1::generate();
    }

    #[Revs(1000)]
    public function benchRamseyV1(): void
    {
        Ramsey::uuid1();
    }

    #[Revs(1000)]
    public function benchCastorV4(): void
    {
        Uuid\Version4::generate();
    }

    #[Revs(1000)]
    public function benchRamseyV4(): void
    {
        Ramsey::uuid4();
    }

    #[Revs(1000)]
    public function benchCastorV6(): void
    {
        Uuid\Version6::generate();
    }

    #[Revs(1000)]
    public function benchRamseyV6(): void
    {
        Ramsey::uuid6();
    }

    #[Revs(1000)]
    public function benchCastorV7(): void
    {
        Uuid\Version7::generate();
    }

    #[Revs(1000)]
    public function benchRamseyV7(): void
    {
        Ramsey::uuid7();
    }
}