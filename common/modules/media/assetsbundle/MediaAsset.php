<?php
/**
 * @author: Mithun Mandal
 * @created: 01/08/2014 6:05
 * @file: MediaAsset
 */

namespace common\modules\media\assetsbundle;


class MediaAsset extends \yii\web\AssetBundle
{
	public $sourcePath = '@common/modules/media/assets';

	public $js = [
            'js/dropzone.js'
	];

	public $css = [
            'css/dropzone.css'
	];

	public $depends = [
            'app\assets\AppAsset',
	];
}