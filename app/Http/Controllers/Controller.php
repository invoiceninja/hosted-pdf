<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function pdf(Request $request)
    {

		$snappdf = new \Beganovich\Snappdf\Snappdf();

		$pdf = $snappdf
		    ->setHtml($request->input('html'))
		    ->generate();

    	return Response::make($pdf, 200, [
		    'Content-Type' => 'application/pdf',
		    'Content-Disposition' => 'inline; filename="ninja.pdf"'
		]);
    }
}
