<?php

namespace App\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomePageController extends AbstractController
{
    #[Route('/home/page', name: 'app_home_page')]
    public function index(): Response
    {
        return $this->render('home_page/index.html.twig', [
            'controller_name' => 'HomePageController',
        ]);
    }
    #[Route('/home/page/about',name:'about_us')]
    public function about(): Response
    {
        return $this->render('home_page/about.html.twig', [
        'controller_name' => 'HomePageController',
        ]);
    }
    #[Route('/home/page/project',name:'our_project')]
    public function project(): Response{
        return $this->render('home_page/project.html.twig', [
        'controller_name'=>'homePageController',])
    ;}

    #[Route('/home/page/testimonial',name:'our_testimonial')]
    public function testimonial(): Response{
        return $this->render('home_page/project.html.twig', [
        'controller_name'=>'homePageController',])
    ;}

    #[Route('/home/page/internships',name:'our_interships')]
    public function internship(): Response{
        return $this->render('home_page/contact.html.twig', [
        'controller_name'=>'homePageController',])
    ;}

    #[Route('/home/page/contact',name:'contact_us')]
    public function contact(): Response{
        return $this->render('home_page/contact.html.twig', [
        'controller_name'=>'homePageController',])
    ;}

    #[Route('/home/page/Access_ERP',name:'access_erp')]
    public function careers(): Response{
        return $this->render('home_page/careers.html.twig', [
        'controller_name'=>'homePageController',])

    ;}
        
}
