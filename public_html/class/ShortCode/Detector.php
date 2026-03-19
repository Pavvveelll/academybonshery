<?php
/**
 * Created by PhpStorm.
 * User: VLAD
 * Date: 08.06.2017
 * Time: 16:42
 */

namespace ShortCode;


class Detector
{
    //параметры можно определять здесь или докально в \LocalConfig::$short_code
    //из локальных вызывается через config
    static $short_codes=[
        '%%VYPUSKNIKI%%'=>[
            'name'=>'VypusknikiBlock',
            //'config'=>'ContactForm'
        ],
        '%VKCOM%'=>[//группа стань грумером
            'name'=>'VKontakte',
            //'config'=>'ContactForm'
        ],
        '%FBCOM%'=>[//станица Facebook
            'name'=>'Facebook',
            //'config'=>'ContactForm'
        ],
        '%OPLATA%'=>[//группа стань грумером
            'name'=>'Oplata',
            //'config'=>'ContactForm'
        ],
        '%OPLATA_API%'=>[//группа стань грумером
            'name'=>'OplataApi',
            //'config'=>'ContactForm'
        ],
        '%BITRIX%'=>[//группа стань грумером
            'name'=>'Bitrix24Test',
            //'config'=>'ContactForm'
        ],
        '%SUBPAGE_LIST%'=>[
            'name'=>'SubPagesList',
            //'config'=>'SubPagesList',
        ],
        '%PODPISKA_MS%'=>[
            'name'=>'Podpiska',
            'nik'=>'subscribe_masterklass',
            'action_url'=>'//petsgroomer.us9.list-manage.com/subscribe/post?u=87b717f9681cc98b9d6fcba82&amp;id=84de7c7eec',
            'secret_field'=>'b_87b717f9681cc98b9d6fcba82_84de7c7eec',
            'type'=>'master',//m
            'analyze'=>'yes',
            'cookie_base'=>'master',
            'cookie_second'=>'kurs',
            //'config'=>'SubPagesList',
        ],
        '%PODPISKA_GR%'=>[
            'name'=>'Podpiska',
            'nik'=>'subscribe',
            'action_url'=>'//petsgroomer.us9.list-manage.com/subscribe/post?u=87b717f9681cc98b9d6fcba82&amp;id=b797dcf1fd',
            'secret_field'=>'b_87b717f9681cc98b9d6fcba82_b797dcf1fd',
            'type'=>'kurs',//s
            'analyze'=>'yes',
            'cookie_base'=>'kurs',
            'cookie_second'=>'master',
            //'config'=>'SubPagesList',
        ],
        '%PODPISKA_POMOSH%'=>[//TODO вести учет именно это подписки
            'name'=>'Podpiska',
            'analyze'=>'no',
            //'nik'=>'subscribe',
            //'cookie_base'=>'kurs',
            //'cookie_second'=>'master',
            'action_url'=>'//petsgroomer.us9.list-manage.com/subscribe/post?u=87b717f9681cc98b9d6fcba82&amp;id=038ca06bf1',
            'secret_field'=>'b_87b717f9681cc98b9d6fcba82_038ca06bf1',
            'type'=>'pomosh',
        ],
        '%%BIZNES%%'=>[//TODO вести учет именно этой подписки
            'name'=>'Podpiska',
            'analyze'=>'no',
            //'nik'=>'subscribe',
            //'cookie_base'=>'kurs',
            //'cookie_second'=>'master',
            'action_url'=>'//petsgroomer.us9.list-manage.com/subscribe/post?u=87b717f9681cc98b9d6fcba82&amp;id=15a2d083bf',
            'secret_field'=>'b_87b717f9681cc98b9d6fcba82_15a2d083bf',
            'type'=>'biznes',
        ],
        '%%P_BONSHERYGROOM%%'=>[
            'name'=>'Podpiska',
            'type'=>'bongroom',
            //'nik'=>'bonsherygroom',
            'action_url'=>'//petsgroomer.us9.list-manage.com/subscribe/post?u=87b717f9681cc98b9d6fcba82&amp;id=2ed44a2afb',
            'secret_field'=>'b_87b717f9681cc98b9d6fcba82_2ed44a2afb',
            'b_text'=>'Подписаться',
            'optin'=>'no',
        ]


/*        '%%REALLIDERS%%'=>[
            'name'=>'RealLiders',
            'config'=>'RealLiders',
            'zag'=>'Хиты продаж ',
            'class'=>'tbloks_light',
        ],
        '%%MAYTONI_LUSTRAS%%'=>[
            'name'=>'RealLiders',
            'config'=>'Maytoni',
            //'zag'=>'Maytoni ',
            'class'=>'tbloks_may',
        ],
        '%%CONTACT_FORM%%'=>[
            'name'=>'ContactForm',
            //'config'=>'ContactForm'
        ],
        '%%SCHEMAORG%%'=>[
            'name'=>'SchemaOrg',
            //'config'=>'SchemaOrg'
        ],
        '%%NOVINKI%%'=>[
            'name'=>'TovarsBlock',
            'config'=>'Novinki',
            'class'=>'tbloks_dyel',
            'zag'=>'Новинки каталога',
        ],
        '%%RASPRODAGA%%'=>[
            'name'=>'TovarsBlock',
            'config'=>'Rasprodaga',
            'class'=>'tbloks_light',
        ],
        '%%LIDERS%%'=>[
            'name'=>'TovarsBlock',
            'config'=>'Liders',
            'class'=>'tbloks_light',
        ],
        '%%NOVINKITOEMAIL%%'=>[
            'name'=>'NovinkiToEmail'
        ],
        '%%MAILSP%%'=>[
            'name'=>'PodpiskaSoft',
            'class'=>'reklam',
            'type'=>'sp',
            'title'=>'Прайс лист для СП',
            'buttontext'=>'Получить прайс',
            'fields'=>[
                'login'=>'e-mail',
                'name'=>'Имя',
            ]
        ],
        '%%MAILREKLAMIST%%'=>[
            'name'=>'PodpiskaSoft',
            'class'=>'reklam',
            'type'=>'reklam',
            'title'=>'Прайс лист на рекламные зонты',
            'buttontext'=>'Получить прайс',
            'fields'=>[
                'login'=>'e-mail',
                'tele'=>'телефон',
            ]
        ],
        '%%MAILREKLAMKEIS%%'=>[
            'name'=>'PodpiskaSoft',
            'class'=>'reklam',
            'type'=>'keis',
            'title'=>'Кейсы и новинки',
            'buttontext'=>'Получать кейсы',
            'fields'=>[
                'login'=>'e-mail',
            ]
        ],
        '%%SOFTUNSUBSCRIBE%%'=>[
            'name'=>'PodpiskaSoftUnsubscribe'
        ],
        '%%OTZYVY%%'=>[
            'name'=>'RevievsMarket',
            'config'=>'RevievsMarket',
        ],

        '%%DOSTAVKA_PVZ%%'=>[
            'name'=>'DostavkaPVZ',
            'sity'=>'Москва',
        ],
        '%%DOSTAVKA_PVZ_SPB%%'=>[
            'name'=>'DostavkaPVZ',
            'sity'=>'Санкт-Петербург',
        ],
        '%%SITEMAP%%'=>[
            'name'=>'HtmlSiteMap',
        ],
        '%%OPTSTAT%%'=>[
            'name'=>'OptStat',
        ],*/
    ];

    /**
     * @var \Page
     */
    static $page;//пробрасываем страницу через классы
    static $replace_mode = 'place';//'block'

    /**
     * @var iShortCode
     */
    static $shclass;

    static function replace($txt){
        //$txt= $page->item_viev->my_item['article'];
        //ищем шорткоды в строке, возвращаем список, один или два знака процента, далее свести к ДВУМ
        if(preg_match_all('/%{1,2}([^% .]{2,20})%{1,2}/', $txt, $allmatches, PREG_OFFSET_CAPTURE)){
            $matches = array_reverse($allmatches[0]);
            //Начинаем с конца
            foreach ($matches as $match){
                if(isset(self::$short_codes[$match[0]])){
                    $param = self::$short_codes[$match[0]];
                    //корректируем локальными настройками, заменяя одинаковые
                    if(isset($param['config'])){//предусмотрено локальное изменение конфигурации
                        //проверяем есть ли локальный конфиг
                         if (isset(\LocalConfig::$short_code[$param['config']])){
                             $param= array_merge($param, \LocalConfig::$short_code[$param['config']]);
                         }
                    }


/*                    if (isset(\LocalConfig::$short_code[$param['name']])){
                        if(isset($param['variant'])){
                            //если один класс используется в нескольких вариантах
                            if (isset(\LocalConfig::$short_code[$param['name']][$param['variant']])){
                                $param= array_merge($param, \LocalConfig::$short_code[$param['name']][$param['variant']]);
                            }

                        }else{
                            $param= array_merge($param, \LocalConfig::$short_code[$param['name']]);
                        }

                    }*/

                    $class='ShortCode'.'\\'.$param['name'];
                    //TODO если класс тот же самый создавать один раз
                    self::$shclass =  new $class();
                    //вопросы повторных вызовов, счетчиков и т.п. решаются в самих классах
                    $replace = self::$shclass->render($param);

                    //что заменяется, сам плейсхолдер или окружающий блок
                    if(isset(self::$shclass->replace_mode) && self::$shclass->replace_mode!='place'){//block
                        if(self::$shclass->replace_mode=='block'){
                            //скрываем ВЕСЬ div
                            //вычисляем начало и конец абзаца
                            $p_start=strrpos(substr($txt,0,$match[1]), '<div');//начало
                            $p_stop = strpos(
                                    substr($txt,$match[1] + mb_strlen($match[0],'UTF-8')),
                                    '</div>') +
                                6 +  $match[1] + mb_strlen($match[0],'UTF-8');

                            $txt=substr_replace($txt,$replace, $p_start,$p_stop-$p_start );
                        }elseif (self::$shclass->replace_mode=='full'){
                            $txt=$replace;//меняем всё
                            return $txt;
                        }
                    }else{
                        $txt=substr_replace($txt,$replace, $match[1],mb_strlen($match[0],'UTF-8'));
                    }
                }
            }
        }
        //$page->item_viev->my_item['article'] = $txt;
        return $txt;
    }
}