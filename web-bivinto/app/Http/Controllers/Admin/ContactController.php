<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::orderBy('created_at', 'desc')->paginate(15);
        return \Inertia\Inertia::render('Admin/Contacts', [
            'contacts' => $contacts
        ]);
    }

    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'status' => 'required|in:pending,resolved'
        ]);

        $contact->update(['status' => $request->status]);

        return redirect()->back();
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->back();
    }
}
