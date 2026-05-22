<?php

namespace App\Services;

use Smalot\PdfParser\Parser;

class PdfTextExtractor
{
    public function extractFromFile(string $pdfPath, int $maxPages = 5): array
    {
        if (!is_readable($pdfPath)) {
            return ['text' => '', 'pages_read' => 0, 'total_pages' => 0];
        }

        try {
            $parser = new Parser();
            $pdf = $parser->parseFile($pdfPath);
            $pages = $pdf->getPages();
            $totalPages = count($pages);
            $limit = min($maxPages, $totalPages);
            $chunks = [];

            for ($i = 0; $i < $limit; $i++) {
                $pageText = trim($pages[$i]->getText());
                if ($pageText !== '') {
                    $chunks[] = $pageText;
                }
            }

            $text = $this->normalizeText(implode("\n\n", $chunks));

            return [
                'text' => $text,
                'pages_read' => $limit,
                'total_pages' => $totalPages,
            ];
        } catch (\Throwable $e) {
            return ['text' => '', 'pages_read' => 0, 'total_pages' => 0];
        }
    }

    public function normalizeText(string $text): string
    {
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;
        $text = preg_replace('/(.{1,400})\s/u', "$1\n", $text) ?? $text;

        return trim($text);
    }
}
