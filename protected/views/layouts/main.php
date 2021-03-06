<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="ru" />
    <!-- @todo description-->
    <meta name="description" content="Интернет-магазин уникальных украшений. Медальоны и плавающие шармы для создания собственного украшения."/>
    <!-- @todo favicon-->
    <link rel="icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico?4" type="image/x-icon"/>

    <?php Yii::app()->clientScript->registerScriptFile('/js/common.js?1', CClientScript::POS_HEAD) ?>
    <?php Yii::app()->clientScript->registerScriptFile('/js/magiczoom.js', CClientScript::POS_HEAD) ?>
    <?php Yii::app()->clientScript->registerScriptFile('/js/social-likes.min.js', CClientScript::POS_HEAD) ?>
    <?php Yii::app()->clientScript->registerScriptFile('/js/jquery_lazyload-1.9.3/jquery.lazyload.js', CClientScript::POS_HEAD) ?>

    <!-- blueprint CSS framework -->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
    <!--[if lt IE 8]>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/site.css?7" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/auth.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/magiczoom.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/social-likes_flat.css" />

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

<!--   @todo -->
    <!-- Yandex.Metrika counter --> <script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter40912744 = new Ya.Metrika({ id:40912744, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/40912744" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->    <!-- GoogleAnalytics -->
    <!-- GoogleAnalytics -->
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-55669537-2', 'auto');
        ga('send', 'pageview');

    </script>
    <!-- /GoogleAnalytics -->
</head>

<body class="main">
    <div class="page">
        <div class="header">
            <!--div style="
                    background-color: white;
                    padding: 13px;
                    color: rgb(207, 30, 25);
                    font-size: 18px;">
                Дорогие покупатели! Временно не будет </br>осуществляться прием и отправка оптовых заказов с <strong>1 по 8 июля</strong>. Спасибо за понимание!
            </div-->
            <div class="header__content">
                <a href="/" class="header__logo"></a>
                <!--a href="/" class="header__logo" style="top: 85px;"></a-->
                <div class="header__inner">
                    <div class="header__contact">
                        <div class="header__contact_item">
                            <i class="header__phone-icon"></i>
                            <?= Yii::app()->params['phone'] ?>
                        </div>
                        <div class="header__contact_item_right">
                            <a target="_blank" class="header__social-item header__social-item_vk" href="<?=Yii::app()->params['vkontakteLink']?>"></a>
                            <a target="_blank" class="header__social-item header__social-item_ig" href="<?=Yii::app()->params['instagramLink']?>"></a>
                            <a target="_blank" class="header__social-item header__social-item_fb" href="<?=Yii::app()->params['facebookLink']?>"></a>
                        </div>
                    </div>
                    <div class="user-nav user-nav_signed">
                        <a class="basket-button button button_blue button_big"  href="/cart/">
                            <span class="button__title<?php if (Yii::app()->user->isGuest):?> guest-basket-button<?php endif; ?>">
                                <i class="button__icon"></i>
                                <span class="basket-button-title"><?php if(isset($this->cart->count) && $this->cart->count > 0):?>(<?= $this->cart->count; ?>)<?php endif?></span>
                            </span>
                        </a>
                        <div class="user-nav__sign-in">
                            <?php if (!Yii::app()->user->isGuest):?>
                                <a class="user-nav__user button button_big button_left" href="/customer/">
                                    <i class="button__icon"></i>
                                </a>
                            <?php endif;?>
                            <div id='auth_buttons' class="user-nav__prefs button-dropdown">
                                <?php if (Yii::app()->user->isGuest):?>
                                    <a class="button button_big button_icon button_right" href="#" data-toggle="modal" data-target="#auth_form">
                                        <span class="button__title">Вход</span>
                                    </a>
                                <?php else: ?>
                                    <a class="button button_big button_icon button_right">
                                        <span class="button__title logout">Выход</span>
                                    </a>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="banner">
                <!-- @todo banner-->
<!--                <img src="/data/i/banner.png">-->
            </div>
            <?php
                $this->widget(
                    'booster.widgets.TbNavbar',
                    array(
                        'brand' => false,
                        'fixed' => false,
                        'fluid' => true,
                        'htmlOptions'=>array('class'=>'menu'),
                        'items' => array(
                            array(
                                'class' => 'booster.widgets.TbMenu',
                                'type' => 'navbar',
                                'htmlOptions'=>array('class'=>'menu__list'),
                                'items' =>
                                    array_merge($this->getCategoryForMenu(),
                                    array(
                                        array('label' => 'Доставка и оплата', 'url' => '/shipping', 'active'=>strpos(Yii::app()->request->pathInfo, 'shipping')===false? false:true),
                                    )
                                )
                            )
                        )
                    )
                );
            ?>
        </div><!-- header -->
        <?php if(isset($this->breadcrumbs)):?>
            <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                'links'=>$this->breadcrumbs,
            )); ?><!-- breadcrumbs -->
        <?php endif;?>
        <div class="content">
            <?php echo $content; ?>
        </div>
        <div class="clear"></div>
    </div><!-- page -->
    <div class="footer">
        Copyright &copy; <?php echo date('Y'); ?> by <?php echo Yii::app()->params['domain']; ?>.<br/>
        All Rights Reserved.<br/>
    </div><!-- footer -->
    <?php $this->renderPartial('auth/_auth', array('modelAuth'=>new User('registration'))); ?>
</body>
<script>
    $( "#auth_buttons" ).on( "click", ".logout", function() {
        $.ajax({
            url: "/site/logout",
            success: function( data ) {
                window.location.reload();
            }});
    });
</script>
</html>
