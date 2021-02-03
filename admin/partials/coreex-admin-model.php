<?php

use appforge\coreex\includes\generator\Generator;
use appforge\coreex\includes\models\WPCore;

if(WPCore::$app->request->getIsPost())
{
    $generator = new Generator();
    $generatorFiles = $generator->preGenerate(WPCore::$app->request->post);
    $file1 = null;
    if(WPCore::$app->request->post('execgenerator') != null && WPCore::$app->request->post('filedoaction') != null)
        $consolelog = $generator->generate(WPCore::$app->request->post);
}
?>
<?php require(dirname( __FILE__ ).'/header.php'); ?>
<form method="post">
<div class="coge-model-page">
    <h2>Model Generator</h2>
        
        <div class="form-group">
            <label for="tablename_id">Tablename</label>
            <input name="tablename" type="text" id="tablename_id" class="form-control basicAutoComplete" autocomplete="off"/>
            <p class="hint">Tablename of Database</p>
        </div>

        <div class="form-group">
            <label for="modelname_id">Model Class Name</label>
            <input name="modelname" type="text" id="modelname_id" class="form-control" autocomplete="off"/>
            <p class="hint">Model Class Name</p>
        </div>

        <div class="form-group">
            <label for="prefix_id">Prefix</label>
            <input name="prefix" type="text" id="prefix_id" class="form-control" autocomplete="off"/>
            <p class="hint">Table Prefix</p>
        </div>

        <div class="form-group">
            <label for="baseclass_id">Base Classname</label>
            <input name="baseclass" type="text" id="baseclass_id" class="form-control" autocomplete="off"/>
            <p class="hint">ActiveRecord or similar</p>
        </div>

        <div class="form-group">
            <label for="path_id">Path</label>
            <input name="path" type="text" id="path_id" class="form-control" autocomplete="off"/>
            <p class="hint">Path of destination like var/www/vhost/etc</p>
        </div>

        <div class="form-group">
            <label for="code_template_id">Code Template</label>
            <input name="code_template" type="text" id="code_template_id" class="form-control" autocomplete="off"/>
            <p class="hint">Path of Code Template like var/www/vhost/etc/model/default</p>
        </div>

        <input type="submit" class="btn btn-primary"  value="Preview" />
    
</div>
<?php
    // $jsgenerator = json_encode($generatorFiles);
    // echo $jsgenerator;
    $index = 0;
?>
<?php if(isset($generatorFiles) && (WPCore::$app->request->post('execgenerator') == null || WPCore::$app->request->post('execgenerator') != null && WPCore::$app->request->post('filedoaction') == null)): ?>
<div class="generator">
    <input type="hidden" name="execgenerator" value="1">
    <table class="table table-stripped">
    <thead>
        <tr>
            <th>File</th>
            <th>Action</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php
        $unchanged = true;
    ?>
    <?php foreach($generatorFiles as $file): ?>
        <tr>
            <td><a href="#filecompare" class="file-compare" data-bs-toggle="modal" data-bs-target="#filecompare"><?= $file->filename; ?></a>
            <div class="modal fade" id="filecompare" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">File Compare</h5>
                        <button type="button" class="btn btn-close" data-bs-target="#filecompare" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="15px" height="15px" viewBox="0 0 1280.000000 1280.000000" preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,1280.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none"><path d="M1545 12784 c-85 -19 -167 -51 -243 -95 -69 -41 -1089 -1049 -1157 -1144 -101 -141 -140 -263 -140 -440 0 -169 36 -293 125 -427 29 -43 705 -726 2149 -2170 l2106 -2108 -2111 -2112 c-1356 -1358 -2124 -2133 -2147 -2169 -88 -137 -121 -249 -121 -419 -1 -181 37 -302 139 -445 68 -95 1088 -1103 1157 -1144 273 -159 604 -143 853 42 22 17 986 976 2143 2131 l2102 2101 2103 -2101 c1156 -1155 2120 -2114 2142 -2131 69 -51 130 -82 224 -113 208 -70 431 -44 629 71 69 41 1089 1049 1157 1144 101 141 140 263 140 440 0 166 -36 290 -121 422 -25 39 -746 767 -2148 2171 l-2111 2112 2107 2108 c2207 2208 2162 2161 2219 2303 75 187 77 392 4 572 -53 132 -74 157 -615 700 -289 291 -552 548 -585 572 -141 101 -263 140 -440 140 -166 0 -289 -35 -420 -120 -41 -26 -724 -702 -2172 -2149 l-2113 -2111 -2112 2111 c-1454 1452 -2132 2123 -2173 2150 -64 41 -149 78 -230 101 -79 22 -258 26 -340 7z"/></g></svg></button>
                    </div>
                    <div class="modal-body">
                        <?= $file->compareresult; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-modal" data-bs-target="#filecompare">Close</button>
                    </div>
                    </div>
                </div>
            </div>
            </td>
            <td><?= $file->generated; ?></td>
            <td><input type="checkbox" <?php if($file->generated == 'Unchanged') echo 'disabled'; ?> class="" name="filedoaction[<?= $index ?>]"></td>
            <?php
                $index++;
            ?>
        </tr>
        <?php
            if($file->generated != 'Unchanged')
                $unchanged = false;
        ?>
    <?php endforeach; ?>
    </tbody>
    </table>
    <input type="submit" class="btn btn-success" <?php if($unchanged) echo 'disabled'; ?> value="Create" />
</div>

    </form>
<?php endif; ?>



<?php if(isset($consolelog) ): ?>
<div class="result">
        <div class="header">Console</div>
        <div class="body">
        <?php foreach($consolelog as $line): ?>
            <?= $line."<br />"; ?>
        <?php endforeach; ?>
        </div>
        
    
</div>
<?php endif; ?>


<?php
$js = <<<JS

(function( $ ) {
	'use strict';

    $(document).ready(function(){
        var editTableName = false;
        function createModelName(force)
        {
            if($('#modelname_id').val() == '' || force)
            {
                var name = $('#tablename_id').val().replace($('#prefix_id').val(),'');
                name = name.replaceAll('_',' ');
                name = name.toLowerCase().replace(/(?<= )[^\s]|^./g, a=>a.toUpperCase());
                name = name.replaceAll(' ','');
                $('#modelname_id').val(name);
            }
        }

        var element = $('#tablename_id');
        $('#tablename_id').after('<div id="tablename_id_container"></div>');
        $('#tablename_id_container').append(element);
        
        $('#tablename_id').keyup(function(event){ 
            if(event.which == 27)
            {
                $('#autocomplete-items').hide();
            }
            else if($(this).val().length >= 3)
            {
                var field = this;
                var qry = $(this).val();
                $.ajax({
                    url: '?rest_route=/coge/v2/dbtable/'+qry,
                    dataType: 'json',
                })
                .done(function (result) {

                    $('.autocomplete-item').remove();


                    result.forEach(function(item){
                        $('#autocomplete-items').append('<a href="#" class="autocomplete-item">'+item+'</a>');
                    });
                   
                    $('.autocomplete-item').hover(function(){
                        $(this).addClass('active');
                    },function(){
                        $(this).removeClass('active');
                    });

                    $('.autocomplete-item').click(function(){
                        $(field).val($(this).text()); 
                        //$(field).focusout();
                        $('#autocomplete-items').hide();
                        createModelName(true);
                        localStorage['tablename'] = $(field).val();
                    });

                    $('#autocomplete-items').show();
                });
            }
        });

        $('#tablename_id').focus(function(){
            $('#modelname_id').val('');
        });

        $('#tablename_id').focusout(function(){
            if($('.autocomplete-item.active').length == 0)
                $('#autocomplete-items').hide();
            
            createModelName();
        });
        
        $('#tablename_id').after('<div class="autocomplete-dropdown" id="autocomplete-items"></div>');

        $('input[type=text]').focusout(function(){
            var name = $(this).attr('name');
            localStorage[name] = $(this).val();
            
        });
        $('input[type=text]').focus(function()
        {
            $('.generator').hide();
        });

        $('input[type=text]').each(function(index, item){
            var name = $(item).attr('name');
            if(typeof(localStorage[name]) != 'undefined')
                $(item).val(localStorage[name]);
        });

        $('.file-compare').click(function(){
            var modal = $(this).data('bs-target');
            $(modal).modal('show');
        });

        $('.close-modal').click(function(){
            var modal = $(this).data('bs-target');
            $(modal).modal('hide');
        });

        $('.btn-close').click(function(){
            var modal = $(this).data('bs-target');
            $(modal).modal('hide');
        });
        
    });

})( jQuery );
    
JS;
?>
<?php require(dirname( __FILE__ ).'/scripts.php'); ?>
<script type='text/javascript'>
<?= $js; ?>
</script>


<?php require(dirname( __FILE__ ).'/footer.php'); 