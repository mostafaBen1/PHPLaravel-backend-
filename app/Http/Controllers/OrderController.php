<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Carbon\Carbon;
use App\Models\Medicine;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $currentPage = $request->get("page"); // You can set this to any page you want to paginate to

        $size = $request->get("size");

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        $role = $user->role;

        if($role->name == "ADMIN"){
            return Order::with("user")->paginate($size);
        }

        return
            Order::whereHas("user" , function ($query) use ($user){
                return $query->where("email" , $user->email);
            })->paginate($size);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $order = new Order();

        $order->issueDate = Carbon::parse($request->get("issueDate"));

        $order->totalPrice = 0 ;
        $order->totalQuantity = 0 ;

        $totalPrice = 0;

        $totalQuantity = 0;

        $order->user_id = $user->id;

        $order->save();
        $order->refresh();

        foreach ($request->data as  $item){
            $medicine = Medicine::find($item['medicineId']);

            $order->medicines()->attach($medicine ,
                array('quantity' => $item["quantity"]));

            $totalQuantity += $item["quantity"];

            $totalPrice  += ( $item["quantity"] * $medicine->price);
        }

        $order->totalPrice = $totalPrice;

        $order->totalQuantity = $totalQuantity;

        $order->save();
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);

        return $order->medicines;
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
