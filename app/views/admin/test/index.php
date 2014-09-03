<html>
<head>
	<title></title>
</head>
<body>
	<form action="<?=fps::getUploadUrl()?>" method="post" enctype="multipart/form-data">
		<input type="file" name="upFile" />
		<button type="submit">Upload</button>
	</form>
</body>
</html>