<?php

namespace App\Http\Controllers;

use App\Analytics\PdfCreated;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
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
                 ->queue();

		$snappdf = new \Beganovich\Snappdf\Snappdf();

		$html = str_replace(['file:/', 'iframe', 'iframe', '&lt;object', '<object', '127.0.0.1', 'localhost'], ['','','','','','',''], $request->input('html'));

		$pdf = $snappdf
		    ->setHtml($html)
		    // ->waitBeforePrinting(100)
		    ->generate();

    	return Response::make($pdf, 200, [
		    'Content-Type' => 'application/pdf',
		    'Content-Disposition' => 'inline; filename="ninja.pdf"'
		]);
    }

    public function html(Request $request)
    {

		$pdf_created = new PdfCreated();
        $pdf_created->string_metric5 = 'license';
        $pdf_created->int_metric1 = 1;

		LightLogs::create($pdf_created)
                 ->queue();

		$snappdf = new \Beganovich\Snappdf\Snappdf();

		$pdf = $snappdf
		    ->setUrl($request->input('url'))
		    ->waitBeforePrinting(100)
		    ->generate();

    	return Response::make(base64_encode($pdf), 200, [
		    'Content-Type' => 'application/pdf',
		    'Content-Disposition' => 'inline; filename="ninja.pdf"'
		]);

    }

    public function version()
    {
    	$version = Cache::get('version');

    	return response($version, 200)
			->header('Content-Type', 'text/plain');
    }
}
