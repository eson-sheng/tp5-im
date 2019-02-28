<?php
/**
 * Created by PhpStorm.
 * User: eson
 * Date: 2019-02-27
 * Time: 22:01
 */

namespace app\api\lib;

/**
 * 图片验证码处理类
 * Class VerifyImage
 * @package app\api\lib
 */
class VerifyImage
{
    private $verifyCode = NULL;

    /**
     * 生成随机字串
     * @return null
     */
    private function createCode ()
    {
        $session = &SessionTools::get('api');
        $this->verifyCode = $session['img_code'];
        return $this->verifyCode;
    }

    /**
     * 生成图片,并加入干扰线，干扰素
     * @param int $width
     * @param int $height
     * @param int $size
     */
    public function createImage ($width = 120, $height = 50, $size = 22)
    {
        ob_end_clean();
        $verifyCode = $this->createCode();
        $image = imagecreatetruecolor($width, $height);

        //白色背景
        $white = imagecolorallocate($image, 228, 228, 228);
        //字体颜色
        $fontColor = imagecolorallocate($image, 0, 0, 180);
        imagefill($image, 0, 0, $white);

        //文字坐标
        $x = ($width + $width / 3 - $size * 6) / 2;
        $y = ($height - $size) / 2 + $size;

        $file = __DIR__ . "/../../../public/link/fonts/simkai.ttf";
        imagettftext($image, $size, 0, $x, $y, $fontColor, $file, $verifyCode);//画在画布上

        //加入干扰点
        for ($i = 0; $i < 220; $i++) {
            $color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
            imagesetpixel($image, rand(0, $width), rand(0, $height), $color);
        }
        //干扰线
        for ($i = 0; $i < 16; $i++) {
            $color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
            imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $color);
        }
        //输出图片
        header("Content-type: image/png");
        imagepng($image);
        //释放资源
        imagedestroy($image);
    }
}