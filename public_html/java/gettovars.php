<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 05.07.2018
 * Time: 20:37
 */

if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['price']) && isset($_POST['txt'])){
        $text=$_POST['txt'];
        $text=html_entity_decode($_POST['txt']);
        $text=str_replace(['«','»'],'"',$text);
        $data=[
            "customerContact"=>$_POST['email'],
            "items"=>[
                [
                    "quantity"=>1,
                    "price"=>[
                        "amount"=>intval($_POST['price']),
                        ],
                    "tax"=>1,
                    "text"=>$text
                ]
            ]
        ];
        $res=json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS );
        echo $res='res='.$res;
        exit();
        /*
        {
            "customerContact": "+79001231212",
            "taxSystem": 1,
            "items":[
                {
                    "quantity": 1.154,
                    "price": {"amount": 300.23},
                    "tax": 3,
                    "text": "Зеленый чай \"Юн Ву\", кг"
                },
                {
                    "quantity": 2,
                    "price": {"amount": 200.00},
                    "tax": 3,
                    "text": "Кружка для чая, шт., скидка 10%"
                }
            ]
        }
                 * */

    }
}
echo 'res=no';