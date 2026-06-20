<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        return view('public.home');
    }

    public function solutions()
    {
        return view('public.solutions');
    }

    public function features()
    {
        return redirect('/solutions');
    }

    public function services()
    {
        return redirect('/solutions');
    }

    public function bi()
    {
        return redirect('/solutions');
    }

    public function about()
    {
        return view('public.about');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function faq()
    {
        return view('public.faq');
    }

    public function login()
    {
        return view('public.login');
    }

    public function register()
    {
        return view('public.register');
    }
}
