<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class ShortUrlController extends Controller
{
    /**
     * @param Request $request
     * @param ShortUrl $shortUrl
     * @return Application|RedirectResponse|Redirector
     */
    public function go(Request $request, ShortUrl $shortUrl)
    {
        return redirect($shortUrl->link);
    }
}
