<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
class Video
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'videos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trick $trick = null;

    #[ORM\Column(type: 'text')]
    private ?string $embedCode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmbedCode(): ?string
    {
        return $this->embedCode;
    }

    public function setEmbedCode(string $embedCode): static
    {
        $this->embedCode = $embedCode;

        return $this;
    }

    /**
     * Nettoie et sécurise une iframe, puis la stocke.
     */
    public static function cleanEmbedCode(?string $embedCode): string
    {
        if ($embedCode === null) {
            return '';
        }

        $iframeStart = strpos($embedCode, '<iframe');
        $iframeEnd = strpos($embedCode, '</iframe>');

        if ($iframeStart === false || $iframeEnd === false) {
            throw new \InvalidArgumentException("Le code vidéo doit contenir une balise <iframe> complète.");
        }

        $iframe = substr($embedCode, $iframeStart, $iframeEnd - $iframeStart + 9); // +9 pour "</iframe>"

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($iframe, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $tags = $dom->getElementsByTagName('iframe');

        if ($tags->length === 0) {
            throw new \InvalidArgumentException("Aucune balise <iframe> valide trouvée.");
        }

        /** @var \DOMElement $iframeTag */
        $iframeTag = $tags->item(0);

        $allowedAttributes = ['src', 'width', 'height', 'frameborder', 'allow', 'allowfullscreen', 'title'];
        $cleanIframe = '<iframe';

        foreach ($allowedAttributes as $attr) {
            if ($iframeTag->hasAttribute($attr)) {
                $cleanIframe .= ' ' . $attr . '="' . htmlspecialchars($iframeTag->getAttribute($attr), ENT_QUOTES) . '"';
            }
        }

        $cleanIframe .= '></iframe>';

        return $cleanIframe;
    }


    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): static
    {
        $this->trick = $trick;
        return $this;
    }
}
