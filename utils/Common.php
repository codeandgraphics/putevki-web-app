<?php

namespace Utils;

class Common {

	public static function addCompilerActions(&$compiler) {
		$compiler->addFunction('backend_url', function($resolvedParams) {
			return "\Phalcon\Di::getDefault()->get('backendUrl')->get(" . $resolvedParams . ')';
		});
		$compiler->addFunction('images_url', function($resolvedParams) {
			return "\Phalcon\Di::getDefault()->get('imagesUrl')->get(" . $resolvedParams . ')';
		});
		$compiler->addFilter('phone', function($resolvedArgs) {
			return "\Utils\Text::cleanPhone(" . $resolvedArgs . ')';
		});
		$compiler->addFilter('humanDate', function($resolvedArgs) {
			return 'Utils\Text::humanDate(' . $resolvedArgs . ');';
		});
	}
}