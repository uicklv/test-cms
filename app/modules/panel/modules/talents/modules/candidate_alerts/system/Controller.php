<?php
class Candidate_alertsController extends Controller
{
    protected $layout = 'layout_panel';

    use Validator;

    public function indexAction()
    {
        $this->view->list = Candidate_alertsModel::getAll();

        Request::setTitle('Candidate Alerts');
    }

    public function deleteAction()
    {
        Request::ajaxPart();

        $id = intval(Request::getUri(0));
        $skill = Candidate_alertsModel::get($id);

        if (!$skill)
            Request::returnError('Candidate Alerts error');

        $data['deleted'] = 'yes';
        $result = Model::update('talent_candidate_alerts', $data, "`id` = '$id'"); // Update row

        if ($result) {
            Request::addResponse('func', 'noticeSuccess', 'Removed');
            Request::addResponse('remove', '#item_' . $id);
            Request::endAjax();
        } else {
            Request::returnError('Database error');
        }
    }
}
/* End of file */
