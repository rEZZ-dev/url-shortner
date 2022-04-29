<?php

namespace App\Http\Controllers;

use App\Models\Links;
use App\Models\UserInfo;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Jenssegers\Agent\Facades\Agent;
use Stevebauman\Location\Facades\Location;

class LinksController extends Controller
{

    public function generate(Request $request): string
    {
        $size = 4;
        $randomToken = Str::random($size);
        $newLink = URL::to('/') . '/'  . $randomToken;

        $link = new Links();
        $link->original_link = $request['originalLink'];
        $link->new_link = $newLink;
        $link->save();

        $this->storeUserInfo($request);

        return $newLink;
    }

    private function storeUserInfo(Request $request): void
    {
        $userIp = $request->getClientIp();

        $currentUserInfo = Location::get($userIp);
        $device = Agent::device();

        $userInfo = new UserInfo();
        $userInfo->ip_address = $userIp;
        $userInfo->country = $currentUserInfo;
        $userInfo->device = $device;

        $userInfo->save();
    }

    public function redirect(String $param): RedirectResponse|Application|Redirector
    {
        $link = URL::to('/') . '/'  . $param;

        $link = Links::where('new_link', '=', $link)->first();

        if($link) {
            return redirect($link->original_link);
        }

        $frontendUrl = URL::to(env('FRONTEND_URL'));

        return redirect($frontendUrl);
    }
}
