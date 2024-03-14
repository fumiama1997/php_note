<?php



// 税込み価格へ変換する(端数は切り上げ)
// @param str $price 税抜き価格
// @return str 税込み価格

function price_before_tax($price){
    return ceil($price*TAX);
}




function price_before_tax_assoc_array($assoc_array){
    foreach($assoc_array as $key => $value){
        //税込み価格へ変換(端数は切り上げ)
        $assoc_array[$key]['price'] = price_before_tax($assoc_array[$key]['price']);
    }
}