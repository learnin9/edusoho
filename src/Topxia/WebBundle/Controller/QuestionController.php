<?php
namespace Topxia\WebBundle\Controller;

use Topxia\Service\Util\CloudClientFactory;
use Symfony\Component\HttpFoundation\Request;

class QuestionController extends BaseController
{
    public function fileUrlAction(Request $request)
    {
        $id   = $request->query->get('id');
        $file = $this->getUploadFileService()->getFile($id);
        if (empty($file)) {
            throw $this->createNotFoundException();
        }

        if ($file['targetType'] != 'question') {
            throw $this->createNotFoundException($this->getServiceKernel()->trans('targetType类型不正确'));
        }

        if ($file['storage'] != 'cloud') {
            throw $this->createNotFoundException($this->getServiceKernel()->trans('storage类型不正确'));
        }

        if ($file['convertStatus'] == 'waiting') {
            return $this->createJsonResponse(array('status' => 'waiting', 'message' => $this->getServiceKernel()->trans('音频正在转码中，请稍后再访问.')));
        }

        if ($file['convertStatus'] == 'error') {
            return $this->createJsonResponse(array('status' => 'waiting', 'message' => $this->getServiceKernel()->trans('音频转码失败，请重新上传此音频.')));
        }

        $factory          = new CloudClientFactory();
        $client           = $factory->createClient();
        $result           = $client->generateFileUrl($file['metas2']['shd']['key'], 3600);
        $result['status'] = 'ok';

        return $this->createJsonResponse($result);
    }

    protected function getUploadFileService()
    {
        return $this->getServiceKernel()->createService('File.UploadFileService');
    }
}
