<?php

namespace App\Http\Controllers;

use App\Address;
use App\Property;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * @return \App\Http\Resources\Property
     */
    public function index()
    {
        return new \App\Http\Resources\Property(Property::with('address')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @param Request $request
     *
     * @return Property
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function store(Request $request)
    {
        $property = new Property($request->toArray());
        $property->save();
        if ($request->address) {
            $address = new Address;
            $address->line_1 = $request->address->line_1;
            $address->line_2 = $request->address->line_2;
            $address->line_3 = $request->address->line_3;
            $address->city = $request->address->city;
            $address->post_code = $request->address->post_code;
            $latlong = $this->getLatLong($request->address->line_1 . ',' . $request->address->city);
            if ($latlong) {
                $address->latitude = $latlong['lat'];
                $address->longitude = $latlong['long'];
            }
            $address->save();
        }
        return $property;
    }

    /**
     * Dummy function for lat long
     *
     * @param string $address
     *
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getLatLong(string $address)
    {
        try {
            $client = new Client();
            $res = $client->request('GET', "https://api.opencagedata.com/geocode/v1/json?q=$address&key=YOUR-API-KEY&language=en&pretty=1");
            $res->getBody();

            return [
                "lat" => $res->getBody()->results->lat,
                "long" => $res->getBody()->results->long
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get Property data
     *
     * @param $id
     *
     * @return Property|Property[]|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function show($id)
    {
        return Property::with('address')->find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update(Request $request, $id)
    {
        $property = Property::find($id);
        $property->name = $request->name;
        $property->save();

        if ($request->address) {
            $address = Address::find($request->address->id);
            $address->line_1 = $request->address->line_1;
            $address->line_2 = $request->address->line_2;
            $address->line_3 = $request->address->line_3;
            $address->city = $request->address->city;
            $address->post_code = $request->address->post_code;
            $latlong = $this->getLatLong($request->address->line_1 . ',' . $request->address->city);
            if ($latlong) {
                $address->latitude = $latlong['lat'];
                $address->longitude = $latlong['long'];
            }
            $address->save();
        }
        return $property;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
