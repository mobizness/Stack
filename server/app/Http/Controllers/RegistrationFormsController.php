<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\RegistrationForm;
use App\RegistrationField;

class RegistrationFormsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $splash_page_id = $_GET['splash_page_id'];
      if (!isset($splash_page_id)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $registraionForm = RegistrationForm::where('splash_page_id', $splash_page_id)->first();
      if (!isset($registraionForm)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $data = $registraionForm->toArray();
      $data['created_at'] = $registraionForm->created_at->timestamp;
      $data['updated_at'] = $registraionForm->updated_at->timestamp;

      $data['registration_fields'] = array();
      $registrationFields = RegistrationField::where('form_id', $registraionForm->id)->get();
      foreach ($registrationFields as $field) {
        $item = $field->toArray();
        $item['created_at'] = $field->created_at->timestamp;
        $item['updated_at'] = $field->updated_at->timestamp;
        $item['_id'] = $field->id;
        $item['unique_id'] = $field->id;

        $data['registration_fields'][] = $item;
      }

      return $data;
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
    public function update($location_id, Request $request, $id)
    {
      $item = RegistrationForm::find($id);
      if ($item == null)
        return array('code' => $id, 'message' => 'Invalid Request1');

      $form = $request->input('form');
      if (!isset($form)) {
        return array('code' => 0, 'message' => 'Invalid Request2');
      }

      $item->message = $form['message'];
      $item->save();

      RegistrationField::where('form_id', $item->id)->delete();

      $fields = $form['registration_fields'];
      foreach ($fields as $field) {
        if (!isset($field['label'])) continue;
        RegistrationField::create([
          'form_id' => $item->id,
          'label' => $field['label'],
          'field_type' => $field['field_type'],
          'name' => $field['name'],
          'required' => isset($field['required']) ? $field['required'] : 0,
          'order' => $field['order'],
          'hidden' => isset($field['hidden']) ? $field['hidden'] : 0,
          'value' => isset($field['value']) ? $field['value'] : '',
        ]);
      }

      $data = $item->toArray();
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;

      $data['registration_fields'] = array();
      $registrationFields = RegistrationField::where('form_id', $item->id)->get();
      foreach ($registrationFields as $field) {
        $item1 = $field->toArray();
        $item1['created_at'] = $field->created_at->timestamp;
        $item1['updated_at'] = $field->updated_at->timestamp;
        $item1['_id'] = $field->id;
        $item1['unique_id'] = $field->id;

        $data['registration_fields'][] = $item1;
      }

      return $data;

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
