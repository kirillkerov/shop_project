<?php

//допустимые типы загружаемых файлов
define('TRUE_FILE_TYPE', [

	'image/jpeg',
	'image/jpg',
	'image/png',

]);

//дирректория загружаемых фалов
define('UPLOAD_DIR', $_SERVER['DOCUMENT_ROOT'] .

	'/img/products/'
	
);