<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Mark;

use Xen3r0\Adf\Enum\MarkType;
use Xen3r0\Adf\Exception\UnsupportedMarkTypeException;

final class MarkFactory
{
    public const TYPE_MAP = [
        MarkType::BackgroundColor->value => BackgroundColor::class,
        MarkType::Strong->value => Strong::class,
        MarkType::Em->value => Em::class,
        MarkType::Code->value => Code::class,
        MarkType::Strike->value => Strike::class,
        MarkType::Underline->value => Underline::class,
        MarkType::Subsup->value => Subsup::class,
        MarkType::TextColor->value => TextColor::class,
        MarkType::Link->value => Link::class,
    ];

    private function __construct()
    {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function create(array $data): Mark
    {
        $type = $data['type'] ?? null;

        if (!\is_string($type) || !isset(self::TYPE_MAP[$type])) {
            throw new UnsupportedMarkTypeException(\is_string($type) ? $type : '');
        }

        $class = self::TYPE_MAP[$type];

        return $class::fromArray($data);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return Mark[]
     */
    public static function createAll(array $data): array
    {
        return array_map(
            static fn (array $mark): Mark => self::create($mark),
            $data['marks'] ?? []
        );
    }
}
