<?php
/*
 * a megadott névvel beállít egy alapértelmezett termék attribútumot
 * @return WC_Product_Attribute -> új attribútum vagy false
 * ha a megadott névvel nem létezik előre definiált attribútum
 */
function attributum_letrehozasa($name){ global $wc_product_attributes;
if (isset($wc_product_attributes[$name])){ $ujattr = new WC_Product_Attribute();
	$ujattr->set_id(1); $ujattr->set_name($name);
	$ujattr->set_visible(true); $ujattr->set_variation(false);
// példa alapértelemeztt érték beállítására (az értékeket előre definiálni kell):
	if ($name=='pa_brand'){
		$term = get_term_by('slug', 'adidas', $name);
		$ujattr->set_options(array($term->term_id));
	}
	if ($name=='pa_egyseg'){
		$term = get_term_by('slug', 'darab', $name);
		$ujattr->set_options(array($term->term_id));
	}
return $ujattr; } else { return false; } }

/* alapértelemeztt attribútum hozzárendelése az új termékhez */
function alap_attributumok() { global $product;
	if (! $product) { $product = $GLOBALS['product_object']; }
	if (! $product) { return; }
	$attributes = $product->get_attributes();
// példa attribútumok beállítására (az attribútumokat korábban létre kell hozni):
$defaultAttributes = array(
	'pa_brand',
	'pa_barcode',
	'pa_gyarto',
	'pa_anyag',
	'pa_csomagolas',
	'pa_egyseg'
);

$modosult=false;
foreach ($defaultAttributes as $key){
if (! isset($attributes[$key])){ $ujattr = attributum_letrehozasa($key);
if ($ujattr){ $attributes[$key] = $ujattr; } $modosult = true; } }
if ($modosult){ $product->set_attributes($attributes); } }
add_action('woocommerce_product_write_panel_tabs', 'alap_attributumok');
