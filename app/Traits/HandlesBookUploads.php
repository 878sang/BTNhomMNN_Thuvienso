<?php

namespace App\Traits;

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

trait HandlesBookUploads
{
    /**
     * Convert Word (.docx) to PDF
     * Priority: LibreOffice (Better quality) > PHPWord (Fallback)
     */
    public function convertWordToPdf($wordFilePath, $outputPdfPath)
    {
        // 1. Thử dùng LibreOffice (Cực kỳ chính xác, khuyên dùng)
        $libreOfficePaths = [
            'C:\Program Files\LibreOffice\program\soffice.exe',
            'C:\Program Files (x86)\LibreOffice\program\soffice.exe',
            'soffice' // Nếu đã thêm vào Environment Variables
        ];

        $soffice = null;
        foreach ($libreOfficePaths as $path) {
            if ($path === 'soffice' || file_exists($path)) {
                $soffice = $path;
                break;
            }
        }

        if ($soffice) {
            $outputDir = dirname($outputPdfPath);
            // Lệnh LibreOffice: --headless (không giao diện), --convert-to pdf, --outdir (thư mục xuất)
            $command = "\"$soffice\" --headless --convert-to pdf --outdir \"$outputDir\" \"$wordFilePath\"";
            
            Log::info("Attempting LibreOffice conversion: $command");
            shell_exec($command);

            // LibreOffice xuất file với tên gốc + .pdf, ta cần rename lại đúng $outputPdfPath
            $expectedFile = $outputDir . DIRECTORY_SEPARATOR . pathinfo($wordFilePath, PATHINFO_FILENAME) . '.pdf';
            if (file_exists($expectedFile)) {
                if ($expectedFile !== $outputPdfPath) {
                    rename($expectedFile, $outputPdfPath);
                }
                return true;
            }
            Log::warning("LibreOffice failed to produce PDF, falling back to PHPWord.");
        }

        // 2. Fallback dùng PHPWord + DomPDF (Nếu không có LibreOffice)
        try {
            ini_set('memory_limit', '256M');
            Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
            Settings::setPdfRendererPath(base_path('vendor/dompdf/dompdf'));

            $phpWord = IOFactory::load($wordFilePath);
            $pdfWriter = IOFactory::createWriter($phpWord, 'PDF');
            $pdfWriter->save($outputPdfPath);
            
            return true;
        } catch (\Exception $e) {
            Log::error('All PDF Conversion Methods Failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate a 5-page preview from a PDF file
     */
    public function generatePreview($fullFilePath, $previewFileName)
    {
        try {
            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile($fullFilePath);
            $pagesToExtract = min($pageCount, 5);

            for ($n = 1; $n <= $pagesToExtract; $n++) {
                $tplIdx = $pdf->importPage($n);
                $size = $pdf->getTemplateSize($tplIdx);
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($tplIdx);
            }

            $storagePath = storage_path('app/public/previews/' . $previewFileName);
            if (!file_exists(dirname($storagePath))) {
                mkdir(dirname($storagePath), 0755, true);
            }
            
            $pdf->Output('F', $storagePath);
            return 'previews/' . $previewFileName;
        } catch (\Exception $e) {
            Log::error('PDF Preview Generation Failed: ' . $e->getMessage());
            return null;
        }
    }

    public function getPdfPageCount($pdfFilePath)
    {
        try {
            $pdf = new Fpdi();
            return $pdf->setSourceFile($pdfFilePath);
        } catch (\Exception $e) {
            Log::error('PDF Page Count Failed: ' . $e->getMessage());
            return null;
        }
    }
}
