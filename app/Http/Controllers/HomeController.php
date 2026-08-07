<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function about()
    {
        return view('home/about');
    }

    public function blog()
    {
        return view('home/blog');
    }

    public function blogDetails()
    {
        return view('home/blogDetails');
    }

    public function chef()
    {
        return view('home/chef');
    }

    public function comming()
    {
        return view('home/comming');
    }

    public function contact()
    {
        return view('home/contact');
    }

    public function error404()
    {
        return view('home/error404');
    }

    public function gallery()
    {
        return view('home/gallery');
    }

    public function home2()
    {
        return view('home/home2');
    }

    public function home3()
    {
        return view('home/home3');
    }

    public function index()
    {
        return view('home/index');
    }

    public function meetTheChef()
    {
        return view('home/meetTheChef');
    }

    public function menu()
    {
        return view('home/menu');
    }

    public function portfolio()
    {
        return view('home/portfolio');
    }

    public function portfolioDetails()
    {
        return view('home/portfolioDetails');
    }

    public function reservations()
    {
        return view('home/reservations');
    }

}
