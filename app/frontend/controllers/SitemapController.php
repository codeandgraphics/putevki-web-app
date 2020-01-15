<?php

namespace Frontend\Controllers;

use Models\Countries;
use Models\Blog\Posts;
use Models\Entities\Country;
use Models\Regions;

class SitemapController extends BaseController
{
    public function indexAction()
    {
        $this->view->disable();

        $expireDate = new \DateTime();
        $expireDate->modify('+1 day');

        $this->response->setExpires($expireDate);

        $this->response->setHeader(
            'Content-Type',
            'application/xml; charset=UTF-8'
        );

        $sitemap = new \DOMDocument('1.0', 'UTF-8');

        $urlset = $sitemap->createElement('urlset');
        $urlset->setAttribute(
            'xmlns',
            'http://www.sitemaps.org/schemas/sitemap/0.9'
        );
        $urlset->setAttribute(
            'xmlns:xsi',
            'http://www.w3.org/2001/XMLSchema-instance'
        );

        foreach ($this->router->getRoutes() as $route) {
            $pattern = $route->getPattern();
            $paths = $route->getPaths();

            if (!$paths['excluded'] and !strpos($pattern, '^')) {
                $url = $sitemap->createElement('url');
                $url->appendChild(
                    $sitemap->createElement(
                        'loc',
                        $this->url->get(ltrim($pattern, '/'))
                    )
                );
                $url->appendChild(
                    $sitemap->createElement('changefreq', 'daily')
                );
                $url->appendChild($sitemap->createElement('priority', '1.0'));
                $urlset->appendChild($url);
            }
        }

        $countries = Countries::find('uri IS NOT NULL');
        foreach ($countries as $country) {
            $this->addUrl(
                $sitemap,
                $urlset,
                'countries/' . $country->uri,
                'weekly',
                '0.5'
            );
        }

        $posts = Posts::find('uri IS NOT NULL');
        foreach ($posts as $post) {
            $this->addUrl(
                $sitemap,
                $urlset,
                'blog/' . $post->uri,
                'weekly',
                '0.5'
            );
        }

        $sitemap->appendChild($urlset);

        echo $sitemap->saveXML();
    }

    private function addUrl(&$sitemap, &$urlset, $path, $changefreq, $priority)
    {
        $url = $sitemap->createElement('url');
        $url->appendChild(
            $sitemap->createElement('loc', $this->url->get($path))
        );
        $url->appendChild($sitemap->createElement('changefreq', $changefreq));
        $url->appendChild($sitemap->createElement('priority', $priority));
        $urlset->appendChild($url);
    }
}
