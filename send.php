<div class="section">
<b>SEND MESSAGE</b>:<br /><br />
<form action="insert.php" method="POST">
	<table style="font-size: 10pt">
		<tr>
			<td>Mobile Number: </td>
			<td><input type="text" name="MobileNumber" id="MobileNumber"></td>
		</tr>
		<tr>
			<td>Date & Time: </td>
			<td><input type="text" name="SendAt" id="datepicker" pattern="^(((20[1-3][0-9])-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]))\s((0[1-9]|1[0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])))$" title="YYYY-MM-DD HH:MM:SS" autocomplete="off"></td>
		</tr>
		<tr>
			<td></td>
			<td>Format: YYYY-MM-DD HH:MM:SS for sending at a future date/time. Leave blank to send now.</td>
		</tr>
		<tr>
			<td>Message: </td>
			<td><textarea name="Message" id="Message" rows="5" cols="30" maxlength="999"></textarea></td>
		</tr>
		<tr>
			<td></td><td>(Max length: 999 char)</td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" value="Submit" /></td>
		</tr>
	</table>
</form>

</div>

<script>
    jQuery(function($) {
        $('#datepicker').datetimepicker({ 
			dateFormat: 'yy-mm-dd',
			timeFormat: 'HH:mm:ss',
			minDate: 0
		}).val();
    });
  </script>