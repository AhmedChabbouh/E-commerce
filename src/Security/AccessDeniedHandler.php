<?php
namespace App\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler extends AbstractController implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {
        if($this->getUser()){
            $content = 'You need to be an admin to access this page.';
        }
        else{
            $content = 'You need to be logged in to access this page.';
        }

        return $this->render('security/access_denied.html.twig', [
            'message' => $content,
        ],new Response('',403));
    }
}