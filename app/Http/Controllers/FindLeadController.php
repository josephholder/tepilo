<?php

namespace App\Http\Controllers;

use App;
use App\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FindLeadController extends Controller
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
            'email' => $request->input('email')
        ];

        $request = $lead->getLeadByEmail($data['email']);
        $data = (array) json_decode($request['data']);

        $response = [
            'msg' => ( isset($data["total_results"]) && $data["total_results"] == 0 )
                ? 'No leads are found please upload your contact information to /api/v1/find-lead'
                : 'Lead(s) found',
            'code' => $request['code'],
            'data' => $data
        ];

        return response()->json($response, $request['code']);
    }

    private function validateRequests($request)
    {
        $validator = Validator::make($request, [
            'email' => 'required|email'
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
}
