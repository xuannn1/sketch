<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Sosadfun\Traits\FindThreadTrait;

class DownloadsController extends Controller
{
    use FindThreadTrait;
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index($id)
    {
        $thread = $this->findThread($id);
        return view('downloads.index', compact('thread'));
    }

}
