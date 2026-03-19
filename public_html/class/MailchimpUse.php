<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 06.03.2016
 * Time: 20:35
 */

class MailchimpUse {
    private $engine;

    function __construct($api_key)
    {
        $this->engine=new \DrewM\MailChimp\MailChimp($api_key);
        if(BD_SERVER=='127.0.0.1'){
            $this->engine->verify_ssl=false;
        }
    }

    public function get_user_info($list,$email){
        return $this->engine->get('lists/'.$list.'/members/'.$this->engine->subscriberHash($email));
    }
    public function get_user_status($list,$email){
        return $this->get_user_info($list,$email)['status'];
    }

    public function getLastError(){
        return $this->engine->getLastError();
    }

    public function subscribe($list,$email, array $merge_fields=[]){
        $data=[
            'email_address' => $email,
            'status'        => 'subscribed',
        ];
        if(count($merge_fields)>0){
            $data['merge_fields']=$merge_fields;
        }

        $result = $this->engine->post("lists/$list/members", $data);
        return $result;
        //return $this->engine->patch('lists/'.$list.'/members/'.$this->engine->subscriberHash($email),array("status"=>"unsubscribed"));
    }
    public function unsubscribe($list,$email){
        return $this->engine->patch('lists/'.$list.'/members/'.$this->engine->subscriberHash($email),array("status"=>"unsubscribed"));
    }

    public function restoreuser($list,$email){
        return $this->engine->patch('lists/'.$list.'/members/'.$this->engine->subscriberHash($email),array("status"=>"subscribed"));
    }

    public function setMergeField($list,$login,$merge_id,$value){
        $userinfo = $this->get_user_info($list,$login);
        if($userinfo){
            //$merge_fields=$userinfo['merge_fields'];
            $merge_fields['merge_fields'][$merge_id]=$value;
           // $merge_fields[$merge_id]=$value;
            $respath= $this->engine->patch('lists/'.$list.'/members/'.$this->engine->subscriberHash($login),$merge_fields);
            if(!$respath){
                $err=$this->engine->getLastError();
                error_log($err, 1, 'deiww@mail.ru');
            }
            return $respath;
        }
        return false;
    }
}
