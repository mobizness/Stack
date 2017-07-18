<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Voucher;
use App\Location;
use App\VoucherCode;

use PDF;

class VouchersController extends Controller
{
    public function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds')
    {
    	$sets = array();
    	if(strpos($available_sets, 'l') !== false)
    		$sets[] = 'abcdefghjkmnpqrstuvwxyz';
    	if(strpos($available_sets, 'u') !== false)
    		$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
    	if(strpos($available_sets, 'd') !== false)
    		$sets[] = '23456789';
    	if(strpos($available_sets, 's') !== false)
    		$sets[] = '!@#$%&*?';
    	$all = '';
    	$password = '';
    	foreach($sets as $set)
    	{
    		$password .= $set[array_rand(str_split($set))];
    		$all .= $set;
    	}
    	$all = str_split($all);
    	for($i = 0; $i < $length - count($sets); $i++)
    		$password .= $all[array_rand($all)];
    	$password = str_shuffle($password);
    	if(!$add_dashes)
    		return $password;
    	$dash_len = floor(sqrt($length));
    	$dash_str = '';
    	while(strlen($password) > $dash_len)
    	{
    		$dash_str .= substr($password, 0, $dash_len) . '-';
    		$password = substr($password, $dash_len);
    	}
    	$dash_str .= $password;
    	return $dash_str;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($location_id, Request $request)
    {
      $perPage = $request->input('per');
      $vouchers = Voucher::paginate($perPage);

      $data = array();
      foreach ($vouchers as $voucher) {
        $arry = $voucher->toArray();
        $arry['created_at'] = $voucher->created_at->timestamp;
        $arry['updated_at'] = $voucher->updated_at->timestamp;
        $arry['unique_id'] = $voucher->id;
        $data[] = $arry;
      }

      $links = array();
      $links['current_page'] = $vouchers->currentPage();
      if ($vouchers->hasMorePages())
        $links['next_page'] = $links['current_page'] + 1;
      $links['total_entries'] = $vouchers->total();
      $links['total_pages'] = $vouchers->lastPage();
      $links['size'] = $vouchers->perPage();
      return array('vouchers' => $data, '_links' => $links);
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
    public function store($location_id, Request $request)
    {

        $voucher = $request->input('voucher');
        $location = Location::find($location_id);
        if (!isset($voucher) || !isset($location)) {
          return array('code' => 0, 'message' => 'Invalid Request');
        }

        $splash_page_id = isset($voucher['splash_page_id']) ? $voucher['splash_page_id'] : 0;
        $batch_name = isset($voucher['batch_name']) ? $voucher['batch_name'] : '';
        $batch_description = isset($voucher['batch_description']) ? $voucher['batch_description'] : '';
        $is_active = isset($voucher['is_active']) ? $voucher['is_active'] : 1;
        $voucher_value = isset($voucher['voucher_value']) ? $voucher['voucher_value'] : '';
        $quantity = isset($voucher['quantity']) ? $voucher['quantity'] : 0;
        $access_type = isset($voucher['access_type']) ? $voucher['access_type'] : 0;
        $access_period = isset($voucher['access_period']) ? $voucher['access_period'] : '';
        $limit_type = isset($voucher['limit_type']) ? $voucher['limit_type'] : '';
        $session_timeout = isset($voucher['session_timeout']) ? $voucher['session_timeout'] : 0;
        $idle_timeout = isset($voucher['idle_timeout']) ? $voucher['idle_timeout'] : 0;
        $validity_minutes = isset($voucher['validity_minutes']) ? $voucher['validity_minutes'] : 0;
        $access_restrict_period = isset($voucher['access_restrict_period']) ? $voucher['access_restrict_period'] : '';
        $access_restrict_data = isset($voucher['access_restrict_data']) ? $voucher['access_restrict_data'] : '';
        $expiration = isset($voucher['expiration']) ? $voucher['expiration'] : '';
        $secure_password = isset($voucher['secure_password']) ? $voucher['secure_password'] : 0;
        $voucher_format = isset($voucher['voucher_format']) ? $voucher['voucher_format'] : '';
        $csv_link = isset($voucher['csv_link']) ? $voucher['csv_link'] : '';
        $pdf_link = isset($voucher['pdf_link']) ? $voucher['pdf_link'] : '';
        $username = isset($voucher['username']) ? $voucher['username'] : '';
        $completed = isset($voucher['completed']) ? $voucher['completed'] : 1;
        $download_speed = isset($voucher['download_speed']) ? $voucher['download_speed'] : 0;
        $upload_speed = isset($voucher['upload_speed']) ? $voucher['upload_speed'] : 0;
        $simultaneous_use = isset($voucher['simultaneous_use']) ? $voucher['simultaneous_use'] : 0;

        $item = Voucher::create([
          'location_id' => $location_id,
          'location_name' => $location->location_name,
          'splash_page_id' => $splash_page_id,
          'batch_name' => $batch_name,
          'batch_description' => $batch_description,
          'is_active' => $is_active,
          'voucher_value' => $voucher_value,
          'quantity' => $quantity,
          'access_type' => $access_type,
          'limit_type' => $limit_type,
          'session_timeout' => $session_timeout,
          'idle_timeout' => $idle_timeout,
          'validity_minutes' => $validity_minutes,
          'access_restrict_period' => $access_restrict_period,
          'access_restrict_data' => $access_restrict_data,
          'expiration' => $expiration,
          'secure_password' => $secure_password,
          'voucher_format' => $voucher_format,
          'csv_link' => $csv_link,
          'pdf_link' => $pdf_link,
          'username' => $username,
          'completed' => $completed,
          'download_speed' => $download_speed,
          'upload_speed' => $upload_speed,
          'simultaneous_use' => $simultaneous_use,
        ]);

        $genCodes = array();
        for ($idx = 0; $idx < $quantity; $idx++) {
          if ($voucher_format == 'alphanumeric') {
            $genCodes[$this->generateStrongPassword(5, false, 'lud')] = $this->generateStrongPassword(5, false, 'lud');
          } else if ($voucher_format == 'numeric') {
            $genCodes[$this->generateStrongPassword(5, false, 'd')] = $this->generateStrongPassword(5, false, 'd');
          } else if ($voucher_format == 'alpha') {
            $genCodes[$this->generateStrongPassword(5, false, 'lu')] = $this->generateStrongPassword(5, false, 'lu');
          }
        }

        $serial = 1;
        foreach ($genCodes as $code_name => $code_pwd) {
          VoucherCode::create([
            'splash_page_id' => $splash_page_id,
            'voucher_id' => $item->id,
            'username' => $code_name,
            'password' => $code_pwd,
            'description' => $batch_description,
            'serial' => $serial,
            'active' => true,
          ]);

          $serial ++;
        }

        $item->pdf_link = route('vouchers.getCodePDF', ['location_id' => $location_id, 'id' => $item->id]);
        $item->csv_link = route('vouchers.getCodeCSV', ['location_id' => $location_id, 'id' => $item->id]);
        $item->completed = 1;
        $item->save();

        $data = $item->toArray();
        $data['unique_id'] = $item->id;
        $data['created_at'] = $item->created_at->timestamp;
        $data['updated_at'] = $item->updated_at->timestamp;

        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($location_id, $id)
    {
      $item = Voucher::find($id);
      if ($item == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      $data = $item->toArray();
      $data['unique_id'] = $item->id;
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;

      return $data;
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
    public function update($location_id, Request $request, $id)
    {
      $item = Voucher::find($id);
      $location = Location::find($location_id);

      if (!isset($item) || !isset($location)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $voucher = $request->input('voucher');
      if (!isset($voucher)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      if (isset($voucher['regenerate_link'])) {
        $item->pdf_link = route('vouchers.getCodePDF', ['location_id' => $location_id, 'id' => $item->id]);
        $item->csv_link = route('vouchers.getCodeCSV', ['location_id' => $location_id, 'id' => $item->id]);
        $item->completed = 1;
        $item->save();

        $data = $item->toArray();
        $data['unique_id'] = $item->id;
        $data['created_at'] = $item->created_at->timestamp;
        $data['updated_at'] = $item->updated_at->timestamp;

        return $data;
      }

      $splash_page_id = isset($voucher['splash_page_id']) ? $voucher['splash_page_id'] : 0;
      $batch_name = isset($voucher['batch_name']) ? $voucher['batch_name'] : '';
      $batch_description = isset($voucher['batch_description']) ? $voucher['batch_description'] : '';
      $is_active = isset($voucher['is_active']) ? $voucher['is_active'] : 0;
      $voucher_value = isset($voucher['voucher_value']) ? $voucher['voucher_value'] : '';
      $quantity = isset($voucher['quantity']) ? $voucher['quantity'] : 0;
      $access_type = isset($voucher['access_type']) ? $voucher['access_type'] : 0;
      $access_period = isset($voucher['access_period']) ? $voucher['access_period'] : '';
      $limit_type = isset($voucher['limit_type']) ? $voucher['limit_type'] : '';
      $session_timeout = isset($voucher['session_timeout']) ? $voucher['session_timeout'] : 0;
      $idle_timeout = isset($voucher['idle_timeout']) ? $voucher['idle_timeout'] : 0;
      $validity_minutes = isset($voucher['validity_minutes']) ? $voucher['validity_minutes'] : 0;
      $access_restrict_period = isset($voucher['access_restrict_period']) ? $voucher['access_restrict_period'] : '';
      $access_restrict_data = isset($voucher['access_restrict_data']) ? $voucher['access_restrict_data'] : '';
      $expiration = isset($voucher['expiration']) ? $voucher['expiration'] : '';
      $secure_password = isset($voucher['secure_password']) ? $voucher['secure_password'] : 0;
      $voucher_format = isset($voucher['voucher_format']) ? $voucher['voucher_format'] : '';
      $csv_link = isset($voucher['csv_link']) ? $voucher['csv_link'] : '';
      $pdf_link = isset($voucher['pdf_link']) ? $voucher['pdf_link'] : '';
      $username = isset($voucher['username']) ? $voucher['username'] : '';
      $location_name = isset($voucher['location_name']) ? $voucher['location_name'] : '';
      $completed = isset($voucher['completed']) ? $voucher['completed'] : 0;
      $download_speed = isset($voucher['download_speed']) ? $voucher['download_speed'] : 0;
      $upload_speed = isset($voucher['upload_speed']) ? $voucher['upload_speed'] : 0;
      $simultaneous_use = isset($voucher['simultaneous_use']) ? $voucher['simultaneous_use'] : 0;

      $item->splash_page_id = $splash_page_id;
      $item->batch_name = $batch_name;
      $item->batch_description = $batch_description;
      $item->is_active = $is_active;
      $item->voucher_value = $voucher_value;
      $item->quantity = $quantity;
      $item->access_type = $access_type;
      $item->limit_type = $limit_type;
      $item->session_timeout = $session_timeout;
      $item->idle_timeout = $idle_timeout;
      $item->validity_minutes = $validity_minutes;
      $item->access_restrict_period = $access_restrict_period;
      $item->access_restrict_data = $access_restrict_data;
      $item->expiration = $expiration;
      $item->secure_password = $secure_password;
      $item->voucher_format = $voucher_format;
      $item->csv_link = $csv_link;
      $item->pdf_link = $pdf_link;
      $item->username = $username;
      $item->completed = $completed;
      $item->download_speed = $download_speed;
      $item->upload_speed = $upload_speed;
      $item->simultaneous_use = $simultaneous_use;

      $item->save();

      $data = $item->toArray();
      $data['unique_id'] = $item->id;
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;

      return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($location_id, $id)
    {
      VoucherCode::where('voucher_id', $id)->delete();

      $item = Voucher::find($id);
      if ($item == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      $item->delete();
      return array('code' => 0, 'message' => 'Success');
    }

    public function getCodePDF($location_id, $id)
    {
      $codes = VoucherCode::where('voucher_id', $id)->get();
      $voucher = Voucher::find($id);
      $location = Location::find($location_id);

      if ($codes == null || $voucher == null || $location == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      $data = array(
        'codes' => $codes,
        'voucher' => $voucher,
        'location' => $location,
      );

      view()->share('data', $data);

      $pdf = PDF::loadView('splash/voucher-code');
      return $pdf->download(Str::random('20') . '.pdf');

      //return view('splash/voucher-code');
    }

    public function getCodeCSV($location_id, $id)
    {
      $codes = VoucherCode::where('voucher_id', $id)->get();
      $voucher = Voucher::find($id);
      if ($codes == null || $voucher == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());
      $csv->insertOne(['Serial', 'Username', 'Password', 'Name', 'Description']);

      foreach ($codes as $code) {
        $item = array();
        $item[] = $code->id;
        $item[] = $code->username;
        $item[] = $code->password;
        $item[] = $voucher->batch_name;
        $item[] = $voucher->batch_description;
        $csv->insertOne($item);
      }

      $csv->output(Str::random('20') . '.csv');
    }
}
