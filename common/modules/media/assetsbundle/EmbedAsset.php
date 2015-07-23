<?php
/**
 * @author: Mithun Mandal
 * @created: 01/08/2014 6:05
 * @file: MediaAsset
 */

namespace common\modules\media\assetsbundle;


class EmbedAsset extends \yii\web\AssetBundle
{
	public $sourcePath = '@common/modules/media/assets';

	public $js = [
            'js/embed.js'
	];
        
        public $css = [
            'css/embed.css'
	];

	public $depends = [
            'app\assets\AppAsset',
	];
}