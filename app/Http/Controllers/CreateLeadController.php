<?php

namespace App\Http\Controllers;

use App\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CreateLeadController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Lead $lead
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Lead $lead)
    {
        $errors = $this->validateRequests($request->all());
        if ($errors) {
            return response()->json($errors, 406);
        }

        $data = [
            'name' => $request->input('name'),
            'url' => $request->input('url', ''),
            'description' => $request->input('description', ''),
            'status_id' => 'stat_yE4J4QxxowV6IKNI931O7RrbtTn3iQtYwS9u52l4D2P',
            'contacts' => $this->contacts($request)
        ];

        $request = $lead->createLead( $data);
        $data = (array) json_decode($request['data']);

        $response = [
            'msg' => 'Contact created',
            'code' => $request['code'],
            'data' => $data
        ];

        return response()->json($response, $request['code']);
    }

    private function validateRequests($request)
    {
        $validator = Validator::make($request, [
            'name' => 'nullable|string',
            'url' => 'nullable|url',
            'description' => 'nullable|string',
            'contacts.*.name' => 'nullable|string',
            'contacts.*.emails.*.email' => 'required|email',
            'contacts.*.phones.*.phone' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $response = [
                'code' => 406,
                'error' =>  $validator->messages()
            ];
            return response()->json($response, 406);
        }
        return false;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function contacts(Request $request)
    {
        $value = $request->input('contacts');
        $contacts = [];
        foreach ($value as $key => $contact) {
            $contacts[$key] =  array_only($contact, ['name', 'emails', 'phones']);
            foreach ($contact as $count => $array) {
                if (is_array($array)) {
                    foreach ($array as $index => $response) {
                        $contacts[$key][$count][$index] = array_only($response, ['type', 'email', 'phone']);
                    }
                }
            }
        }
        return $contacts;
    }
}
