<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title><?=$message?></title><style>.float-left{float:left;}.text-center{text-align:center;}.float-right{float:right;}</style></head><body><header><h2 class="text-center">


<?=$message?></h2><small class="float-left"><?=(isset($file) ? $file : '').(isset($line) ? '&nbsp;:&nbsp;'.$line : '')?></small><small class="float-right">PHP:<?=phpversion()?></small></header></body></html>
