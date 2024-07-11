<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CouponsAvailable;
use App\Models\Lead;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Functions\Helper;

class EmailController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make(
            $data,
            [
                'recipient_name' => 'required|string|min:2| max:50',
                'recipient_surname' => 'required|string|min:2| max:50',
                'email' => 'required|email',
                'type' => 'required|string',
                'customer_points' => 'required|int'

            ],
            [
                'recipient_name.required' => 'Il nome è richiesto',
                'recipient_name.string' => 'Il nome deve essere una stringa',
                'recipient_name.min' => 'Il nome deve avere almeno 2 caratteri',
                'recipient_name.max' => 'Il nome non può avere più di 50 caratteri',
                'recipient_surname.required' => 'Il cognome è richiesto',
                'recipient_surname.string' => 'Il cognome deve essere una stringa',
                'recipient_surname.min' => 'Il cognome deve avere almeno 2 caratteri',
                'recipient_surname.max' => 'Il cognome non può avere più di 50 caratteri',
                'email.required' => 'Email is required',
                'type.required' => 'Type is required',
                'customer_points.required' => 'Coupons are required',
                'customer_points.int' => 'Coupons must be int'
            ]
        );
        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->route('admin.customers.index')->withErrors($errors);
        }


        // Create Lead and fill with datas from input
        $new_lead = new Lead();
        $new_lead->recipient_name = $data['recipient_name'];
        $new_lead->recipient_surname = $data['recipient_surname'];
        $new_lead->email = $data['email'];
        $new_lead->type = $data['type'];
        $new_lead->save();
        $new_lead->customer_points = Helper::discountCoupons($data['customer_points']) > 0 ? Helper::discountCoupons($data['customer_points']) : 0;

        // TODO: invio differenti email in base a tipo che mi arriva dal pulsante
        // Invia l'email
        Mail::to($data['email'])->send(new CouponsAvailable($new_lead));
        return redirect()->route('admin.customers.index')->with('success', 'Email inviata  con successo');
    }
}
