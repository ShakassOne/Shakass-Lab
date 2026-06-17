<?php
declare(strict_types=1);
if (! defined('ABSPATH')) { exit; }
function shakass_array_get(array $array, string $key, mixed $default = null): mixed { return $array[$key] ?? $default; }
function shakass_active_tools(): array { return ['product'=>'Produit','colors'=>'Couleurs','text'=>'Texte','upload'=>'Importer','logo'=>'Logo / SVG','qr'=>'QR Code','layers'=>'Calques','request'=>'Demande']; }
function shakass_request_statuses(): array { return ['new'=>'Nouveau','processing'=>'En cours','done'=>'Traité','rejected'=>'Refusé']; }
function shakass_default_products(): array { return [
 ['name'=>'T-shirt noir','slug'=>'tshirt-noir','active'=>1,'type'=>'T-shirt','description'=>'Base premium noire polyvalente.','material'=>'Coton peigné','weight'=>'180 g/m²','fit'=>'Unisexe','sizes'=>['S','M','L','XL'],'colors'=>[['name'=>'Noir premium','hex'=>'#08090d'],['name'=>'Blanc','hex'=>'#f5f2ea'],['name'=>'Rouge Shakass','hex'=>'#9b1717']],'default'=>1],
 ['name'=>'T-shirt blanc','slug'=>'tshirt-blanc','active'=>1,'type'=>'T-shirt','description'=>'Base claire pour visuels contrastés.','material'=>'Coton peigné','weight'=>'180 g/m²','fit'=>'Unisexe','sizes'=>['S','M','L','XL'],'colors'=>[['name'=>'Blanc','hex'=>'#f5f2ea'],['name'=>'Noir premium','hex'=>'#08090d']],'default'=>0],
 ['name'=>'Sweat noir','slug'=>'sweat-noir','active'=>1,'type'=>'Sweat','description'=>'Sweat confortable premium.','material'=>'Coton / polyester','weight'=>'280 g/m²','fit'=>'Unisexe','sizes'=>['S','M','L','XL'],'colors'=>[['name'=>'Noir premium','hex'=>'#08090d'],['name'=>'Gris carbone','hex'=>'#747986']],'default'=>0],
 ['name'=>'Polo blanc','slug'=>'polo-blanc','active'=>1,'type'=>'Polo','description'=>'Polo blanc professionnel.','material'=>'Piqué coton','weight'=>'210 g/m²','fit'=>'Unisexe','sizes'=>['S','M','L','XL'],'colors'=>[['name'=>'Blanc','hex'=>'#f5f2ea'],['name'=>'Bleu marine','hex'=>'#111c35']],'default'=>0],
 ]; }
function shakass_default_mockups(): array { return [
 ['product'=>'tshirt-noir','name'=>'Mockup T-shirt premium','front_image'=>'','back_image'=>'','color'=>'#08090d','active'=>1,'front_zone'=>['x'=>25,'y'=>24,'w'=>50,'h'=>58],'back_zone'=>['x'=>27,'y'=>22,'w'=>46,'h'=>56]],
 ['product'=>'sweat-noir','name'=>'Mockup sweat premium','front_image'=>'','back_image'=>'','color'=>'#08090d','active'=>1,'front_zone'=>['x'=>26,'y'=>25,'w'=>48,'h'=>54],'back_zone'=>['x'=>28,'y'=>23,'w'=>44,'h'=>54]],
 ]; }
function shakass_default_pricing(): array { return ['base'=>['tshirt-noir'=>18,'tshirt-blanc'=>18,'sweat-noir'=>34,'polo-blanc'=>26],'formats'=>['A7'=>3,'A6'=>5,'A5'=>8,'A4'=>12,'A3'=>18],'text'=>4,'image'=>9,'qr'=>5,'discounts'=>['1-9'=>0,'10-24'=>5,'25-49'=>10,'50+'=>15]]; }
function shakass_default_settings(): array { return ['email'=>get_option('admin_email'),'accent'=>'#ff5a2c','dark_mode'=>1,'cta'=>'Envoyer ma demande','trust'=>'Demande gratuite et sans engagement','default_product'=>'tshirt-noir','tools'=>array_fill_keys(array_keys(shakass_active_tools()), 1)]; }
function shakass_config_payload(): array { return ['products'=>array_values(array_filter(get_option('shakass_products', shakass_default_products()), fn($p)=>!empty($p['active']))),'mockups'=>array_values(array_filter(get_option('shakass_mockups', shakass_default_mockups()), fn($m)=>!empty($m['active']))),'pricing'=>get_option('shakass_pricing', shakass_default_pricing()),'settings'=>get_option('shakass_settings', shakass_default_settings()),'tools'=>shakass_active_tools()]; }
