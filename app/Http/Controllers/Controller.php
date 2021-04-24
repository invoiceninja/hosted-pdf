<?php

namespace App\Http\Controllers;

use App\Analytics\PdfCreated;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;
use Turbo124\Beacon\Facades\LightLogs;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function pdf(Request $request)
    {

        $pdf_created = new PdfCreated();
        $pdf_created->string_metric5 = 'license';
        $pdf_created->int_metric1 = 1;

		LightLogs::create($pdf_created)
                 ->batch();

		$snappdf = new \Beganovich\Snappdf\Snappdf();

		$pdf = $snappdf
		    ->setHtml($request->input('html'))
		    ->waitBeforePrinting(100)
		    ->generate();

    	return Response::make($pdf, 200, [
		    'Content-Type' => 'application/pdf',
		    'Content-Disposition' => 'inline; filename="ninja.pdf"'
		]);
    }
}
