<?php

	define('SITE_URL', "http://" . $_SERVER['SERVER_NAME'] . "/");
	define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT']. "/");

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


	define('CATEGORY', 'Terracota|Beaded|Metal|Glass|Semi-precious|Pearl|Wood|Ceramic|Wired|Pearlized glass|Plastic');

	define('PRD_ITEM', 'Anklets|Bangles|Earrings|Necklace|Pendant Sets');
	define('STYLES', 'Daily Wear|Party Wear|Work Wear');


	define('PRD_ACTIVE', 1);
	define('PRD_INACTIVE', 0);

	define('PRD_AVAILABLE', 1);
	define('PRD_UNAVAILABLE', 0);

	define('DISCOUNT_TYPE_RUPEES' , 1);
	define('DISCOUNTTYPE_PERCENT' , 2);

	define('PRDIMGDIR', "productImages");
	define('CMPIMGDIR', "componentImages");

	define('MAINIMG', 1);
	define('ALTERNATE1IMG', 2);
	define('ALTERNATE2IMG', 3);
	define('FEATUREDIMG', 4);
	define('PROMOTEDIMG', 5);

	define('SHIPPINGCHARGES_LARGE', 40);
	define('SHIPPINGCHARGES_MEDIUM', 40);
	define('SHIPPINGCHARGES_SMALL', 40);
	define('SHIPPING_GENERAL', 70);
	define('OVERHEADS', 40);
	define('TAXPERCENT', 15.5);
	define('PROFITPERCENT', 30);
	define('TRANSACTIONPERCENT', 2.3);



	define('COLORS', 'Unspecified|Red|Blue|Orange|Yellow|Gold|Silver|Pink|Black|Brown|White|Green|Purple|Indigo|Aqua|Bronze|Antique|Copper|Gunmetal|Beige|Multicolor');
	define('TEXTURES', 'Plain|Grilled|Engraved|Painted|DoubleTone|MutliColor');
	define ('SOURCES', 'Pandahall|ItsyBitsy|Other');

?>