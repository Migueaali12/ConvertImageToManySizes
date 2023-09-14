<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ConversionController extends Controller
{
    public function convertToPdf(Request $request)
    {
        $selectedSizes = $request->input('selectedSizes');
        $selectedData = $request->input('selectedData');
        $selectedConvertType = $request->input('selectedConvertType');
        $pdfPaths = [];
        $images = [];

        foreach ($selectedConvertType as $convertType) {
            $images = [];

            foreach ($selectedData as $data) {
                $imgUrl = $data['imgUrl'];
                $imgName = $data['imgName'];

                usort($selectedSizes, function ($a, $b) {

                    $aSize = floatval($a);
                    $bSize = floatval($b);

                    if ($aSize == $bSize) {
                        return 0;
                    }

                    return ($bSize < $aSize) ? -1 : 1;
                });

                foreach ($selectedSizes as $imageSize) {
                    $image = Image::make($imgUrl);

                    if (strpos($imageSize, 'x') !== false) {
                        list($customWidth, $customHeight) = explode('x', $imageSize);
                        $image->resize(((float) $customWidth * 39.6), (float) $customHeight * 39.6);
                    }

                    $images[] = $image;
                }
            }

            $pdfFileName = time() . '_' . $imgName . '_' . $imageSize . '_' . $convertType . '.pdf';
            $pdfPath = public_path('uploadsPdf/' . $pdfFileName);

            if (!File::exists(dirname($pdfPath))) {
                File::makeDirectory(dirname($pdfPath), 0755, true);
            }

            $template = ($convertType === 'horizontal') ? 'pdf.template1' : 'pdf.template2';

            $pdf = PDF::loadView($template, compact('images'));
            $pdf->save($pdfPath);

            $httpPdfPath = asset('uploadsPdf/' . $pdfFileName);
            $pdfPaths[] = $httpPdfPath;
        }

        return response()->json(['pdfPaths' => $pdfPaths, 'message' => 'Conversión exitosa!']);
    }

}
