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

	define('CATEGORY', 'Beaded|Fashion|Terracota|Quilled');
	define('CATEGORY_CODES', '1|2|3|4');
	define('PRD_ITEM', 'Anklets|Bangles|Earrings|Necklace|Pendant Sets');
	define('PRD_ITEM_CODE', '1|2|3|4|5');

	define('CAT_BEADED', 1);
	define('CAT_FASHION', 2);
	define('CAT_TERRACOTA', 3);
	define('CAT_QUILLED', 4);


	define('ITEM_ANKLETS' , 1);
	define('ITEM_BANGLES' , 2);
	define('ITEM_EARRINGS' , 3);
	define('ITEM_NECKLACE' , 4);
	define('ITEM_PENDANTS' , 5);


	define('ANKLET_PARTS', 5);
	define('BANGLES_PARTS', 5);
	define('EARRINGS_PARTS', 5);
	define('NECKLACE_PARTS', 5);
	define('PENDANT_PARTS', 5);

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

?>