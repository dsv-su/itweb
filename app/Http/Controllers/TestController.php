<?php

namespace App\Http\Controllers;

class TestController extends Controller
{
   public function test()
   {
       dd('Test');
       /*return (new \Statamic\View\View)
           ->template('home.partials.search.staff')
           ->layout('mylayout');*/
   }
   public function server()
   {

   }
}
