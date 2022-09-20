<?php
class Form_testerController extends Controller
{
    protected $layout = 'layout_panel';
    protected $sectorsName = ['Creative arts and design', 'Healthcare', 'Marketing', 'Sales', 'Web development'];
    protected $contractType = ['permanent', 'contract', 'temporary'];

    use Validator;

    public function indexAction()
    {
        $this->view->list = Form_testerModel::getAll();
        Request::setTitle('Data generator');
    }

    public function addAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('name',      'Form name',      'required|trim|min_length[1]|max_length[50]');
            $this->validatePost('action',    'Modul /Action',  'required|trim|');
            $this->validatePost('column',    'Columns',        'min_count[1]');
            $this->validatePost('value',     'Values',         'min_count[1]');

            if ($this->isValid()) {

                $data = [
                    'name'    => post('name'),
                    'action'  => post('action'),
                    'columns'  => implode(',', post('column')),
                    'values'   => implode(',', post('value')),
                    'time'    => time()
                ];

                $result = Model::insert('form_tester', $data);
                $insertID = Model::insertID();

                if (!$result && $insertID) {
                    Request::addResponse('redirect', false, url('panel', 'form_tester'));
                } else {
                    Request::returnError('Database error');
                }

            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

    }

    public function sendAction()
    {
        $id = (Request::getUri(0));

        $form = Form_testerModel::get($id);
        if (!$form)
            redirect(url('panel/form_tester'));

        $columns = explode(',', $form->columns);
        $values  = explode(',', $form->values);

        $data = [];
        for ($i = 0; $i < count($columns); $i++)
        {
            $data[$columns[$i]] = $values[$i];
        }
        $data['check'] = true;
        $url =  SITE_URL . $form->action;


        // todo ...
        Request::addResponse('load', $url, 'json:' . json_encode($data));


//        $ch = curl_init();
//
//        curl_setopt($ch, CURLOPT_URL,   $url);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//
//        $server_output = curl_exec($ch);
//
//        curl_close ($ch);



//        if ($result) {
//            Request::returnError('Data inserted');
//        } else {
//            Request::returnError('Database error');
//        }

    }





}
/* End of file */