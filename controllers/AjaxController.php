<?php
/**
 * Created by PhpStorm.
 * User: evgenii
 * Date: 23.12.2015
 * Time: 19:45
 */

//namespace cabinet\controllers;
namespace openecontmd\backend_api\controllers;

//use common\models\Holiday;

use openecontmd\backend_api\models\SysTabledescription;
use openecontmd\backend_api\models\SysFormdescription;
use openecontmd\backend_api\models\SysTablelist;
use openecontmd\backend_api\models\Structure;

use openecontmd\backend_api\models\SysActionRoles;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class AjaxController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
//                        'actions' => ['translate', 'menu', 'roleset'],
                        'actions' => ['menu', 'editmenu', 'tables', 'incoming', 'test', 'table', 'pattern'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionMenu()
    {
/*
        if(\Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $items = Structure::find()
                ->where(['ParentID' => $data['parent']])
                ->orderBy('OrderIndex')
                ->all();
            $active_rules = SysActionRoles::find()->where(['RoleId' => $data['role']])->all();
            $rules = [];
            foreach($active_rules as $role){
                $rules[$role['ItemId'].$role['action']] = 1;
            }

            return $this->renderAjax('menu_item',[
                'items' => $items,
                'active_rules' => $rules,
            ]);
        }
*/
        if(\Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $items = Structure::find()
            ->where(['ParentID' => $data['parent']])
            ->orderBy('OrderIndex')
            ->all();

            return $this->renderAjax('menu_item',[
                'items' => $items
            ]);
        }
    }


    public function actionTest()
    {
            $data = Yii::$app->request->post();
// '.$data['draw'].'
$json = '{
  "draw": '.$data['draw'].',
  "recordsTotal": 12,
  "recordsFiltered": 12,
  "data": [
    {
      "customerID" : "1",
      "action" : "E/V",
      "first_name": "Airi",
      "last_name": "Satou",
      "position": "Accountant",
      "office": "Tokyo",
      "start_date": "2008-11-08",
      "salary": "162700",
      "id" : 1
    },
    {
      "customerID" : "2",
      "action" : "E/V",
      "first_name": "Angelica",
      "last_name": "Ramos",
      "position": "Chief Executive Officer (CEO)",
      "office": "London",
      "start_date": "2009-10-09",
      "salary": "1200000",
      "id" : 2
    },
    {
      "customerID" : "3",
      "action" : "E/V",
      "first_name": "Ashton",
      "last_name": "Cox",
      "position": "Junior Technical Author",
      "office": "San Francisco",
      "start_date": "2009-01-09",
      "salary": "86000",
      "id" : 3
    },

    {
      "customerID" : "4",
      "action" : "E/V",
      "first_name": "Airi",
      "last_name": "Satou",
      "position": "Accountant",
      "office": "Tokyo",
      "start_date": "2008-11-08",
      "salary": "162700",
      "id" : 1
    },
    {
      "customerID" : "5",
      "action" : "E/V",
      "first_name": "Angelica",
      "last_name": "Ramos",
      "position": "Chief Executive Officer (CEO)",
      "office": "London",
      "start_date": "2009-10-09",
      "salary": "1200000",
      "id" : 2
    },
    {
      "customerID" : "6",
      "action" : "E/V",
      "first_name": "Ashton",
      "last_name": "Cox",
      "position": "Junior Technical Author",
      "office": "San Francisco",
      "start_date": "2009-01-09",
      "salary": "86000",
      "id" : 3
    },

    {
      "customerID" : "7",
      "action" : "E/V",
      "first_name": "Airi",
      "last_name": "Satou",
      "position": "Accountant",
      "office": "Tokyo",
      "start_date": "2008-11-08",
      "salary": "162700",
      "id" : 1
    },
    {
      "customerID" : "8",
      "action" : "E/V",
      "first_name": "Angelica",
      "last_name": "Ramos",
      "position": "Chief Executive Officer (CEO)",
      "office": "London",
      "start_date": "2009-10-09",
      "salary": "1200000",
      "id" : 2
    },
    {
      "customerID" : "9",
      "action" : "E/V",
      "first_name": "Ashton",
      "last_name": "Cox",
      "position": "Junior Technical Author",
      "office": "San Francisco",
      "start_date": "2009-01-09",
      "salary": "86000",
      "id" : 3
    },
    {
      "customerID" : "10",
      "action" : "E/V",
      "first_name": "Airi",
      "last_name": "Satou",
      "position": "Accountant",
      "office": "Tokyo",
      "start_date": "2008-11-08",
      "salary": "162700",
      "id" : 1
    },
    {
      "customerID" : "11",
      "action" : "E/V",
      "first_name": "Angelica",
      "last_name": "Ramos",
      "position": "Chief Executive Officer (CEO)",
      "office": "London",
      "start_date": "2009-10-09",
      "salary": "1200000",
      "id" : 2
    },
    {
      "customerID" : "12",
      "action" : "E/V",
      "first_name": "Ashton",
      "last_name": "Cox",
      "position": "Junior Technical Author",
      "office": "San Francisco",
      "start_date": "2009-01-09",
      "salary": "86000",
      "id" : 3
    }
  ]
}';


$data = json_decode($json);
//var_dump($json, $data);exit;


            return $this->asJson($data);
    }

    public function actionTable()
    {
        $preset = '';
        $post = Yii::$app->request->post();
        $tablename = ($post['tablename']) ? $post['tablename'] : 'xxx';
        $parent = ($post['parent']) ? $post['parent'] : 'xxx';
        $keyname = ($post['keyname']) ? $post['keyname'] : 'xxx';
        $fieldlist = ($post['fieldlist']) ? $post['fieldlist'] : '*';
        if ( $tablename == 'ut_factura') $fieldlist .= ', json_data';
        $showset = ($post['showset']) ? $post['showset'] : [];
        $filterset = ($post['filterset']) ? $post['filterset'] : [];
        $showsetlist = ($post['showsetlist']) ? explode(',', $post['showsetlist']) : [];
        $lang = isset($post['lang']) ? $post['lang'] : 'ro';

        $client_id = (isset($post['client_id'])) ? $post['client_id'] : "";

        $business_token = ($post['business_token']) ? $post['business_token'] : "";

        $preset .= ($client_id != '') ? " AND (client_id = '$client_id') " : "";
        $preset .= ($business_token != '') ? " AND (business_alias = '$business_token') " : "";

        $date_now = date("Y-m-d", time());              // $date_now = '2021-08-28';
        $date_exp = date("Y-m-d", time()+60*60*24*3);   // $date_exp = '2021-08-31';
        $arch = ' AND (is_archived != 1) ';

        foreach ($filterset as $k => $v) {
            if ($v === 'true') {
                switch ($k) {
                    default:
                    case 'all':
                        $status = "'draft', 'actual', 'suspended', 'sended', 'part', 'full', 'rejected', 'archived'";
                        $factor_l = '>=';
                        $date_l = '2021-01-01';
                        $factor_u = '>=';
                        $date_u = '2021-01-01';
                    break;
                    case 'actual':
                        $status = "'draft', 'actual', 'suspended', 'sended', 'part'";
                        $factor_l = '>=';
                        $date_l = '2021-01-01';
                        $factor_u = '>=';
                        $date_u = $date_now;
                    break;
                    case 'deadline':
                        $status = "'draft', 'actual', 'suspended', 'sended', 'part'";
                        $factor_l = '<=';
                        $date_l = $date_exp;
                        $factor_u = '>=';
                        $date_u = $date_now;
                    break;
                    case 'expired':
                        $status = "'draft', 'actual', 'suspended', 'sended', 'part'";
                        $factor_l = '>=';
                        $date_l = '2021-01-01';
                        $factor_u = '<';
                        $date_u = $date_now;
                    break;
                    case 'rejected':
                        $status = "'rejected'";
                        $factor_l = '>=';
                        $date_l = '2021-01-01';
                        $factor_u = '>=';
                        $date_u = '2021-01-01'; // $date_now;
                    break;
                    case 'archived':
                        $status = "'draft', 'actual', 'sended', 'part', 'full', 'rejected', 'archived', 'refunded'";
                        $factor_l = '>=';
                        $date_l = '2021-01-01';
                        $factor_u = '<';
                        $date_u = $date_now;
                        $arch = ' AND (is_archived = 1) ';
                    break;
                }
                if ( $tablename == 'ut_factura') {
                    $preset .= " AND (status IN ($status)) ";
                    $preset .= " AND (due_on $factor_l '$date_l') ";
                    $preset .= " AND (due_on $factor_u '$date_u') ";
                }
            }
        }
        $s = ($post['start']) ? $post['start'] : 0;
        $l = ($post['length']) ? $post['length'] : 10;

        if ( $tablename == 'ut_factura') $preset .= $arch;

        //        $data = (object) [];
        //        $data->post = (object) [];
        //        $data->post = $post;



        try {
            if ($parent == 'xxx')
                $cnt = (new \yii\db\Query())
                ->select("count(*) AS cnt")
                ->andWhere(['client_id' => $client_id])
//                ->andWhere(['business_alias' => $business_token])
                ->from($tablename)->all();
            else
                $cnt = (new \yii\db\Query())
                ->select("count(*) AS cnt")
                ->where(['ParentID' => $parent])
                ->andWhere(['client_id' => $client_id])
//                ->andWhere(['business_alias' => $business_token])
                ->from($tablename)->all();

        } catch (\Exception $e) {
            //Yii::$app->session->setFlash('danger', Yii::t('apl', 'Ошибка при чтении данных!'));
            Yii::$app->session->setFlash('danger', 'Data read error!');
        }

        $where = [];
        if ($post['columns'][0]['search']['value'] != "")
        {
            foreach ($post['columns'] as $k => $v) {
                if ($v['searchable'] == "true") {
                    $where[$v['data']] = '%'.str_replace(" ", "%", $post['columns'][0]['search']['value']).'%';
                }
            }
        }
        else
        {
            foreach ($post['columns'] as $k => $v) {
                if ( ($v['searchable'] == "true") && ($v['search']['value'] != "") ) {
                    //$where[$v['data']] = '%'.$v['search']['value'].'%';
                    $where[$v['data']] = '%'.str_replace(" ", "%", $v['search']['value']).'%';
                }
            }
        }
        //var_dump($where);exit;

        if ($parent == 'xxx') {
            $filter = "SELECT count(*) AS cnt FROM $tablename WHERE ( 1=1 )".$preset;
//           $query = "SELECT `$keyname`, Domain, Alias, `Body`, '' AS `a`, '' AS `b` FROM $tablename WHERE (1)";
            $query = "SELECT $fieldlist FROM $tablename WHERE ( 1=1 )".$preset;
        } else {
            $filter = "SELECT count(*) AS cnt FROM $tablename WHERE (`ParentID` = '$parent')".$preset;
            //           $query = "SELECT `$keyname`, Domain, Alias, `Body`, '' AS `a`, '' AS `b` FROM $tablename WHERE (1)";
            $query = "SELECT $fieldlist FROM $tablename WHERE (`ParentID` = '$parent')".$preset;
        }

//var_dump($query);exit;
        /**/
        if ($post['columns'][0]['search']['value'] != "") {
            $filter .= " AND ( 0 ";
            $query .= " AND ( 0 ";
            foreach ($where as $k => $v) {
                if (in_array('`'.$k.'`', $showsetlist))
                {
                    $filter .= " OR (";
                    $query  .= " OR (";
                    $il = 0;
                    foreach ($showset as $kl => $vl) {
                        if ($vl == 'true') {
                            if ($il > 0) { $filter .= " OR "; $query .= " OR "; }
                            $filter .= " (json_get(`$k`, '$kl') LIKE '$v')";
                            $query  .= " (json_get(`$k`, '$kl') LIKE '$v')";
                            $il++;
                        }
                    }
                    $filter .= " )";
                    $query .= " )";
                } else {
                    $filter .= " OR (`$k` LIKE '$v')";
                    $query .= " OR (`$k` LIKE '$v')";
                }
            }
            $filter .= " ) ";
            $query .= " ) ";
        } else {
            foreach ($where as $k => $v) {
                //var_dump($k, $showsetlist, in_array('`'.$k.'`', $showsetlist));
                if (in_array('`'.$k.'`', $showsetlist)) {
                    $filter .= " AND (";
                    $query  .= " AND (";
                    $il = 0;
                    foreach ($showset as $kl => $vl) {
                        if ($vl == 'true') {
                            if ($il > 0) { $filter .= " OR "; $query .= " OR "; }
                            $filter .= " (json_get(`$k`, '$kl') LIKE '$v')";
                            $query  .= " (json_get(`$k`, '$kl') LIKE '$v')";
                            $il++;
                        }
                    }
                    $filter .= " )";
                    $query .= " )";
                } else {
                    $filter .= " AND (`$k` LIKE '$v')";
                    $query  .= " AND (`$k` LIKE '$v')";
                }
            }
        }
        /**/
        $limit = " LIMIT {$s}, {$l}";
//var_dump($query); exit;


        //            var_dump($filter); exit;

        try {
            $filtered = Yii::$app->db->createCommand($filter)->queryOne();
        } catch (\Exception $e) {
            //Yii::$app->session->setFlash('danger', Yii::t('apl', 'Ошибка при чтении данных!'));
            Yii::$app->session->setFlash('danger', 'Data read error!');
        }
        //var_dump($filtered); exit;

        $order = '';
        foreach ($post['order'] as $k => $v) {
            if ($k == 0)
                $order .= ' ORDER BY `'.$post['columns'][$post['order'][$k]['column']]['data'].'` '.strtoupper($post['order'][$k]['dir']);
                else
                    $order .= ', `'.$post['columns'][$post['order'][$k]['column']]['data'].'` '.strtoupper($post['order'][$k]['dir']);

                    //                var_dump($post['columns'][$post['order'][$k]['column']]['data']);
                    //                var_dump($post['order'][$k]['dir']);
        }
//var_dump($query.$order.$limit);exit;
        try {
//var_dump($query.$order.$limit);exit;
            $items = Yii::$app->db->createCommand($query.$order.$limit)->queryAll();
//var_dump($query.$order.$limit, $items); exit;
            /*
             if (!$items)
             {
             Yii::$app->session->setFlash('danger', 'Ошибка при чтении данных!');
             break;
             }
             */
            //var_dump($items); exit;

        } catch (\Exception $e) {
            //Yii::$app->session->setFlash('danger', Yii::t('apl', 'Error!'));
            Yii::$app->session->setFlash('danger', 'Data read error!');
        }
        //        $data->items = (object)$items;

        if ( $tablename == 'ut_factura') {
            foreach ($items as $k => $v) {
                $e = isset($v['json_data']) ? json_decode($v['json_data']) : null;
                if ($e) {
                    $items[$k]['send_email'] = $v['buyer_email']; // $e->Document->SupplierInfo->Buyer->Email;
                    unset($items[$k]['json_data']);
                }
                else
                    $items[$k]['send_email'] = '';
            }
        }

        $response = (object) [];
        $response->draw = intval($post['draw']);
        $response->recordsTotal = intval($cnt[0]['cnt']);
        $response->recordsFiltered = intval($filtered['cnt']); //count($items);
//        $response->email = 'oleg.dynga@gmail.com';
        $response->data = $items;

        //var_dump($response);exit;

        return $this->asJson($response);
    }


}
