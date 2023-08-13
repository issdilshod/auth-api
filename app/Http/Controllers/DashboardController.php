<?php

namespace App\Http\Controllers;

class DashboardController extends Controller{

    public function index(){
        return view('pages.home', [
            'title' => __('home.title')
        ]);
    }

}