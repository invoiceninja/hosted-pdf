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

	$flags = [
		'--headless',
		'--no-sandbox',
		'--disable-gpu',
		'--no-margins',
		'--hide-scrollbars',
		'--no-first-run',
		'--no-default-browser-check',

		// PDF-specific settings
		'--print-to-pdf-no-header',
		'--no-pdf-header-footer',

		// Security settings
		'--disable-web-security=false',
		'--block-insecure-private-network-requests',
		'--block-port=22,25,465,587',
		'--disable-usb',
		'--disable-webrtc',
		'--block-new-web-contents',
		'--deny-permission-prompts',
		'--ignore-certificate-errors',

		// Performance & resource settings
		'--disable-dev-shm-usage',
		'--disable-software-rasterizer',
		'--run-all-compositor-stages-before-draw',
		'--disable-renderer-backgrounding',
		'--disable-background-timer-throttling',
		'--disable-background-networking',
		'--disable-domain-reliability',
		'--disable-ipc-flooding-protection',

		// Feature disabling
		'--disable-translate',
		'--disable-extensions',
		'--disable-sync',
		'--disable-default-apps',
		'--disable-plugins',
		'--disable-notifications',
		'--disable-device-discovery-notifications',
		'--disable-reading-from-canvas',
		'--safebrowsing-disable-auto-update',
		'--disable-features=SharedArrayBuffer,OutOfBlinkCors,NetworkService,NetworkServiceInProcess',

		'--virtual-time-budget=2000',
		'--font-render-hinting=medium',
		'--enable-font-antialiasing',
		
		// Debug/Output
		'--dump-dom',
	];

    public function pdf(Request $request)
    {

        $pdf_created = new PdfCreated();
        $pdf_created->string_metric5 = 'license';
        $pdf_created->int_metric1 = 1;

		LightLogs::create($pdf_created)
                 ->queue();

		$snappdf = new \Beganovich\Snappdf\Snappdf();

		$html = str_ireplace(['file:/', 'iframe', 'iframe', '&lt;embed', '<embed', '&lt;object', '<object', '127.0.0.1', 'localhost','.env','/etc/'], [''], $request->input('html'));
		.
		$pdf->clearChromiumArguments();
		$pdf->addChromiumArguments(implode(' ', $chrome_flags));

		$pdf = $snappdf
		    ->setHtml($html)
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

		if(!$version || strlen($version ?? '') <=1){
			$version = trim(file_get_contents('https://raw.githubusercontent.com/invoiceninja/invoiceninja/v5-develop/VERSION.txt'));
			Cache::forever('version', $version);
		}

    	return response($version, 200)
			->header('Content-Type', 'text/plain');
    }
}
