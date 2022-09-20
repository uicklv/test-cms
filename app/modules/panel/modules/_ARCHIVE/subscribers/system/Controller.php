<?php
class SubscribersController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->list = SubscribersModel::getAll();

        Request::setTitle('Email Subscribers');
    }

    public function export_dataAction()
    {
        Request::ajaxPart();

        $data = Model::fetchAll(Model::select('subscribers'));

        if (is_array($data) && count($data) > 0) {

            //prepare data
            $dataToCsv = [];
            $i = 0;
            foreach ($data as $item) {
                $dataToCsv[$i]['id'] = $item->id;
                $dataToCsv[$i]['email'] = $item->email;
                $dataToCsv[$i]['subscription date'] = date('m.d.Y', $item->time);

                $i++;
            }

            $df = fopen("app/data/tmp/export.csv", 'w');
            fputcsv($df, array_keys(reset($dataToCsv)), ';');
            foreach ($dataToCsv as $row) {
                fputcsv($df, $row, ';');
            }
            fclose($df);

            Request::addResponse('func', 'downloadFile', _SITEDIR_ . 'data/tmp/export.csv');
            Request::endAjax();
        } else {
            Request::returnError('No Data');
        }

    }

    public function deleteAction()
    {
        $id = (Request::getUri(0));
        $user = SubscribersModel::get($id);

        if (!$user)
            redirect(url('panel/analytics/subscribers'));

        $data['deleted'] = 'yes';
        $result = Model::update('subscribers', $data, "`id` = '$id'"); // Update row

        if ($result) {
//            $this->session->set_flashdata('success', 'Location created successfully.');
//            Request::addResponse('redirect', false, url('panel', 'locations', 'edit', $insertID));
        } else {
            Request::returnError('Database error');
        }

        redirect(url('panel/analytics/subscribers'));
    }
}
/* End of file */