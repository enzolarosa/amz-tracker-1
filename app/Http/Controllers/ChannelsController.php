<?php

namespace App\Http\Controllers;

use App\Models\Channels;
use Illuminate\Http\Response;

class ChannelsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('teams.channels.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Channels $channels
     * @return Response
     */
    public function show(Channels $channels)
    {
        return view('teams.channels.show');
    }
}
