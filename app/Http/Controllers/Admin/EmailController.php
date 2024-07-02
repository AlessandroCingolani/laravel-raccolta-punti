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
                'name' => 'required|string|min:2| max:50',
                'email' => 'required|email',
                'customer_points' => 'required|int'

            ],
            [
                'name.required' => 'Il nome è richiesto',
                'name.string' => 'Il nome deve essere una stringa',
                'name.min' => 'Il nome deve avere almeno 2 caratteri',
                'name.max' => 'Il nome non può avere più di 50 caratteri',
                'email.required' => 'Email is required',
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
        $new_lead->name = $data['name'];
        $new_lead->email = $data['email'];
        $new_lead->customer_points = Helper::discountCoupons($data['customer_points']) > 0 ? Helper::discountCoupons($data['customer_points']) : 0;


        // Invia l'email
        Mail::to($data['email'])->send(new CouponsAvailable($new_lead));
        return redirect()->route('admin.customers.index')->with('success', 'Email inviata  con successo');
    }
}