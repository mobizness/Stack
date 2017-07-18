<style type="text/css">
  @page {
    margin-top: 200px;
    margin-bottom: 70px;
  }

  #header {
    position: fixed;
    left: 0px;
    top: -180px;
    right: 0px;
    height: 150px;
  }

  #footer {
    position: fixed;
    bottom: -20px;
  }

  #footer span {
    font-size: 10px;
  }

  table {
    border-collapse: collapse;
  }

	table td, table th{
		border:2px solid black;
	}

  table th {
    text-align: left;
  }
</style>

<div id="header">
  <h4>Batch Name: {{ $data['voucher']->batch_name }}</h4>
  <span>Description: {{ $data['voucher']->batch_description }}</span><br/>
  <span>Created: {{ $data['voucher']->created_at }}</span><br/>
  <span>Location Name: {{ $data['location']->location_name }}</span><br/>
  <span>Total Codes: {{ $data['voucher']->quantity }}</span><br/>
  <span>ID: {{ $data['voucher']->id }}</span><br/>
</div>

<div id="footer">
  <span>Your internet vouchers</span>
</div>

<div class="container">
  <br/>

	<table width="100%" cellpadding="10">
		<tr>
			<th width="5%">#</th>
      <th width="15%">Username</th>
      <th width="15%">Password</th>
      <th>Description</th>
			<th width="25%">Notes</th>
		</tr>
		@foreach ($data['codes'] as $key => $code)
		<tr>
			<td>{{ $code->id }}</td>
			<td>{{ $code->username }}</td>
			<td>{{ $code->password }}</td>
      <td>{{ $code->description }}</td>
      <td>&nbsp;</td>
		</tr>
		@endforeach
	</table>
</div>
