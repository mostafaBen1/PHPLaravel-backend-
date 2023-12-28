<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Response;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function index(Request $request)
    {
        $currentPage = $request->get("page"); // You can set this to any page you want to paginate to

        $size = $request->get("size");

        $name= $request->get("name");

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        return $name == null ?
                Medicine::paginate($size)
            :   Medicine::where("name" , "LIKE" , "%{$name}%")
                ->paginate($size)
            ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $medicine = Medicine::where("name" , $request->name)->first();

        if($medicine){
           return Response::json([
                "message" => "Already A medicine with that name"
           ] , 403);
        }

        Medicine::create([
            "name" => $request->name,
            "quantityInStock" => $request->quantityInStock,
            "price" => $request->price,
            "prescriptionRequired" => $request->prescriptionRequired,
            "expireDate" => $request->expireDate
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Medicine::find($id);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $medicine = Medicine::find($id);

        if($request->name != null) $medicine->name = $request->name;

        if($request->price  != null) $medicine->price = $request->price;

        if($request->quantityInStock != null )
            $medicine->quantityInStock = $request->quantityInStock;

        if($request->prescriptionRequired != null || !$request->prescriptionRequired)
            $medicine->prescriptionRequired = $request->prescriptionRequired;

        if($request->expireDate  != null)
            $medicine->expireDate = $request->expireDate;

        $medicine->save();

        return  $medicine;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete a medicines
        Medicine::destroy($id);
    }

    public function search(Request $request){
        $name = $request->get("name");

        $size = 5;

        $medicines = Medicine::where("name" , "LIKE" , "%{$name}%")
            ->limit($size)
            ->get();

        return [
            "medicines" => $medicines
        ];
    }
}
