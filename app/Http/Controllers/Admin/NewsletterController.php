<?php

namespace App\Http\Controllers\Admin;

use EFrane\Newsletter\Model\Message;
use EFrane\Newsletter\Model\Subscriber;
use EFrane\Newsletter\Model\Subscription;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class NewsletterController extends Controller
{
    public function index() {
        $lists = Subscription::paginate(15);
        $lists->setPath('#lists');

        $subscribers = Subscriber::paginate(15);
        $subscribers->setPath('#subscribers');

        $messages = Message::paginate(15);
        $messages->setPath('#messages');

        return view('admin.newsletter.index', compact('lists', 'subscribers', 'messages'));
    }
}
