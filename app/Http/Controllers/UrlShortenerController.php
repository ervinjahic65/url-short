<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Models\Url;

class UrlShortenerController extends Controller
{
    // Store the shortened URL

    public function store(Request $request)
    {
       try {
           if(auth()->user()->id) {
                $longUrl = $request->get('url');
                $newGeneratedUrl = $request->get('shortlink');

                if($longUrl != '' || $newGeneratedUrl != '') {
                   $urlFound = Url::where('old_url', $longUrl)->get(['id', 'new_url'])->toArray();

                   if(!empty($urlFound)) {
                       return $urlFound[0]['new_url'];
                   } else {
                        $urlTable = new Url;
                        $urlTable->old_url = $longUrl;
                        $urlTable->new_url = $newGeneratedUrl;
                        $urlTable->user_id = auth()->user()->id;
                        $urlTable->user_ip = $_SERVER['REMOTE_ADDR'];

                        if($urlTable->save()) {
                            return $urlTable->new_url;
                        }
                   }
                }
           }
        } catch (Exception $e) {
            dd($e);
        }
    }


    public function handle(Request $request, $url)
    {
        try {
            $uri = $_SERVER['REQUEST_URI'];

            if($uri == '') {
                return abort(404);
            }

            $url = URL::where('new_url', 'like', '%'.$uri.'%')->get(['old_url']);

            if($url == '' || count($url) == 0) {
                return abort(404);
            } else {
                return redirect($url[0]['old_url']);
            }

        } catch (Exception $e) {
            dd($e);
        }
    }
}
