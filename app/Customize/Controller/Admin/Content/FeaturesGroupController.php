<?php
namespace Customize\Controller\Admin\Content;

use Eccube\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FeaturesGroupController extends AbstractController
{
    #[Route('/admin/content/features_group_list', name: 'admin_content_features_group_list')]
    public function index(Request $request)
    {
        return $this->render('admin/Content/FeaturesGroup/index.twig');
    }
}