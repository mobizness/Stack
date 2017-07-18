<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\VoucherCode;

class VoucherCodesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($location_id, $voucher_id, Request $request)
    {
      $perPage = $request->input('per');
      $voucherCodes = VoucherCode::where('voucher_id', $voucher_id)->paginate($perPage);

      $data = array();
      foreach ($voucherCodes as $voucher_code) {
        $arry = $voucher_code->toArray();
        $arry['created_at'] = $voucher_code->created_at->timestamp;
        $arry['updated_at'] = $voucher_code->updated_at->timestamp;
        $data[] = $arry;
      }

      $links = array();
      $links['current_page'] = $voucherCodes->currentPage();
      if ($voucherCodes->hasMorePages())
        $links['next_page'] = $links['current_page'] + 1;
      $links['total_entries'] = $voucherCodes->total();
      $links['total_pages'] = $voucherCodes->lastPage();
      $links['size'] = $voucherCodes->perPage();
      return array('codes' => $data, '_links' => $links);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function update($location_id, $client_id, Request $request, $id)
     {
       $item = VoucherCode::where('username', $id)->first();

       if (!isset($item)) {
         return array('code' => 0, 'message' => 'Invalid Request');
       }

       $code = $request->input('code');
       if (!isset($code)) {
         return array('code' => 0, 'message' => 'Invalid Request');
       }

       $active = isset($code['active']) ? $code['active'] : 0;

       $item->active = $active;

       $item->save();

       $data = $item->toArray();
       $data['unique_id'] = $item->id;
       $data['created_at'] = $item->created_at->timestamp;
       $data['updated_at'] = $item->updated_at->timestamp;

       return array('code' => $data);
     }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
