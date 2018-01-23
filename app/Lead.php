<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    /**
     * @param $email
     * @return CloseService|array
     */
    public function getLeadByEmail($email)
    {
        $options = [
            'query' =>  [
                'organization_id' => 'orga_ewP1OMNH1nVGxAjRM1S5j6ATb8hikpIyCmYBJmIFcHM',
                'query' => urldecode($email),
                '_limit' => '200'
            ]
        ];
        $request = new CloseService('GET', $options);
        $request = $request->call();

        $data = \GuzzleHttp\json_decode($request['data'], JSON_OBJECT_AS_ARRAY);
        $data = $data['data'];

        if (! empty($data)) {
            $values = [
                'id' => array_get($data[0], 'id', null),
                'valuations' => array_get($data[0], 'custom.lcf_GEg9856gXoijAlF67G7ZXDtjSlycfNDxTaSyM6labnW', null)
            ];
        }

        if (isset($values['id'])) {
            $data = [
                'id' => $values['id'],
                'valuations' => $values['valuations'] + 1
            ];
            $request = $this->updateLeadValuations($data);
        };
        return $request;
    }

    /**
     * @param $data
     * @return CloseService|array
     */
    public function createLead($data)
    {
        $options = [
            'json' => $data
        ];

        $request = new CloseService('POST', $options);
        $request = $request->call();

        return $request;
    }

    /**
     * @param $data
     * @return CloseService|array
     */
    private function updateLeadValuations($data)
    {
        $uri = $data['id'] . '/';
        $options = [
            'json' => [
                'custom.number of valuations' => $data['valuations']
            ]
        ];
        $request = new CloseService('PUT', $options, $uri);
        $request = $request->call();

        return $request;
    }
}

