<?php

/* 
 * Coder: Mithun Mandal
 * Contact: 01206636679/8527580960
 * Project: Timescity CMS Using Yii2
 */

namespace common\modules\media\uploader;

interface UploaderInterface
{
    /**
     * Convert File or http request to database
     *
     * @param string $model model of upload ActiveRecord
     * @param Array $data
     * @param string $path upload path for uploading
     * @return mixed if converted then it will output as Array else null;
     */
    public function convert($model, $data=[],$path='');
}