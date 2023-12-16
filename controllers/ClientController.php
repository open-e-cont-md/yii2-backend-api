<?php
//namespace cabinet\controllers;
namespace openecontmd\backend_api\controllers;

use Yii;
use openecontmd\backend_api\models\Client;
use openecontmd\backend_api\models\Customer;
use openecontmd\backend_api\models\SysTabledescription;
use openecontmd\backend_api\models\SysFormdescription;
use openecontmd\backend_api\models\SysTablelist;
use openecontmd\backend_api\models\Structure;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use openecontmd\backend_api\models\Terms;
use openecontmd\backend_api\models\User;

/**
 * Site controller
 */
class ClientController extends BaseController
{
    /**
     * @inheritdoc
     */

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'dashboard', 'list', 'vendor', 'edit', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get', 'post'],
//                    'logout' => ['post', 'get'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionEdit($key = '')
    {
        //Yii::$app->view->params['help'] = Document::getContentHelp('customer');
//echo "<pre>"; var_dump($customer_id, Yii::$app->request->post()); echo "</pre>"; exit;
/**/
        if (!Yii::$app->user->isGuest)
        {
            $_user = User::findByEmail(\Yii::$app->user->identity->email);
//            $_user->client_alias = 'mychauffeurdrive';
            $context['user'] = $_user;
            if ($_user->client_id > 0)
            {
                Yii::$app->params['client'] = $_user->client_id;
                $_client = Customer::findClientByID($_user->client_id);
                $context['client'] = $_client[0];
            }

            $new_key = null;
            if (!empty(Yii::$app->request->post()))
            {
                $p = Yii::$app->request->post();
                $mode = (isset($p['mode'])) ? $p['mode'] : '';
//echo "<pre>"; var_dump($p); echo "</pre>"; //exit;
                $p = self::_prepareData($p, 'ut_customer');
//echo "<pre>"; var_dump($p); echo "</pre>"; exit;

    //            $g = Yii::$app->request->get();
                switch ($p['mode']) {
                    default:
                    case 'update':
                        $customer = Client::findCustomerByEmail($context['client']->client_id, $p['contact_email']);
//echo "<pre>"; var_dump($p, $customer); echo "</pre>"; exit;
/*
                        if ( ($p['contact_email'] != '') && (($p['contact_email'] != $p['prev_email']) && $customer) ) {
                            Yii::$app->session->setFlash('danger', 'Покупатель с таким E-Mail уже есть!');
                            break;
                        }
*/
                        Yii::$app->db->createCommand("START TRANSACTION")->execute();
    /*
                        if ($p['alias'] == '')
                        {
                            Yii::$app->db->createCommand("ROLLBACK")->execute();
                            Yii::$app->session->setFlash('danger', 'Вы не можете удалять ваш псевдоним!');
                            break;
                        }
    */
/**/
                        if (!isset($customer->source_update) || empty($customer->source_update))
                        {
                            $sl = 'self';
                        }
                        else
                        {
                            $l = explode(',', $customer->source_update);
                            $f = false;
                            foreach ($l as $v) if ($v == 'self') $flag = true;
                            if (!$flag) array_push($l, 'self');
                            $sl = implode(',', $l);
                        }

                        if ($p['is_individual'] != '1') {

                        $query = "UPDATE ut_customer SET first_name = '".$p['first_name']."', last_name = '".$p['last_name']."',
                        caption = '".$p['caption']."', caption_long = '".$p['caption_long']."', preferred_language = '".$p['preferred_language']."',
                        idno = '".preg_replace("/[^0-9]/", '', trim($p['idno']))."', idno_verified = '{$p['idno_verified']}',
                        tva = '{$p['tva']}',
                        contact_email = '".$p['contact_email']."', currency = 'MDL', juridical_type = '{$p['juridical_type']}',
                        contact_phone = '".str_replace(" ", "", $p['contact_phone'])."', country_code = '".$p['country_code']."',
                        address = '".$p['address']."',
                        last_update = NOW(), source_update = '{$sl}'
                        WHERE (unique_key = '{$p['unique_key']}')";

                        } else {

                        $query = "UPDATE ut_customer SET first_name = '".$p['i_first_name']."', last_name = '".$p['i_last_name']."',
                        caption = '', caption_long = '', preferred_language = '".$p['i_preferred_language']."',
                        idno = '".preg_replace("/[^0-9]/", '', trim($p['i_idno']))."', idno_verified = '0', tva = '',
                        contact_email = '".$p['i_contact_email']."', currency = 'MDL', juridical_type = 'PF',
                        contact_phone = '".str_replace(" ", "", $p['i_contact_phone'])."', country_code = '".$p['i_country_code']."',
                        address = '".$p['i_address']."',
                        last_update = NOW(), source_update = '{$sl}'
                        WHERE (unique_key = '{$p['unique_key']}')";

                        }

//  city = '".$p['city']."', postal_index = '".$p['postal_index']."',

//echo "<pre>"; var_dump($p, $customer, $query); echo "</pre>"; exit;

/*
is_individual = '{$p['is_individual']}',
idno = '".$p['idno']."',
tva = '{$p['tva']}',
source_update = '$sl',

*/
//var_dump($query);exit;
                        $query_flag = (Yii::$app->db->createCommand($query)->execute()) ? true : false;
/**/

    //                    $query_flag = true;
                        if ($query_flag)
                        {
                            Yii::$app->db->createCommand("COMMIT")->execute();
                            Yii::$app->session->setFlash('success', 'Saved successfully!');
                        }
                        else
                        {
                            Yii::$app->db->createCommand("ROLLBACK")->execute();
                            Yii::$app->session->setFlash('danger', 'Nothing is changed!');
                        }

                        if ($mode != 'stay')
                            return $this->redirect(['/client']);
                        else
                            return $this->redirect(['/client/edit/'.$p['unique_key']]);
                    break;

                    case 'create':

//                        $customer = Customer::findCustomerByEmail($context['client']->alias, $p['contact_email']);
/*
                        if ($customer)
                        {
                            Yii::$app->session->setFlash('danger', 'Покупатель с таким E-Mail уже есть!');
                            break;
                        }
                        $customer = Customer::findCustomerByIDNO($context['client']->alias, $p['idno']);
//echo "<pre>"; var_dump($p, $customer); echo "</pre>"; exit;
                        if ($customer)
                        {
                            Yii::$app->session->setFlash('danger', 'Покупатель с таким IDNO уже есть!');
                            break;
                        }
*/
//echo "<pre>"; var_dump($p); echo "</pre>"; exit;
                        Yii::$app->db->createCommand("START TRANSACTION")->execute();
/**/
                        $new_key = md5(time());

                        if ($p['is_individual'] != '1') {

                            if (trim($p['contact_email']) == '')
                            {
                                Yii::$app->session->setFlash('danger', Terms::translate('email_absent', 'cabinet'));
                                break;
                            }
                            if (!filter_var(trim($p['contact_email']), FILTER_VALIDATE_EMAIL))
                            {
                                Yii::$app->session->setFlash('danger', Terms::translate('email_wrong', 'cabinet'));
                                break;
                            }

                            $query = "
INSERT INTO ut_customer SET unique_key = '$new_key', ParentID = '352e3d14b65fcaf5dec771507334317a',
is_individual = '{$p['is_individual']}',
client_id = '{$context['client']->client_id}',
idno = '".preg_replace("/[^0-9]/", '', trim($p['idno']))."',
idno_verified = '{$p['idno_verified']}', juridical_type = '{$p['juridical_type']}',
tva = '{$p['tva']}',
currency = 'MDL',
first_name = '{$p['first_name']}', last_name = '{$p['last_name']}',
caption = '{$p['caption']}', caption_long = '{$p['caption_long']}',
preferred_language = '{$p['preferred_language']}',
contact_email = '{$p['contact_email']}',
contact_phone = '".str_replace(" ", "", $p['contact_phone'])."', country_code = '{$p['country_code']}',
address = '".$p['address']."',
source_create = 'self',
last_update = NOW()";

                        } else {

                            if (trim($p['i_contact_email']) == '')
                            {
                                Yii::$app->session->setFlash('danger', Terms::translate('email_absent', 'cabinet'));
                                break;
                            }
                            if (!filter_var(trim($p['i_contact_email']), FILTER_VALIDATE_EMAIL))
                            {
                                Yii::$app->session->setFlash('danger', Terms::translate('email_wrong', 'cabinet'));
                                break;
                            }

                            $query = "
INSERT INTO ut_customer SET unique_key = '$new_key', ParentID = '352e3d14b65fcaf5dec771507334317a',
is_individual = '{$p['is_individual']}',
client_id = '{$context['client']->client_id}',
idno = '',
idno_verified = '0', juridical_type = 'PF',
tva = '',
currency = 'MDL',
first_name = '{$p['i_first_name']}', last_name = '{$p['i_last_name']}',
caption = '', caption_long = '',
preferred_language = '{$p['i_preferred_language']}',
contact_email = '{$p['i_contact_email']}',
contact_phone = '".str_replace(" ", "", $p['i_contact_phone'])."', country_code = '{$p['i_country_code']}',
address = '".$p['i_address']."',
source_create = 'self',
last_update = NOW()";

                        }

//  city = '{$p['city']}', postal_index = '{$p['postal_index']}',

                        //var_dump($query);exit;

                        $query_flag = (Yii::$app->db->createCommand($query)->execute()) ? true : false;
//echo "<pre>"; var_dump($customer_id, $p, $query_flag, $query); echo "</pre>"; exit;
/**/

                        if ($query_flag)
                        {
                            Yii::$app->db->createCommand("COMMIT")->execute();
                            Yii::$app->session->setFlash('success', 'Customer saved successfully!');
                        }
                        else
                        {
                            Yii::$app->db->createCommand("ROLLBACK")->execute();
                            Yii::$app->session->setFlash('danger', 'Nothing is changed!');
                        }
                        //            var_dump($query1, $query_flag); exit;
                        return $this->redirect(['/client']);
                    break;

                }

            }
/**/

            //$customer = Customer::findCustomer($new_key ? $new_key : $customer_id);
            $customer = Client::findCustomer($new_key ? preg_replace("/[^0-9]/", '', trim($p['idno'])) : $key);

//echo "<pre>"; var_dump(1, $p, $customer); echo "</pre>"; exit;

            if ($customer)
            {
                if ($new_key)
                    return $this->redirect(['customer']);
                else
                    return $this->render('client_edit', [
                    'context' => $context,
                    'customer_id' => $key,
                    'customer' => $customer
                ]);
            }
            else
            {
//                echo "<pre>"; var_dump(2, $p, $customer, $context); echo "</pre>"; exit;
                return $this->render('client_start', [
                    'context' => $context,
                    'key' => null,
                    'customer' => null,
                    'post' => isset($p) ? $p : []
                ]);
            }
        }
    }

    public function actionList($customer_id = null)
    {
        //Yii::$app->view->params['help'] = Document::getContentHelp('customer');
        $this->layout = 'main';

        if (isset($customer_id)) {
            //echo "<pre>"; var_dump($customer_id); echo "</pre>"; exit;
        }

        $context = Customer::getContext();
        $_user = User::findByEmail(\Yii::$app->user->identity->email);
        $context['user'] = $_user;

        $request_data = ['tab' => 'ut_customer', 'item' => '352e3d14b65fcaf5dec771507334317a'];
        $view = 'list';
        $table = $this->findTable($request_data);
        if ($table) {
            $columns = SysTabledescription::find()
            ->where(['TableName' => $request_data['tab'], 'FieldVisible' => 1])
            ->orderBy('OrderFields')->indexBy('FieldName')->all();
            $rows = array_keys($columns);
            $key_name = SysTabledescription::getKeyName($table);
            $selectors = [];
            $dict = SysFormdescription::find()
            ->where(['TableName' => $request_data['tab']])
            ->andWhere(['isVisible' => '1'])
            ->andWhere(['like', 'SelectSrc', 'SELECT'])
            ->orderBy('FieldOrder')->indexBy('FieldName')->all();
            if ($dict) {
                foreach ($dict as $v) {
                    //$selectors[$v->FieldName] = $v->SelectSrc;
                    $res = Yii::$app->db->createCommand($v->SelectSrc)->queryAll(\PDO::FETCH_CLASS);
                    foreach ($res as $val) {
                        $selectors[$v->FieldName][$val->value] = $val->caption;
                    }
                }
            }
        }
        else
        {
            $columns = $rows = $selectors = $table = $key_name = null;
        }
        $item = Structure::find()->where(['StructureID' => $request_data['item']])->one();

        $data = [
            'item' => $item,
            'columns' => $columns,
            'rows' => $rows,
            'selectors' => $selectors,
            'table' => $table,
            'parent' => $request_data['item'],
            'key_name' => $key_name,
            'context' => $context,
//            'business' => $business
        ];
//echo "<pre>"; var_dump($data); exit;
        return $this->render($view, $data);
    }


    public function actionVendor($customer_id = null)
    {
        //Yii::$app->view->params['help'] = Document::getContentHelp('customer');
        $this->layout = 'main';

        if (isset($customer_id)) {
            //echo "<pre>"; var_dump($customer_id); echo "</pre>"; exit;
        }

        $context = Customer::getContext();
        $_user = User::findByEmail(\Yii::$app->user->identity->email);
        $context['user'] = $_user;

        $request_data = ['tab' => 'ut_customer', 'item' => '352e3d14b65fcaf5dec771507334317a'];
        $view = 'vendor';
        $table = $this->findTable($request_data);
        if ($table) {
            $columns = SysTabledescription::find()
            ->where(['TableName' => $request_data['tab'], 'FieldVisible' => 1])
            ->orderBy('OrderFields')->indexBy('FieldName')->all();
            $rows = array_keys($columns);
            $key_name = SysTabledescription::getKeyName($table);
            $selectors = [];
            $dict = SysFormdescription::find()
            ->where(['TableName' => $request_data['tab']])
            ->andWhere(['isVisible' => '1'])
            ->andWhere(['like', 'SelectSrc', 'SELECT'])
            ->orderBy('FieldOrder')->indexBy('FieldName')->all();
            if ($dict) {
                foreach ($dict as $v) {
                    //$selectors[$v->FieldName] = $v->SelectSrc;
                    $res = Yii::$app->db->createCommand($v->SelectSrc)->queryAll(\PDO::FETCH_CLASS);
                    foreach ($res as $val) {
                        $selectors[$v->FieldName][$val->value] = $val->caption;
                    }
                }
            }
        }
        else
        {
            $columns = $rows = $selectors = $table = $key_name = null;
        }
        $item = Structure::find()->where(['StructureID' => $request_data['item']])->one();

        $data = [
            'item' => $item,
            'columns' => $columns,
            'rows' => $rows,
            'selectors' => $selectors,
            'table' => $table,
            'parent' => $request_data['item'],
            'key_name' => $key_name,
            'context' => $context,
            //            'business' => $business
        ];
        //echo "<pre>"; var_dump($data); exit;
        return $this->render($view, $data);
    }


    public function actionDelete($customer_id = null)
    {
        $this->layout = 'main_datatable';

        if (isset($customer_id)) {
            //echo "<pre>"; var_dump($customer_id); echo "</pre>"; exit;
            $query_flag = Client::deleteCustomer($customer_id);

            if ($query_flag)
            {
                Yii::$app->db->createCommand("COMMIT")->execute();
                Yii::$app->session->setFlash('success', 'Customer deleted successfully!');
            }
            else
            {
                Yii::$app->db->createCommand("ROLLBACK")->execute();
                Yii::$app->session->setFlash('danger', 'Nothing id deleted!');
            }
        }

        return $this->redirect(['list']);
    }

    public function actionDashboard()
    {
        if (!\Yii::$app->user->isGuest) {
            $_user = User::findByEmail(\Yii::$app->user->identity->email);
            $context['user'] = $_user;
            if ($_user->client_id > 0)
            {
                Yii::$app->params['client'] = $_user->client_id;
                $_client = Customer::findClientByID($_user->client_id);
                $context['client'] = $_client[0];
                $_business = Customer::findBusinessesByClientID($_user->client_id);
                if (count($_business) > 0)
                {
                    $context['selected_business'] = 0;
                    $context['business'] = $_business;
                }
//                Yii::$app->session->setFlash('success', 'Добро пожаловать!');
                return $this->render('customer_dashboard', ['context' => $context]);
            }
        }
    }

    protected function findTable($request_data)
    {
        if (($model = SysTablelist::find()->where(['SysTableName' => $request_data['tab']])->one()) !== null) {
            return $model;
        } else {
            return null;
            //throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    protected function _prepareData($data, $table)
    {
        //echo "<pre>";        var_dump($data);
        foreach($data as &$item) {
            if (is_array($item)) {
                foreach($item as &$f) {
                    $f = str_replace("\\", "\\\\", $f);
                    $f = str_replace("'", "\'", $f);
                    $f = str_replace('"', '\"', $f);
                }
            }
        }
        //var_dump($data);        //exit;
        foreach($data as &$item) {
            $item = (is_array($item))
            ? json_encode($item, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT)
            : (self::isJson($item)
                ? $item
                : addslashes($item)); // addslashes
        }
        //var_dump($data); exit;
/*
         foreach($data as &$item) {
         $item = (is_array($item)) ? json_encode($item, JSON_UNESCAPED_UNICODE) : $item;
         }
*/
/*
        foreach($_FILES as $key => $options) {
            $ext = strtolower(pathinfo($options['name'], PATHINFO_EXTENSION));
            $filename = md5(time().$table.$options['tmp_name']) . '.' . $ext;
            $folder = Yii::$app->params['business_image_url'] . $table . '/';
            self::checkFolders($folder);
            if (move_uploaded_file($options['tmp_name'], $folder . $filename)) {
                $data[$key] = $filename;
            }
        }
*/
        return $data;
    }
/*
    private static function checkFolders($folder)
    {
        if (!is_dir($folder)) {
            mkdir($folder);
        }
        if (!is_writable($folder)) {
            chmod($folder, 0777);
        }
        if (!is_dir($folder) || !is_writable($folder)) {
            throw new ErrorException('grant permissions to www-data for  folder' . $folder);
        }
    }
*/

    private static function isJson($string) {
        return ((is_string($string) &&
            (is_object(json_decode($string)) ||
                is_array(json_decode($string))))) ? true : false;
    }

}
