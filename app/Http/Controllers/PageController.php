<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function privacy()
    {
        return view('pages.privacy');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function contactSend(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:2000',
        ]);

        // Log the contact form submission (email sending would be configured separately)
        \Illuminate\Support\Facades\Log::info('Contact form submission', [
            'name'    => $request->name,
            'email'   => $request->email,
            'subject' => $request->subject,
        ]);

        return redirect()->route('pages.contact')
            ->with('contact_success', 'Tu mensaje ha sido enviado correctamente. Te responderemos en breve.');
    }
}
