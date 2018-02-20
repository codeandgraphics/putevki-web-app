<?php

namespace Frontend\Controllers;

class SitemapController extends BaseController
{

    public function indexAction() {
        $this->view->disable();

        $expireDate = new \DateTime();
        $expireDate->modify('+1 day');

        $this->response->setExpires($expireDate);

        $this->response->setHeader('Content-Type', "application/xml; charset=UTF-8");

        $sitemap = new \DOMDocument("1.0", "UTF-8");

        $urlset = $sitemap->createElement('urlset');
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlset->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');

        foreach($this->router->getRoutes() as $route) {
            $pattern = $route->getPattern();
            $paths = $route->getPaths();

            if(!$paths['excluded'] and !strpos($pattern, '^')) {
                $url = $sitemap->createElement('url');
                $url->appendChild($sitemap->createElement('loc', $this->url->get(ltrim($pattern, '/'))));
                $url->appendChild($sitemap->createElement('changefreq', 'daily'));
                $url->appendChild($sitemap->createElement('priority', '1.0'));
                $urlset->appendChild($url);
            }
        }

        $sitemap->appendChild($urlset);

        echo $sitemap->saveXML();
    }

}
