<?php

class SiteController extends Controller
{
    public $cart;

    public function init(){
        parent::init();
        if(Yii::app()->user->isGuest) {
            if (!empty(Yii::app()->session['cartId']))
                $this->cart = Cart::model()->findByPk(Yii::app()->session['cartId']);
            else
                $this->cart = null;
        } else {
            $this->cart = Cart::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'is_active' => true));
        }
//        self::saveUTM();
    }

    public function actions() {
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('deny',
                'actions'=>array('login', 'register'),
                'users'=>array('@'),
            ),
            array('deny',
                'actions'=>array('customer', 'history', 'historyItem'),
                'users'=>array('?'),
            ),
            array('allow',
                'users'=>array('*'),
            ),
        );
    }

//    private static function saveUTM(){
//        if($_GET && isset($_GET['utm_source'])){
//            $utm = new Utm();
//            $utm->utm_source = $_GET['utm_source'];
//            if ($_GET['utm_medium']) $utm->utm_medium = $_GET['utm_medium'];
//            if ($_GET['utm_campaign']) $utm->utm_campaign = $_GET['utm_campaign'];
//            if ($_GET['utm_term']) $utm->utm_term = $_GET['utm_term'];
//            if ($_GET['utm_content']) $utm->utm_content = $_GET['utm_content'];
//            $utm->save();
//        }
//    }

    public function actionRegistration(){
        $user = new User;
        $user->scenario = 'registration';
        $user->attributes = Yii::app()->request->getPost('User');
        if ($user->validate()) {
            $user->password = $user->password1;
            if($user->save()) {
                if($this->cart) {
                    $this->cart->user_id = $user->id;
                    $this->cart->save();
                }
                echo true;
                Yii::app()->end();
            }
        } else {
            $this->renderPartial('auth/_register',array('modelAuth'=>$user),false,true);
        }
    }

    public function actionLogin(){
        $user=new User;
        $user->scenario = 'login';
        $user->attributes = Yii::app()->request->getPost('User');
        if ($user->validate() && $user->login()) {
            if ($this->cart) {
                $cart = Cart::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
                if($cart) {
                    $cart->addItemsToCart($this->cart->cartItems);
                    if(Yii::app()->controller->action->id == 'order'){
                        $this->cart->user_id = $user->id;
                        $this->cart->is_active = false;
                        $this->cart->save();
                    } else {
                        $this->cart->delete();
                    }
                } else {
                    $this->cart->user_id = $user->id;
                }
            }
            echo true;
            Yii::app()->end();
        } else {
            $this->renderPartial('auth/_login', array('modelAuth' => $user),false,true);
        }
    }

    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(array('site/index'));
    }

    public function actionIndex() {
        $this->render('index');
    }

    public function actionCatalog($type){
        $this->pageTitle=Yii::app()->name .' - '. Yii::app()->params["categories"][$type];
        if(isset($_GET['order']))
            $this->setOrder($_GET['order']);
        if(isset($_GET['size']))
            $this->setSize($_GET['size']);
        $params = [
            'category' => $type,
            'order' => $this->getOrder(),
            'size' => $this->getSize(),
        ];
        if (isset($_GET['subcategory']))
            $params['subcategory'] = $_GET['subcategory'];
        $model = Photo::model()->getPhotos($params);
        if(isset($_GET['order']) || isset($_GET['size']))
            $this->renderPartial('catalog/catalog',array('model'=>$model, 'type'=>$type));
        else
            $this->render('catalog/catalog',array('model'=>$model, 'type'=>$type));
    }

    public function actionModel($type, $id){
        $model = Photo::model()->findByAttributes(array('category'=>$type, 'article'=>$id));
        $this->pageTitle=$model->title.' арт. '.$model->article.' - '.Yii::app()->name;
        $this->render('model',array('model'=>$model, 'type'=>$type));
    }

    public function actionError() {
        if($error=Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    public function actionShipping() {
        $this->pageTitle=Yii::app()->name.' - Доставка и оплата';
        $this->render('shipping');
    }

    public function actionCustomer(){
        $this->pageTitle = Yii::app()->name.' - '.'Личный кабинет';
        $model = User::model()->getUser();
        if (isset($_POST["data_type"])) {
            $model->scenario = $_POST["data_type"];
        } else {
            $model->scenario = 'customer';
        }
        if(isset($_POST['User'])) {
            $model->attributes=$_POST['User'];
            if($model->validate() && $model->save()) {
                Yii::app()->user->setFlash( 'success', "Данные сохранены.");
            }
            if (isset($_POST["data_type"])) {
                $this->renderPartial('user/_'.$_POST["data_type"], array('model' => $model));
            }
        } else {
            $this->render('user/customer', array(
                'model' => $model,
            ));
        }
    }

    public function actionCart(){
        $this->pageTitle = Yii::app()->name.' - '.'Корзина';
        $this->render('cart/cart',array(
            'model'=>$this->cart,
            'path'=>''
        ));
    }

    public function actionOrder($id){
        $this->pageTitle = Yii::app()->name.' - '.'Заказ';
        if(!empty($this->cart->cartItems) && $this->cart->id == $id) {
            if(!Yii::app()->user->isGuest) {
                $user = User::model()->getUser();
                $user->scenario = 'userOrder';
                if ($user->blocked)
                    $user->payment = 'prepay';
            } else {
                $user = new User();
                $user->scenario = 'orderWithRegistration';
            }
            if (isset($_POST['User'])) {
                $user->saveUserData($_POST['User']);
                $errors = $user->getErrors();
                if(empty($errors)) {
                    $order = $this->createOrder($user, $_POST['User']['shipping']);

                    $res['status'] = $order->status;
                    $res['orderId'] = $order->id;
                    $this->sentOrderMail($order);
                    $this->sentOrderMailToAdmin($order);
                } else {
                    $this->renderPartial('order/_order_form', array('user' => $user, 'shipping' => $_POST['User']['shipping']));
                    Yii::app()->end();
                }
                echo json_encode($res);
                Yii::app()->end();
            }
            $this->render('order/order', array(
                'user' => $user,
                'cart' => $this->cart
            ));
        }  else
            throw new CHttpException(404,'К сожалению, страница не найдена.');
    }
    
    public function actionHistory(){
        $this->pageTitle = Yii::app()->name.' - '.'Мои заказы';
        $history = Order::model()->findAllByAttributes(['user_id'=>Yii::app()->user->id], ['order'=>'id DESC']);
        $this->render('user/history',array(
            'history'=>$history,
        ));
    }

    public function actionHistoryItem($id){
        $this->pageTitle = Yii::app()->name.' - '.'Заказ №'.$id;
        $order = Order::model()->findByPk($id);
        if($order->user_id == Yii::app()->user->id) {
            $this->render('user/history_item', array(
                'order' => $order,
            ));
        } else {
            throw new CHttpException(404,'К сожалению, страница не найдена.');
        }
    }

    public function actionUnsubscribe(){
        $this->pageTitle = Yii::app()->name.' - '.'Отписаться от новостей';
        if(!empty($_GET) && isset($_GET['id']) && isset($_GET['email']) && isset($_GET['hash'])){
            $user = User::model()->findByPk($_GET['id']);
            $hash = crypt($user->id, $user->name);
            if($user->id == $_GET['id'] && $user->email == $_GET['email'] && $hash == $_GET['hash']) {
                if ($user->unsubscribe())
                    $this->render('unsubscribe');
                else
                    throw new CHttpException(404,'Попробуйте повторить запрос через какое-то время.');
            } else {
                throw new CHttpException(404,'К сожалению, страница не найдена.');
            }
        } else {
            throw new CHttpException(404,'К сожалению, страница не найдена.');
        }
    }
    
    public function getOrder(){
        if(!isset(Yii::app()->session['catalog_order'])) {
            Yii::app()->session['catalog_order'] = 'по популярности';
        }
        return Yii::app()->session['catalog_order'];
    }

    public function setOrder($order){
        Yii::app()->session['catalog_order'] = $order;
    }

    public function getSize(){
        if(isset(Yii::app()->session['catalog_size']))
            $order = Yii::app()->session['catalog_size'];
        else {
            $order = Yii::app()->session['catalog_size'] = 'все';
        }
        return $order;
    }

    public function setSize($size){
        Yii::app()->session['catalog_size'] = $size;
    }
    
    public function sendOldOrderMailToAdmin($order){
        $mail = new Mail();
        $mail->to = Yii::app()->params['emailTo'];
        $mail->subject = $order->type == 'shipping'?'Заказ розница':'Заказ опт';
        $mail->message = 'ФИО: '.$order->name. ' <br> ';
        $mail->message .= 'E-mail: '.$order->email. ' <br> ';
        $mail->message .= 'Телефон: '.$order->phone. ' <br> ';
        if(!empty($this->postcode))
            $mail->message .= 'Почтовый индекс: '.$order->postcode. ' <br> ';
        if(!empty($this->address))
            $mail->message .= 'Почтовый адрес: '.$order->address. ' <br> ';
        if(!empty($this->company))
            $mail->message .= 'Компания: '.$order->company. ' <br> ';
        if(!empty($this->delivery))
            $mail->message .= 'Способ доставки: '.$order->delivery. ' <br> ';
        if(!empty($this->city))
            $mail->message .= 'Город: '.$order->city. ' <br> ';
        if(!empty($this->order))
            $mail->message .= 'Заказ: '.$order->order. ' <br> ';
        $mail->send();
    }
    
    public function sentOrderMail($order){
        $this->layout = '//layouts/mail';
        $mail = new Mail();
        $mail->to = $order->email;
        $mail->subject = "Заказ № ". $order->id ." оформлен в интернет-магазине ".Yii::app()->params['domain'];
        $mail->message = $this->render('/site/mail/order',array('order'=>$order),true);
        $mail->send();
    }

    public function sentOrderMailToAdmin($order){
        $this->layout = '//layouts/mail';
        $mail = new Mail();
        $mail->to = Yii::app()->params['emailTo'];
        $mail->subject = "Новый заказ розница № ". $order->id;
        $mail->message = $this->render('/site/mail/order_to_admin',array('order'=>$order),true);
        $mail->send();
    }

    public function createOrder($user, $shipping){
        $order = new Order();
        $order->id = floatval(Yii::app()->dateFormatter->format('yyMMddHHmmss', time()));
        $order->user_id = $user->id;
        $order->shipping_method = 'russian_post';
        $order->payment_method = $_POST['User']['payment'];
        $order->phone = $user->phone;
        $order->email = $user->email;
        $order->shipping = $shipping;

        if($_POST['User']['payment'] == 'cod') $order->status = 'in_progress';
        elseif($_POST['User']['payment'] == 'card') $order->status = 'payment';

        $cart = $this->cart;
        $order->subtotal = $cart->subtotal;
        $order->sale = $cart->sale;
        $order->total = $cart->subtotal + $shipping;
        $order->addressee = $user->surname . " " .$user->name . " " . $user->middlename ;
        $order->postcode = $user->postcode;
        $order->address = $user->address;
        if ($order->save()){
            foreach ($cart->cartItems as $item) {
                $item->order_id = $order->id;
                $item->cart_id = null;
                if($item->photo->is_sale) {
                    $item->new_price = $item->photo->price;
                    $item->price = $item->photo->old_price;
                } else {
                    $item->price = $item->photo->price;
                }
                $item->save();
            }
            if(!$cart->is_active) $cart->delete();
            return $order;
        }
    }

}