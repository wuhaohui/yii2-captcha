<?php

namespace common\tool;


class CaptchaAction extends \yii\captcha\CaptchaAction
{

    protected function renderImageByGD($authstr)
    {
        $width = $this->width;
        $height = $this->height;
        $board_width = $width;
        $board_height = $height;
        // 生成一个32帧的GIF动画
        for ($i = 0; $i < 32; $i++) {
            ob_start();
            $image = imagecreate($board_width, $board_height);
            imagecolorallocate($image, 0, 0, 0);
            // 设定文字颜色数组
            $colorList[] = ImageColorAllocate($image, 15, 73, 210);
            $colorList[] = ImageColorAllocate($image, 0, 64, 0);
            $colorList[] = ImageColorAllocate($image, 0, 0, 64);
            $colorList[] = ImageColorAllocate($image, 0, 128, 128);
            $colorList[] = ImageColorAllocate($image, 27, 52, 47);
            $colorList[] = ImageColorAllocate($image, 51, 0, 102);
            $colorList[] = ImageColorAllocate($image, 0, 0, 145);
            $colorList[] = ImageColorAllocate($image, 0, 0, 113);
            $colorList[] = ImageColorAllocate($image, 0, 51, 51);
            $colorList[] = ImageColorAllocate($image, 158, 180, 35);
            $colorList[] = ImageColorAllocate($image, 59, 59, 59);
            $colorList[] = ImageColorAllocate($image, 0, 0, 0);
            $colorList[] = ImageColorAllocate($image, 1, 128, 180);
            $colorList[] = ImageColorAllocate($image, 0, 153, 51);
            $colorList[] = ImageColorAllocate($image, 60, 131, 1);
            $colorList[] = ImageColorAllocate($image, 0, 0, 0);
            $fontcolor = ImageColorAllocate($image, 0, 0, 0);
            $gray = ImageColorAllocate($image, 245, 245, 245);
            $color = imagecolorallocate($image, 255, 255, 255);
            $color2 = imagecolorallocate($image, 255, 0, 0);
            imagefill($image, 0, 0, $gray);
            $space = 20;// 字符间距
            if ($i > 0) {// 屏蔽第一帧
                $top = 0;
                for ($k = 0; $k < strlen($authstr); $k++) {
                    $colorRandom = mt_rand(0, sizeof($colorList) - 1);
                    $float_top = rand(0, 4);
                    $float_left = rand(0, 3);
//                    imagestring(
//                        $image,
//                        6,
//                        $space * $k,
//                        $top + $float_top,
//                        substr($authstr, $k, 1),
//                        $colorList[$colorRandom]
//                    );

                    imagettftext ($image,
                        20,
                        0,
                        $space * $k,
                        $top + $float_top + 25,
                        $colorList[$colorRandom],
                        __DIR__.'/../SourceHanSansCN-Regular.ttf',
                        substr($authstr, $k, 1));
                }
            }
            for ($k = 0; $k < 20; $k++) {
                $colorRandom = mt_rand(0, sizeof($colorList) - 1);
                imagesetpixel($image, rand() % 70, rand() % 15, $colorList[$colorRandom]);

            }
            // 添加干扰线
            for ($k = 0; $k < 3; $k++) {
                $colorRandom = mt_rand(0, sizeof($colorList) - 1);
                $todrawline = 1;
                if ($todrawline) {
                    imageline($image, mt_rand(0, $board_width), mt_rand(0, $board_height), mt_rand(0, $board_width), mt_rand(0, $board_height), $colorList[$colorRandom]);
                } else {
                    $w = mt_rand(0, $board_width);
                    $h = mt_rand(0, $board_width);
                    imagearc($image, $board_width - floor($w / 2), floor($h / 2), $w, $h, rand(90, 180), rand(180, 270), $colorList[$colorRandom]);
                }
            }
            imagegif($image);
            imagedestroy($image);
            $imagedata[] = ob_get_contents();
            ob_clean();
            ++$i;
        }
        $gif = new GIFEncoder($imagedata);
        echo $gif->GetAnimation();
    }
}