<?php

namespace Infrastructure\Controller\Seo;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class SitemapController extends AbstractController
{
    public function __construct(
        private readonly RouterInterface $router
    ) {}

    #[Route('/sitemap.xml', name: 'app_sitemap', methods: ['GET'])]
    public function index(): Response
    {
        $urls = [];
        $hostname = $this->getParameter('appUrl') ?? 'https://steamtec.fr';

        // Pages publiques importantes (selon robots.txt)
        $urls[] = [
            'loc' => $hostname . $this->router->generate('home'),
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'weekly',
            'priority' => '1.0'
        ];

        $urls[] = [
            'loc' => $hostname . $this->router->generate('app_about'),
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'monthly',
            'priority' => '0.8'
        ];

        $urls[] = [
            'loc' => $hostname . $this->router->generate('app_societe'),
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'monthly',
            'priority' => '0.8'
        ];

        $urls[] = [
            'loc' => $hostname . $this->router->generate('app_configuration'),
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'monthly',
            'priority' => '0.7'
        ];

        $urls[] = [
            'loc' => $hostname . $this->router->generate('app_contact'),
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'monthly',
            'priority' => '0.6'
        ];

        // Nouvelles pages publiques ajoutées dans robots.txt
        $urls[] = [
            'loc' => $hostname . '/client',
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'monthly',
            'priority' => '0.7'
        ];

        $urls[] = [
            'loc' => $hostname . '/nettoyage',
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'monthly',
            'priority' => '0.8'
        ];

        $urls[] = [
            'loc' => $hostname . '/desherbage',
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'monthly',
            'priority' => '0.8'
        ];

        $urls[] = [
            'loc' => $hostname . '/materiel',
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'monthly',
            'priority' => '0.7'
        ];

        $urls[] = [
            'loc' => $hostname . '/accessoire',
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'monthly',
            'priority' => '0.6'
        ];

        $response = new Response($this->renderView('seo/sitemap.xml.twig', [
            'urls' => $urls,
            'hostname' => $hostname
        ]), 200);

        $response->headers->set('Content-Type', 'application/xml');

        return $response;
    }
} 