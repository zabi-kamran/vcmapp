<table class="table">
	<tr>
		<th>S.No</th>
		<th>Name</th>
		<th>GSM No</th>
		<th>
			<label class="checkbox-inline">
				<input type="checkbox" class="checkbox checked" id="check"> All
			</label></th>
		</tr>
		@foreach($data as $row)
		<tr>
			<td>{{ $loop->iteration }}</td>
			<td>{{ $row->fname." ".$row->lname }}</td>
			<td>{{ $row->gsm_no }}</td>
			<td><input type="checkbox" name="cust_id[]" class="select checked" value="<?= $row->id;?>"></td>
		</tr>
		@endforeach
	</table>
	<script>
		$( '#check' ).on('click', function () {
			$( '.select' ).prop('checked', this.checked);
		});
	</script>