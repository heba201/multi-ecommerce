<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Order;
use Session;
// use niklasravnsborg\LaravelPdf\Facades\Pdf\PDF;
use Config;
use Elibyy\TCPDF\Facades\TCPDF;
class InvoiceController extends Controller
{
   //download invoice
   public function invoice_download($id)
   {
       if (Session::has('currency_code')) {
           $currency_code = Session::get('currency_code');
       } else {
           $currency_code = Currency::findOrFail(get_setting('system_default_currency'))->code;
       }
       $language_code = Session::get('locale', Config::get('app.locale'));
      $dir=Language::where('locale', $language_code)->first();
      if($dir){
        if ($dir->direction == 'rtl') {
            $direction = 'rtl';
            $text_align = 'right';
            $not_text_align = 'left';
        } 
      }
       else {
           $direction = 'ltr';
           $text_align = 'left';
           $not_text_align = 'right';
       }

       if (
           $currency_code == 'BDT' ||
           $language_code == 'bd'
       ) {
           // bengali font
           $font_family = "'Droid Arabic Kufi','DejaVu Sans'";
       } elseif (
           $currency_code == 'KHR' ||
           $language_code == 'kh'
       ) {
           // khmer font
           $font_family = "'Droid Arabic Kufi','DejaVu Sans'";
       } elseif ($currency_code == 'AMD') {
           // Armenia font
           $font_family = "'Droid Arabic Kufi','DejaVu Sans'";
           // }elseif($currency_code == 'ILS'){
           //     // Israeli font
           //     $font_family = "'Varela Round','DejaVu Sans'";
       } elseif (
           $currency_code == 'AED' ||
           $currency_code == 'EGP' ||
           $language_code == 'sa' ||
           $currency_code == 'IQD' ||
           $language_code == 'ir' ||
           $language_code == 'om' ||
           $currency_code == 'ROM' ||
           $currency_code == 'SDG' ||
           $currency_code == 'ILS' ||
           $language_code == 'jo'
       ) {
           // middle east/arabic/Israeli font
           $font_family = "'Droid Arabic Kufi','DejaVu Sans'";
       } elseif ($currency_code == 'THB') {
           // thai font
           $font_family = "'Droid Arabic Kufi','DejaVu Sans'";
       } elseif (
           $currency_code == 'CNY' ||
           $language_code == 'zh'
       ) {
           // Chinese font
           $font_family = "'Droid Arabic Kufi','DejaVu Sans'";
       } elseif (
           $currency_code == 'kyat' ||
           $language_code == 'mm'
       ) {
           // Myanmar font
           $font_family = "'Droid Arabic Kufi','DejaVu Sans'";
       } elseif (
           $currency_code == 'THB' ||
           $language_code == 'th'
       ) {
           // Thai font
           $font_family = "'Droid Arabic Kufi','DejaVu Sans'";
       } else {
           // general for all
           $font_family = "'Droid Arabic Kufi','Droid Arabic Kufi'";
       }
       $order = Order::findOrFail($id);
$view = \View::make('admin.invoice.invoice',compact('order','font_family','direction','text_align','not_text_align'));
$html = $view->render();

$pdf = new TCPDF();
$pdf::SetTitle('Invoice');
$pdf::AddPage();
$pdf::SetFont('aealarabiya', '', 16);
$pdf::writeHTML($html, true, false, true, false, '');
$filename='order-' . $order->code . '.pdf';
 $pdf::Output(public_path($filename), 'F');
 return response()->download(public_path($filename));
    }
}
