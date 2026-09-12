<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Subscriber;

class ContactController extends Controller
{
    function subscriberIndex()
    {
        $this->authorize('viewAny', Subscriber::class);

        $pageTitle   = 'Subscribers';
        $subscribers = Subscriber::searchable(['email'])->latest()->paginate(getPaginate());

        return view('admin.page.subscriber', compact('pageTitle', 'subscribers'));
    }

    function sendEmailSubscriber()
    {
        $this->authorize('sendEmail', Subscriber::class);

        $this->validate(request(), [
            'subject' => 'required',
            'body'    => 'required',
        ]);

        $subscribers = Subscriber::cursor();

        foreach ($subscribers as $subscriber) {
            $receiverName = explode('@', $subscriber->email)[0];
            $user         = [
                'username' => $subscriber->email,
                'email'    => $subscriber->email,
                'fullname' => $receiverName,
            ];

            notify($user, 'DEFAULT', [
                'subject' => request('subject'),
                'message' => request('body'),
            ], ['email']);
        }

        $toast[] = ['success', 'Email has been sent to all subscribers'];

        return back()->with('toasts', $toast);
    }

    function subscriberRemove(int $id)
    {
        $subscriber = Subscriber::findOrFail($id);

        $this->authorize('delete', $subscriber);

        $subscriber->delete();

        $toast[] = ['success', 'The subscriber has been successfully deleted'];

        return back()->with('toasts', $toast);
    }

    function contactIndex()
    {
        $this->authorize('viewAny', Contact::class);

        $pageTitle = 'Contacts';
        $contacts  = Contact::searchable(['email'])
            ->dateFilter()
            ->orderBy('status')
            ->latest()
            ->paginate(getPaginate());

        return view('admin.page.contact', compact('pageTitle', 'contacts'));
    }

    function contactRemove(int $id)
    {
        $contact = Contact::findOrFail($id);

        $this->authorize('delete', $contact);

        $contact->delete();

        $toast[] = ['success', 'The contact has been successfully deleted'];

        return back()->with('toasts', $toast);
    }

    function contactStatus(int $id)
    {
        $this->authorize('changeStatus', Contact::class);

        return Contact::changeStatus($id);
    }
}
