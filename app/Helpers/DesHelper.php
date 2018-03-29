<?php
//
/*
function encrypt1($key, $iv, $input)
{
    $input = PaddingPKCS7($input);

    $td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');
    mcrypt_generic_init($td, $key, $iv);
    $encrypted_data = mcrypt_generic($td, $input);
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);

    return base64_encode($encrypted_data);
}

function PaddingPKCS7($data)
{
    $block_size = mcrypt_get_block_size('tripledes', 'cbc');
    $padding_char = $block_size - (strlen($data) % $block_size);
    $data .= str_repeat(chr($padding_char), $padding_char);
    return $data;
}
*/

if (!function_exists('encrypt')){
    function encrypt($key, $iv, $input)
    {
        //$input = "D593D4524D2EDD1855EBD700152A86F1_1462805740";
        //$input = "29B41C93F5686F06104F48EE77A11766_1462778517";
        $size = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_CBC); //3DES加密将MCRYPT_DES改为MCRYPT_3DES
        //$size = mcrypt_get_block_size(MCRYPT_3DES, MCRYPT_MODE_CBC);

        $input = pkcs5_pad($input, $size); //如果采用PaddingPKCS7，请更换成PaddingPKCS7方法。
        $key = str_pad($key, 24, '0'); //3DES加密将8改为24
        $td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_CBC, ''); //3DES加密将MCRYPT_DES改为MCRYPT_3DES
        if ($iv == '') {
            $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        }

        @mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = base64_encode($data); //如需转换二进制可改成  bin2hex 转换
        /*
        $l = "unFoYRND//36lgaJFQuHZqAZiePuhQUFoPxrHqSh9SdcUg+fB0ZQhAPlC3oa36ey";
        $s = "lAQhR24aneXfO23+4KZac28EYZWZIBaIIRcB1hvHnvU";

        $d = substr($data, 0, -21);
        if($d !== $s || strlen($l) != strlen($data)){
            echo $data;
            exit();
        }
        */

        return $data;
    }
}

if (!function_exists('pkcs5_pad')){
    function pkcs5_pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
}