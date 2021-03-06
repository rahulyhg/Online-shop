<?php

/**
 * This is the model class for table "order".
 *
 * The followings are the available columns in table 'order':
 * @property string $id
 * @property integer $user_id
 * @property string $status
 * @property integer $is_paid
 * @property string $shipping_method
 * @property string $payment_method
 * @property integer $postcode
 * @property string $addressee
 * @property string $address
 * @property integer $subtotal
 * @property integer $sale
 * @property integer $shipping
 * @property integer $total
 * @property string $date_create
 * @property string $track_code
 * @property string $phone
 * @property string $email
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Order extends CActiveRecord
{

    public $itemsCount;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, address, subtotal, sale, shipping, total', 'required'),
			array('user_id, is_paid, subtotal, sale, shipping, total, postcode', 'numerical', 'integerOnly'=>true),
			array('id', 'length', 'max'=>13),
			array('status, shipping_method, payment_method, addressee, address, track_code, phone, email', 'length', 'max'=>255),
			array('date_create', 'safe'),
            array('date_create','default', 'value'=>new CDbExpression('NOW()'), 'setOnEmpty'=>false,'on'=>'insert'),
			array('id, user_id, status, is_paid, shipping_method, payment_method, addressee, address, subtotal, sale, shipping, total, date_create, track_code, phone, email, postcode', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'cartItems' => array(self::HAS_MANY, 'CartItem', 'order_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
            'user_id' => 'Пользователь',
            'status' => 'Статус',
            'is_paid' => 'Оплачено',
            'shipping_method' => 'Метод доставки',
            'payment_method' => 'Оплата',
            'postcode' => 'Индекс',
            'addressee' => 'Получатель',
            'address' => 'Адрес',
            'subtotal' => 'Подитог',
            'sale' => 'Скидка',
            'shipping' => 'Доставка',
            'total' => 'Итого',
            'date_create' => 'Дата создания',
            'track_code' => 'Почтовый идентификатор',
			'phone' => 'Телефон',
			'email' => 'Email',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('is_paid',$this->is_paid);
        $criteria->compare('postcode',$this->postcode,true);
		$criteria->compare('addressee',$this->addressee,true);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('track_code',$this->track_code,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('email',$this->email,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'Pagination' => array (
                'PageSize' => 20
            ),
            'sort'=>array(
                'defaultOrder'=>'date_create DESC',
            )
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Order the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getColor(){
		$color = '';
		switch ($this->status) {
			case 'in_progress':
				$color = 'green';
				break;
			case 'confirmation':
				$color = 'red';
				break;
        	case 'collect':
				$color = 'green';
				break;
			case 'payment':
				$color = 'red';
				break;
			case 'shipping_by_rp':
				$color = 'yellow';
				break;
			case 'shipping_by_tc':
				$color = 'yellow';
				break;
			case 'waiting_delivery':
				$color = 'orange';
				break;
			case 'completed':
				$color = 'light_green';
				break;
			case 'not_redeemed':
				$color = 'light_red';
				break;
			case 'canceled':
				$color = 'gray';
				break;
			case 'lost':
				$color = 'purple';
				break;
		}
		return $color;
	}

    public function getOrderItemsCount(){
        $count = 0;
        foreach ($this->cartItems as $item) {
            $count += $item->count;
        }
        return $count;
    }
}
