<?php
namespace Activity\ActivityBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Topxia\WebBundle\Controller\BaseController;

class ActivityController extends BaseController
{
    public function showAction(Request $request, $id)
    {
        list($activity, $detail, $typeConfg) = $this->getActivityService()->getActivityDetail($id);

        return $this->render($typeConfg['show_page'], array(
            'activity' => $activity,
            'detail'   => $detail
        ));
    }

    public function triggerAction(Request $request, $id)
    {
        $activity = $this->getActivityService()->getActivity($id);
        return $this->createJsonResponse(true);
    }

    protected function getActivityService()
    {
        return $this->createService('Activity:Activity.ActivityService');
    }
}