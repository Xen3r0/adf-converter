<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Enum;

/**
 * @codeCoverageIgnore
 */
enum MarkType: string
{
    case BackgroundColor = 'backgroundColor';
    case Code = 'code';
    case Em = 'em';
    case Link = 'link';
    case Strike = 'strike';
    case Strong = 'strong';
    case Subsup = 'subsup';
    case TextColor = 'textColor';
    case Underline = 'underline';
}
