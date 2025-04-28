<?php
namespace App\Twig;

use App\Entity\Video;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class SafeEmbedExtension extends AbstractExtension
{
public function getFilters(): array
{
return [
new TwigFilter('safe_embed', [$this, 'safeEmbed'], ['is_safe' => ['html']]),
];
}

public function safeEmbed(string $embedCode): string
{
return Video::cleanEmbedCode($embedCode);
}
}
