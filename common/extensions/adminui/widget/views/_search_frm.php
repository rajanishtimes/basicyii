<?php
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
?>
<?php
    $query = Yii::$app->request->getQueryParams();
    $frmClass = 'collapsed-box';
    $style = 'display:none';
    if($config['default_open'] || isset($query['_submit'])){
        $frmClass = '';
        $style = '';
    }
?>
<div class="box box-solid box-info <?=$frmClass?>">
    <div class="box-header" data-widget="collapse" style="cursor:pointer">
        <h3 class="box-title"><?php echo $config['form_title']?></h3>
        <div class="box-tools pull-right">
            <button class="btn btn-info btn-sm btn-submit">
                <i class="fa fa-chevron-circle-down"></i>
            </button>
        </div>
    </div>
    <div class="box-body" style="<?=$style?>">
        <?php $form = ActiveForm::begin([
            'action' => Url::to($config['action']),
            'method' => 'get',
            'class'=>'form-inline',
            'options'  =>  [
                'id' => 'search_form'
            ]
        ]); 
        ?>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-2">
                    <label for="">Search By Keyword :</label>
                </div>
                <div class="col-sm-10">
                        <?php 
                            echo $form->field($model,'meta', ['template'=>'<div class="input-group input-group-sm metasearch">{input}<span class="input-group-btn">
                            <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-search" title="Search"></i></button>
                        </span></div>'])->textInput(['placeholder'=>'Enter Keyword'])->label(false);
                        ?>
                        
                    
                </div>
            </div><br><div class="clearfix"></div>
            <div class="row">
                <?php
                    if(!empty($fields)){
                ?>
                <div class="col-md-2">
                    <label for=""><?=$config['filter_label']?></label>
                </div>
                <div class="col-md-10"> 
                    <?php
                            foreach($fields as $f){
                                $f['label'] = !empty($f['label']) ? $f['label'] : $model->getAttributeLabel($f['name']); 
                    ?>
                    <div class="col-xs-12  col-sm-4 col-lg-3 col-md-3">
                        
                    <?php
                    
                                switch ($f['type']){
                                    case 'select':
                                        $options = !empty($f['options']) ?  $f['options'] : [];
                                        echo $form->field($model,$f['name'])->dropDownList($f['default'],$options)->label($f['label'],['class'=>'col-xs-12  col-sm-12 col-lg-12 col-md-12']);
                                        break;
                                    case 'radio':
                                        $options = !empty($f['options']) ?  $f['options'] : [];
                                        echo $form->field($model,$f['name'])->radioList($f['item'],$options)->label($f['label'],['class'=>'col-xs-12  col-sm-12 col-lg-12 col-md-12']);
                                        break;
                                }
                    ?>
                        
                    </div>
                    <?php
                            }
                        }
                    ?>
                
                
                    <?php
                        foreach($fields as $f){
                            if($f['type'] == 'select'){
                                $options = !empty($f['options']) ?  $f['options'] : [];
                                
                            }
                        }
                    ?>
                </div>
                <div class="row">
                    <div class="text-center">
                        <?php echo Html::input('hidden','_submit','1')?>
                        <button class="btn btn-danger btn-md btn-submit">Search</button>
                    </div>
                </div>
                
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$view->registerJs('
    $(".bootmulti").multiselect({
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        disableIfEmpty :true,
        numberDisplayed: 1,
    });
    
    $(".metasearch input").blur(function(){
        checkvalue();
    });
    
    $("#search_form").submit(function(){
        return checkvalue();
    });
    
    function checkvalue(){
        elem = $(".metasearch input");
        var metaval = $(elem).val();
        if(metaval.length <= 2 && metaval != ""){
            if(isNumber(metaval)){
               $(elem).parent().find(".help-block").remove();
                $(elem).parent().removeClass("has-error").addClass("has-success");
                return true;
            }else{ 
                 $(elem).parent().find(".help-block").remove();
                $(elem).parent().append("<div class=\"clearfix\"></div><div class=\"help-block\" style=\"position:absolute;left:0\">Keyword must be 3 characters long.</div>");
                $(elem).parent().addClass("has-error");
                return false;
            }
        }else if(metaval.length > 2 || metaval == "") {
            $(elem).parent().find(".help-block").remove();
            $(elem).parent().removeClass("has-error").addClass("has-success");
            return true;
        }else{
            return true;
        }
    }
    
    function isNumber(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }
    
',View::POS_READY);

?>