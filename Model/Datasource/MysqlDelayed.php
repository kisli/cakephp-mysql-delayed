<?php
/**
 * MySQL datasource with extensions for delayed insert/update.
 * Copyright 2013, Kisli (http://www.kisli.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2013, Kisli (http://www.kisli.com)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Mysql', 'Model/Datasource/Database');


class MysqlDelayed extends Mysql {

	// When we set this flag to true, renderStatement() will issue
	// either a "INSERT DELAYED" or a "UPDATE LOW_PRIORITY" statement
	private $delayNextQuery = false;


	public function update(Model $model, $fields = array(), $values = null, $conditions = null) {

		$this->delayNextQuery = (isset($model->delayedUpdates) && $model->delayedUpdates);

		return parent::update($model, $fields, $values, $conditions);
	}

	public function create(Model $model, $fields = null, $values = null) {

		$this->delayNextQuery = (isset($model->delayedInserts) && $model->delayedInserts);

		return parent::create($model, $fields, $values);
	}

	public function renderStatement($type, $data) {

		$ltype = strtolower($type);

		if ($this->delayNextQuery && ($ltype == 'create' || $ltype == 'update')) {

			extract($data);
			$aliases = null;

			switch ($ltype) {
			case 'create':
				return "INSERT DELAYED INTO {$table} ({$fields}) VALUES ({$values})";

			case 'update':
				if (!empty($alias)) {
					$aliases = "{$this->alias}{$alias} {$joins} ";
				}
				return "UPDATE LOW_PRIORITY {$table} {$aliases}SET {$fields} {$conditions}";
			}
		}

		return parent::renderStatement($type, $data);
	}
}
