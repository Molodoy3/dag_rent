<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\AccountFree;
use Illuminate\Http\Request;

class NotificationManagerController extends Controller
{
    public function subscribe(Request $req)
    {
        $id = auth()->user()->id;
        $user = User::find($id);

        $subscription = $user->updatePushSubscription(
            $req->post('endpoint'),
            $req->post('public_key'),
            $req->post('auth_token'),
            $req->post('encoding'),
        );
        return response()->json(['message' => 'Subscribed!']);
    }

    public function unsubscribe(Request $req)
    {
        $id = auth()->user()->id;
        $user = User::find($id);

        $user->deletePushSubscription($req->post('endpoint'));

        return response()->json(['message' => 'Unsubscribed!']);
    }
    public function send()
    {
        $id = auth()->user()->id;
        $user = User::find($id);
        $user->notify(new AccountFree());

        //return redirect('/');
    }
}
