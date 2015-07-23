<?php

/* 
 * Coder: Mithun Mandal
 * Contact: 01206636679/8527580960
 * Project: Timescity CMS Using Yii2
 */
namespace common\modules\media\components;
use yii;
use yii\base\Exception;
use yii\base\Component;

class ExifReader extends Component{

    /**
     * File from which we want to read exif data or thumbnail
     * @var <string>
     */
    public $file;

    /**
     * An exif data array
     * @var <array>
     */
    private $exifData;

    /**
     * Binary thumbnail read from image exif data
     * @var <string>
     */
    private $exifThumbnail;

    /**
     * Thumbnail width
     * @var <integer>
     */
    private $tnWidth;

    /**
     * Thumbnail height
     * @var <integer>
     */
    private $tnHeight;

    /**
     * Thumbnail type
     * - to get the mime type use PHP function: image_type_to_mime_type()
     * @var <integer>
     */
    private $tnType;

    /**
     * Init function which check if exif extension is loaded
     */

    /**
     * Supported files
     */
    private $supported = array('image/jpeg', 'image/tiff');

    /**
     * Init function which check if exif extension is loaded
     */

    public function init() {

        if(!extension_loaded("exif")) {
           throw new Exception("Please check if your exif extension is loaded!", 500);
        }
        parent::init();
    }

    /**
     * Load exif data if file exists
     */
    protected function loadExifData() {

        if (file_exists($this->file)) {

            $imageInfo = getimagesize($this->file);

            if (in_array($imageInfo['mime'], $this->supported)) {
                // exif_read_data($filename, $sections_needed, $sub_arrays, $read_thumbnail)
                if (!$this->exifData = exif_read_data($this->file, null, true, false)) {
                    throw new Exception("Unable to read exif data from your file!", 500);
                }
            }
        }
        else {
            throw new Exception("Specified file does not exist!", 500);
        }

    }

    /**
     * Load exif thumbnail
     * We can load thumbnail only if we want because in loadExifData() in
     * function read_exif_data() parameter $read_thumbnail = false
     */
    protected function loadExifThumbnail() {

        if (is_array($this->exifData) && array_key_exists('THUMBNAIL', $this->exifData)) {
            // read an exif data
            // exif_thumbnail($filename, $width, $height, $imagetype)
            $this->exifThumbnail = exif_thumbnail($this->file, $this->tnWidth, $this->tnHeight, $this->tnType);

        }

    }

    /**
     * Function which returns our choose of exif data sections
     * @param <array> $sections
     * @return <array>
     */
    public function getExifData($sections = array()) {

        $this->loadExifData();

        if (is_array($this->exifData)) {
            $exifDataToReturn = array();
            foreach ($sections as $section) {
                if (is_array($section)) {
                    // in $exclude are keys which won't be showed
                    list($name, $exclude) = $section;
                    if (array_key_exists($name, $this->exifData)) {
                        $exifDataToReturn[$name] = $this->exifData[$name];
                        foreach ($exclude as $item) {
                            unset($exifDataToReturn[$name][$item]);
                        }
                    }
                }
                else {
                    if (array_key_exists($section, $this->exifData)) {
                        if($section == 'GPS'){
                            //$exifDataToReturn[$section] = $this->exifData[$section];
                            $exifDataToReturn[$section]['GPSLong'] = $this->gps($this->exifData['GPS']["GPSLongitude"], $this->exifData['GPS']['GPSLongitudeRef']);
                            $exifDataToReturn[$section]['GPSLat'] = $this->gps($this->exifData['GPS']["GPSLatitude"], $this->exifData['GPS']['GPSLatitudeRef']);

                            $exifDataToReturn[$section]['GPSDateStamp'] = $this->exifData['GPS']["GPSDateStamp"];
                            $exifDataToReturn[$section]['GPSProcessingMode'] = $this->exifData['GPS']["GPSProcessingMode"];
                        }else{
                            $exifDataToReturn[$section] = $this->exifData[$section];
                        }
                    }
                    else {
                        $exifDataToReturn[$section] = array();
                    }
                }
            }
            return $exifDataToReturn;
        }

    }

    /**
     * This function return thumbnail with its parameters
     * @return <array>
     */
    public function getExifThumbnail() {

        $this->loadExifThumbnail();

        if (isset($this->exifThumbnail)) {
            $thumbnailToReturn = array();
            $thumbnailToReturn['width'] = $this->tnWidth;
            $thumbnailToReturn['height'] = $this->tnHeight;
            $thumbnailToReturn['type'] = $this->tnType;
            $thumbnailToReturn['thumb'] = $this->exifThumbnail;
            return $thumbnailToReturn;
        }

    }
    
    private function gps($coordinate, $hemisphere) {
        for ($i = 0; $i < 3; $i++) {
              $part = explode('/', $coordinate[$i]);
              if (count($part) == 1) {
                $coordinate[$i] = $part[0];
              } else if (count($part) == 2) {
                $coordinate[$i] = floatval($part[0])/floatval($part[1]);
              } else {
                $coordinate[$i] = 0;
              }
        }
        list($degrees, $minutes, $seconds) = $coordinate;
        $sign = ($hemisphere == 'W' || $hemisphere == 'S') ? -1 : 1;
        return $sign * ($degrees + $minutes/60 + $seconds/3600);
    }
}
?>