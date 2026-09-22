<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;

/**
 * Membersihkan berkas SVG hasil unggahan dari elemen/atribut berbahaya
 * (skrip, event handler, tautan javascript:) agar tidak menjadi stored XSS
 * ketika berkas dibuka langsung dari domain aplikasi.
 */
final class SvgSanitizer
{
    /**
     * @var list<string>
     */
    private const FORBIDDEN_ELEMENTS = [
        'script', 'foreignObject', 'iframe', 'embed', 'object', 'audio', 'video', 'animate', 'set', 'handler',
    ];

    public static function sanitizeFile(?string $absolutePath): void
    {
        if (! is_string($absolutePath) || $absolutePath === '' || ! is_file($absolutePath)) {
            return;
        }

        if (strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION)) !== 'svg') {
            return;
        }

        $contents = file_get_contents($absolutePath);

        if ($contents === false || $contents === '') {
            return;
        }

        $sanitized = self::sanitizeString($contents);

        if ($sanitized !== null) {
            file_put_contents($absolutePath, $sanitized);
        }
    }

    public static function sanitizeString(string $svg): ?string
    {
        $document = new DOMDocument;
        $previous = libxml_use_internal_errors(true);

        $loaded = $document->loadXML($svg, LIBXML_NONET);

        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (! $loaded) {
            return null;
        }

        $xpath = new DOMXPath($document);

        foreach (self::FORBIDDEN_ELEMENTS as $tag) {
            $nodes = $xpath->query('//*[local-name()="'.$tag.'"]');

            if ($nodes === false) {
                continue;
            }

            foreach (iterator_to_array($nodes) as $node) {
                $node->parentNode?->removeChild($node);
            }
        }

        $allNodes = $xpath->query('//*');

        if ($allNodes !== false) {
            foreach ($allNodes as $node) {
                self::cleanAttributes($node);
            }
        }

        $result = $document->saveXML();

        return $result === false ? null : $result;
    }

    private static function cleanAttributes(DOMNode $node): void
    {
        if (! $node instanceof DOMElement || ! $node->hasAttributes()) {
            return;
        }

        foreach (iterator_to_array($node->attributes) as $attribute) {
            $name = strtolower($attribute->nodeName);
            $value = strtolower(trim((string) $attribute->nodeValue));
            $compact = preg_replace('/\s+/', '', $value) ?? $value;

            $isEventAttribute = str_starts_with($name, 'on');
            $isScriptUrl = in_array($name, ['href', 'xlink:href', 'src'], true)
                && (str_starts_with($compact, 'javascript:') || str_starts_with($compact, 'data:text/html'));
            $isDataUrl = in_array($name, ['href', 'xlink:href'], true) && str_starts_with($compact, 'data:image/svg');

            if ($isEventAttribute || $isScriptUrl || $isDataUrl) {
                $node->removeAttribute($attribute->nodeName);
            }
        }
    }
}
