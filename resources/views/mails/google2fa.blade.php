<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>

	<div style="text-align: center;">
		{!! $details['qr_image'] !!}
		<img src='{!! $details['qr_image'] !!}'/>
	</div>
	<p>Set up your two factor authentication by scanning the barcode below. Alternatively, you can use the code <strong>{{ $details['secret'] }}</strong></p>
</body>
</html>