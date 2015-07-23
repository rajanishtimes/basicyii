<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\adminUi\widget;

use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Dropdown renders a Bootstrap dropdown menu component.
 *
 * @see http://getbootstrap.com/javascript/#dropdowns
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @since 2.0
 */
class ColorLegend extends Widget
{
    public $class;
    
    public static $state_colors = [
                        ['class'=>'bg-red','title'=>'Deleted','initial'=>'DL'],                     // state 0
                        ['class'=>'bg-green','title'=>'Published','initial'=>'PU'],                 // state 1
                        ['class'=>'bg-light-blue','title'=>'Ready For Publish','initial'=>'RP'],    // state 2
                        ['class'=>'bg-black','title'=>'Un-published','initial'=>'UP'],              // state 3
                        ['class'=>'bg-orange','title'=>'Draft','initial'=>'DF'],                    // state 4
                        ['class'=>'bg-purple','title'=>'Sourced','initial'=>'SU'],                  // state 5  
                        ['class'=>'bg-maroon','title'=>'Canceled','initial'=>'CAN'],                 // state 6
                        ['class'=>'bg_other','title'=>'Other','initial'=>'OTH'],                 // state 7
                        ['class'=>'bg-teal','title'=>'Temporary Closed','initial'=>'TEMP CLOSE'],                 // state 8
                        ['class'=>'bg_closed','title'=>'Closed','initial'=>'CLOSE'],                 // state 9
                        ['class'=>'opening_soon','title'=>'Opening Soon','initial'=>'OP SOON'],                 // state 10
                        ['class'=>'bg-yellow','title'=>'Closed for the Season','initial'=>'CLOSE SEASON'],                 // state 11 
                        
    ];
    
    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        $this->initOptions();
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        echo Html::beginTag('div',['class'=>'grid-view']);
        echo '
        <div class="row"><div class="col-md-12"><table id="color_legend" class="table" align="right" width="40%">
            <thead>
                <td align="right">';
                    
                        foreach(self::$state_colors as $r){
                            echo '<label class="label '.$r['class'].'">'.$r['title'].'</label>&nbsp;';
                        }
        echo '            
                </td>
            </thead>
        </table></div></div>';
        echo Html::endTag('div');
    }

    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    protected function initOptions()
    {
        $this->options = array_merge([
            'class' => 'grid-view',
        ], $this->options);
    }
}
