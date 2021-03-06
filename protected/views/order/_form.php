<?php
/* @var $this OrderController */
/* @var $model Order */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'order-history-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Сохранить'); ?>
    </div>

    <?php if ($model->user_id): ?>
        <div class="row">
            <div class="label"><?php echo $form->labelEx($model,'user_id'); ?></div>
            <div>
                <a href="/admin/user/update?id=<?= $model->user_id ?>" class="item-list__order__cell-link"><?= $model->user->name .' '. $model->user->surname ?></a>
            </div>
        </div>
    <?php endif; ?>

	<div class="row">
        <div class="label"><?php echo $form->labelEx($model,'status'); ?></div>
        <div><?php echo $form->dropDownList($model,'status', Yii::app()->params['orderStatuses']); ?></div>
	</div>

    <div class="row">
        <div class="label"><?php echo $form->labelEx($model,'track_code'); ?></div>
        <div><?php echo $form->textField($model,'track_code'); ?></div>
    </div>

	<div class="row">
        <div class="label"><?php echo $form->labelEx($model,'is_paid'); ?></div>
        <div><?php echo $form->dropDownList($model,'is_paid', [0 => 'Нет', 1 => 'Да']) ?></div>
	</div>

    <div class="row">
        <div class="label"><?php echo $form->labelEx($model,'shipping_method'); ?></div>
        <div><?php echo $form->dropDownList($model,'shipping_method', Yii::app()->params['shippingMethod']); ?></div>
    </div>

	<div class="row">
        <div class="label"><?php echo $form->labelEx($model,'payment_method'); ?></div>
        <div><?php echo $form->dropDownList($model,'payment_method', Yii::app()->params['paymentMethod']); ?></div>
	</div>

    <div class="row">
        <div class="label"><?php echo $form->labelEx($model,'phone'); ?></div>
        <div><?php echo $form->textField($model,'phone'); ?></div>
    </div>

    <div class="row">
        <div class="label"><?php echo $form->labelEx($model,'email'); ?></div>
        <div><?php echo $form->textField($model,'email'); ?></div>
    </div>

    <div class="row">
        <div class="label"><?php echo $form->labelEx($model,'postcode'); ?></div>
        <div class="long_field"><?php echo $form->textField($model,'postcode'); ?></div>
    </div>

	<div class="row">
        <div class="label"><?php echo $form->labelEx($model,'addressee'); ?></div>
        <div><?php echo $form->textField($model,'addressee'); ?></div>
	</div>

    <div class="row">
        <div class="label"><?php echo $form->labelEx($model,'address'); ?></div>
        <div class="address"><?php echo $form->textField($model,'address'); ?></div>
    </div>

	<div class="row">
        <div class="label"><?php echo $form->labelEx($model,'subtotal'); ?></div>
        <div><?php echo $form->textField($model,'subtotal'); ?></div>
	</div>

	<div class="row">
        <div class="label"><?php echo $form->labelEx($model,'sale'); ?></div>
        <div><?php echo $form->textField($model,'sale'); ?></div>
	</div>

    <div class="row">
        <div class="label"><?php echo $form->labelEx($model,'shipping'); ?></div>
        <div><?php echo $form->textField($model,'shipping'); ?></div>
    </div>

    <div class="row">
        <div class="label"><?php echo $form->labelEx($model,'total'); ?></div>
        <div><?php echo $form->textField($model,'total'); ?></div>
    </div>

    <div class="row">
        <div class="label"><?php echo $form->labelEx($model,'date_create'); ?></div>
        <div>
            <?php if ($model->date_create): ?>
                <?php echo CHtml::encode($model->date_create); ?>
            <?php else: ?>
                <?php echo $form->dateField($model,'date_create', array('value' => date('Y-m-d', time()))); ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($model->cartItems)): ?>
        <ul class="item-list__content">
            <?php foreach($model->cartItems as $cartItem) :?>
                <li class="item-list__item">
                    <?php $this->renderPartial('_item', array('cartItem'=>$cartItem)); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

<!--    <div class="row buttons">-->
<!--        <a class="add_model_button">Добавить в заказ</a>-->
<!--    </div>-->

<!--    <div class="add_model_form_t hide">-->
<!--        <div>-->
<!--            --><?php //echo CHtml::dropDownList('category', 0, Yii::app()->params['categories'], ['class'=>'category']) ?>
<!--            --><?php //echo $form->dropDownList($modelCartItem,'item_id', Photo::model()->getArticlesByCategory('dress')) ?>
<!--            --><?php //echo $form->dropDownList($modelCartItem,'size', Photo::model()->getSizesByArticle('11010')) ?>
<!--            --><?php //echo $form->textField($modelCartItem,'count'); ?>
<!--            <a class="remove_model_button">Удалить</a>-->
<!--        </div>-->
<!--    </div>-->

<!--    <div class="add_model_form"></div>-->


<?php $this->endWidget(); ?>

<script>
    var model_form_count = 0;

    $( "form" ).on( "change", ".category", function() {
        e = $(this);
        $.ajax({
            url: "/ajax/getArticlesByCategory",
            data: {category: $(e).children("option:selected").val()},
            type: "POST",
            dataType: "html",
            success: function (data) {
                select = $(e).parent('div').children("#CartItem_item_id");
                select.empty();
                newOptions = jQuery.parseJSON(data);
                $.each(newOptions, function(value,key) {
                    select.append($("<option></option>")
                        .attr("value", value).text(key));
                });
                if ($(newOptions).length > 0) {
                    replaceSizes(select);
                } else {
                    $(e).parent('div').children("#CartItem_size").empty();
                }
            }
        })
    });

    $( "form" ).on( "change", "#CartItem_item_id", function() {
        replaceSizes(this);
    });

    function replaceSizes(e) {
        console.log(e);
        $.ajax({
            url: "/ajax/getSizesById",
            data: {id: $(e).children("option:selected").val()},
            type: "POST",
            dataType: "html",
            success: function (data) {
                var $select = $(e).parent('div').children("#CartItem_size");
                $select.empty();
                newOptions = jQuery.parseJSON(data);
                $.each(newOptions, function(value,key) {
                    $select.append($("<option></option>")
                        .attr("value", value).text(key));
                });
            }
        })
    }

    $( "form" ).on( "click", ".add_model_button", function() {
        model_form_count++;
        id = "add_model_button_" + model_form_count;
        var $div = $("<div>", {id: id});
        $( ".add_model_form" ).append($div);
        $( ".add_model_form_t" ).children('div').clone().appendTo("#" + id );

        $("#" + id).find("#CartItem_item_id").attr('name', "CartItemNew[" + model_form_count + "][item_id]");
        $("#" + id).find("#CartItem_size").attr('name', "CartItemNew[" + model_form_count + "][size]");
        $("#" + id).find("#CartItem_count").attr('name', "CartItemNew[" + model_form_count + "][count]");
    });

    $( "form" ).on( "click", ".remove_model_button", function() {
        $(this).parent('div').parent('div').remove();
    });
</script>