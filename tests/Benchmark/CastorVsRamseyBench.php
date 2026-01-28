<?php

declare(strict_types=1);

namespace Castor\Benchmark;

use Castor\Uuid;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Subject;
use PhpBench\Attributes\Warmup;
use Ramsey\Uuid\Uuid as Ramsey;

#[Warmup(2)]
#[Revs(100)]
#[Iterations(5)]
class CastorVsRamseyBench
{
    private const string V1_UUID = '90ba7df0-c9db-11ef-9cd2-0242ac120002';
    private const string V1_UUID_BIN = "\x90\xba\x7d\xf0\xc9\xdb\x11\xef\x9c\xd2\x02\x42\xac\x12\x00\x02";

    private const string V4_UUID = '5de07980-e44d-423e-978b-43fafa16aa75';
    private const string V4_UUID_BIN = "\x5d\xe0\x79\x80\xe4\x4d\x42\x3e\x97\x8b\x43\xfa\xfa\x16\xaa\x75";

    private const string V6_UUID = '1efc9dda-8c06-6070-9995-806b116c6b00';
    private const string V6_UUID_BIN = "\x1e\xfc\x9d\xda\x8c\x06\x60\x70\x99\x95\x80\x6b\x11\x6c\x6b\x00";

    private const string V7_UUID = '01942c8a-202f-7bf6-8f03-281414be3450';
    private const string V7_UUID_BIN = "\x01\x94\x2c\x8a\x20\x2f\x7b\xf6\x8f\x03\x28\x14\x14\xbe\x34\x50";

    #[Subject]
    public function v1_generate_castor(): void
    {
        Uuid\Version1::generate();
    }

    #[Subject]
    public function v1_generate_ramsey(): void
    {
        Ramsey::uuid1();
    }

    #[Subject]
    public function v1_generate_to_string_castor(): void
    {
        Uuid\Version1::generate()->toString();
    }

    #[Subject]
    public function v1_generate_to_string_ramsey(): void
    {
        Ramsey::uuid1()->toString();
    }

    #[Subject]
    public function v1_parse_to_bytes_castor(): void
    {
        Uuid\Version1::parse(self::V1_UUID)->getBytes()->toRaw();
    }

    #[Subject]
    public function v1_parse_to_bytes_ramsey(): void
    {
        Ramsey::fromString(self::V1_UUID)->getBytes();
    }

    #[Subject]
    public function v1_from_bytes_to_string_castor(): void
    {
        Uuid\Version1::fromBytes(self::V1_UUID_BIN)->toString();
    }

    #[Subject]
    public function v1_from_bytes_to_string_ramsey(): void
    {
        Ramsey::fromBytes(self::V1_UUID_BIN)->toString();
    }

    #[Subject]
    public function v4_generate_castor(): void
    {
        Uuid\Version4::generate();
    }

    #[Subject]
    public function v4_generate_ramsey(): void
    {
        Ramsey::uuid4();
    }

    #[Subject]
    public function v4_generate_to_string_castor(): void
    {
        Uuid\Version4::generate()->toString();
    }

    #[Subject]
    public function v4_generate_to_string_ramsey(): void
    {
        Ramsey::uuid1()->toString();
    }

    #[Subject]
    public function v4_parse_to_bytes_castor(): void
    {
        Uuid\Version4::parse(self::V4_UUID)->getBytes()->toRaw();
    }

    #[Subject]
    public function v4_parse_to_bytes_ramsey(): void
    {
        Ramsey::fromString(self::V4_UUID)->getBytes();
    }

    #[Subject]
    public function v4_from_bytes_to_string_castor(): void
    {
        Uuid\Version4::fromBytes(self::V4_UUID_BIN)->toString();
    }

    #[Subject]
    public function v4_from_bytes_to_string_ramsey(): void
    {
        Ramsey::fromBytes(self::V4_UUID_BIN)->toString();
    }

    #[Subject]
    public function v6_generate_castor(): void
    {
        Uuid\Version6::generate();
    }

    #[Subject]
    public function v6_generate_ramsey(): void
    {
        Ramsey::uuid6();
    }

    #[Subject]
    public function v6_generate_to_string_castor(): void
    {
        Uuid\Version4::generate()->toString();
    }

    #[Subject]
    public function v6_generate_to_string_ramsey(): void
    {
        Ramsey::uuid1()->toString();
    }

    #[Subject]
    public function v6_parse_to_bytes_castor(): void
    {
        Uuid\Version6::parse(self::V6_UUID)->getBytes()->toRaw();
    }

    #[Subject]
    public function v6_parse_to_bytes_ramsey(): void
    {
        Ramsey::fromString(self::V6_UUID)->getBytes();
    }

    #[Subject]
    public function v6_from_bytes_to_string_castor(): void
    {
        Uuid\Version6::fromBytes(self::V6_UUID_BIN)->toString();
    }

    #[Subject]
    public function v6_from_bytes_to_string_ramsey(): void
    {
        Ramsey::fromBytes(self::V6_UUID_BIN)->toString();
    }

    #[Subject]
    public function v7_generate_castor(): void
    {
        Uuid\Version7::generate();
    }

    #[Subject]
    public function v7_generate_ramsey(): void
    {
        Ramsey::uuid7();
    }

    #[Subject]
    public function v7_generate_to_string_castor(): void
    {
        Uuid\Version7::generate()->toString();
    }

    #[Subject]
    public function v7_generate_to_string_ramsey(): void
    {
        Ramsey::uuid1()->toString();
    }

    #[Subject]
    public function v7_parse_to_bytes_castor(): void
    {
        Uuid\Version7::parse(self::V7_UUID)->getBytes()->toRaw();
    }

    #[Subject]
    public function v7_parse_to_bytes_ramsey(): void
    {
        Ramsey::fromString(self::V7_UUID)->getBytes();
    }

    #[Subject]
    public function v7_from_bytes_to_string_castor(): void
    {
        Uuid\Version7::fromBytes(self::V7_UUID_BIN)->toString();
    }

    #[Subject]
    public function v7_from_bytes_to_string_ramsey(): void
    {
        Ramsey::fromBytes(self::V7_UUID_BIN)->toString();
    }
}
