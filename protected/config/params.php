<?php
return array(
    // @todo params
    'carouselUrl' => [
        'first' => '/medallion',
        'second' => '/charm',
        'third' => '/plaque'
    ],
//    'newsCount' => 3,
//    'newPhotoCountInMail' => 6,
    'minOrderSum' => 500,
    'categories' => array(
        'medallion' => 'Медальоны',
        'charm' => 'Шармы',
        'plaque' => 'Диски',
    ),
    'subcategories' => [
        'charm' => [
            'letters' => 'Буквы',
            'family' => 'Семья',
            'love' => 'Любовь',
            'symbols' => 'Символы',
            'animals' => 'Животные',
            'travels' => 'Путешествия',
            'hobby' => 'Увлечения',
            'nature' => 'Природа',
            'crystals' => 'Стразы',
            'food' => 'Еда и напитки',
            'profession' => 'Профессии',
            'celebration' => 'Новый год',
            'other' => 'Другое',
        ],
    ],
//    'shippingFreeCount' => 3,
//    'shippingFreeCountString' => 'трех',
    'maxItemCountInCart' => 10,
    'orderStatuses' => [
        'in_progress' => 'В обработке',
        'confirmation'  => 'Ожидает подтверждения',
        'collect' => 'Собирается',
        'payment' => 'Ожидает оплаты',
        'shipping_by_rp' => 'Передан на доставку в Почту России',
        'shipping_by_tc' => 'Передан на доставку в ТК',
        'waiting_delivery' => 'Ожидает вручения',
        'completed' => 'Выполнен',
        'lost' => 'Потеряна',
        'not_redeemed' => 'Не выкуплен',
        'canceled' => 'Отменен'
    ],
    'paymentMethod' => [
        'cod' => 'При получении на почте',
        'card'  => 'Онлайн-оплата картой',
        'prepay'  => 'Предоплата',
    ],
    'shippingMethod' => [
        'russian_post' => 'Почта России',
        'ems' => 'EMS Почта России',
    ],
);