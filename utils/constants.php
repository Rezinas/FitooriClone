<?php

	define('SITE_URL', "http://" . $_SERVER['SERVER_NAME']."/plumms/");
	define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT']."/plumms/");

	define('ORIGINAL_IMAGE_MAX_WIDTH', 420);
	define('ORIGINAL_IMAGE_MAX_HEIGHT', 420);

	// define('THUMBNAIL_IMAGE_MAX_WIDTH', 50);
	// define('THUMBNAIL_IMAGE_MAX_HEIGHT', 50);

	// define('TOOLTIP_IMAGE_MAX_WIDTH', 250);
	// define('TOOLTIP_IMAGE_MAX_HEIGHT', 250);

	define('PANEL_IMAGE_MAX_WIDTH', 350);
	define('PANEL_IMAGE_MAX_HEIGHT', 250);

	define('SUCCESS',  "SUCCESS");
	define('ERROR',  "ERROR");
	define('FAILURE',  "SYSTEM FAILURE");


	define('ADMIN',  0);
	define('CUSTOMER',  1);
	define('GUEST',  2);


	define('CATEGORY', 'Terracota|Beaded|Metal');

	define('PRD_ITEM', 'Anklets|Bangles|Earrings|Necklace|Pendant Sets');
	define('STYLES', 'Daily Wear|Party Wear|Work Wear');


	define('PRD_ACTIVE', 1);
	define('PRD_INACTIVE', 0);

	define('PRD_AVAILABLE', 1);
	define('PRD_UNAVAILABLE', 0);

	define('DISCOUNT_TYPE_RUPEES' , 1);
	define('DISCOUNTTYPE_PERCENT' , 2);

	define('PRDIMGDIR', "productImages");

	define('MAINIMG', 1);
	define('ALTERNATE1IMG', 2);
	define('ALTERNATE2IMG', 3);
	define('FEATUREDIMG', 4);
	define('PROMOTEDIMG', 5);


	define('COLORS', 'Unspecified|Red|Blue|Orange|Yellow|Gold|Silver|Pink|Black|Brown|White|Green|Purple|Indigo|Aqua');
	define('TEXTURES', 'Plain|Grilled|Engraved|Painted|DoubleTone|MutliColor');

?>