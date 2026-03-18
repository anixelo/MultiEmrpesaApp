<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\User;
use App\Notifications\NuevoMensajeContactoNotification;
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

        $contactMessage = ContactMessage::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        // Notify all superadmins
        $notification = new NuevoMensajeContactoNotification($contactMessage);
        User::whereHas('roles', fn ($q) => $q->where('name', 'superadministrador'))
            ->each(fn (User $superAdmin) => $superAdmin->notify($notification));

        return redirect()->route('pages.contact')
            ->with('contact_success', 'Tu mensaje ha sido enviado correctamente. Te responderemos en breve.');
    }
}
