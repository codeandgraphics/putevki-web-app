<?php

namespace Models;

use \Phalcon\Mvc\Model;

class BaseModel extends Model
{
	/**
	 * @return Meta
	 */
	public function getMeta() : Meta {
		return new Meta($this->metaKeywords, $this->metaDescription);
	}

	public static function name()
	{
		return get_called_class();
	}

	/**
	 * @inheritdoc
	 *
	 * @access public
	 * @static
	 * @param array|string $parameters Query parameters
	 * @return Model\ResultsetInterface
	 */
	public static function find($parameters = null)
	{
		$parameters = self::softDeleteFetch($parameters);

		return parent::find($parameters);
	}

	/**
	 * @inheritdoc
	 *
	 * @access public
	 * @static
	 * @param array|string $parameters Query parameters
	 * @return Model
	 */
	public static function findFirst($parameters = null)
	{
		$parameters = self::softDeleteFetch($parameters);

		return parent::findFirst($parameters);
	}

	/**
	 * @inheritdoc
	 *
	 * @access public
	 * @static
	 * @param array|string $parameters Query parameters
	 * @return mixed
	 */
	public static function count($parameters = null)
	{
		$parameters = self::softDeleteFetch($parameters);

		return parent::count($parameters);
	}

	/**
	 * @access protected
	 * @static
	 * @param array|string $parameters Query parameters
	 * @return mixed
	 */
	public static function softDeleteFetch($parameters = null)
	{
		if (method_exists(get_called_class(), 'getDeleted') === false) {
			return $parameters;
		}

		$deletedField = call_user_func([get_called_class(), 'getDeleted']);

		if ($parameters === null) {
			$parameters = $deletedField . ' = "N"';
		} elseif (
			is_array($parameters) === false &&
			strpos($parameters, $deletedField) === false
		) {
			$parameters .= ' AND ' . $deletedField . ' = "N"';
		} elseif (is_array($parameters) === true) {
			if (
				array_key_exists(0, $parameters) === true &&
				strpos($parameters[0], $deletedField) === false
			) {
				$parameters[0] .= ' AND ' . $deletedField . ' = "N"';
			} elseif (
				array_key_exists('conditions', $parameters) === true &&
				strpos($parameters['conditions'], $deletedField) === false
			) {
				$parameters['conditions'] .= ' AND ' . $deletedField . ' = "N"';
			} else {
				if(!array_key_exists('conditions', $parameters)) {
					$parameters['conditions'] = $deletedField . ' = "N"';
				}
			}
		}

		return $parameters;
	}
}