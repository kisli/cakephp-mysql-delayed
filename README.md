cakephp-mysql-delayed
=====================

A MySQL datasource with extensions for delayed insert/update.

## Overview

This custom datasource is based on the default Mysql datasource provided by CakePHP, and will allow to use INSERT DELAYED and UPDATE LOW_PRIORITY statements instead of the default INSERT/UPDATE ones for some models in your application.

## Installation

Simply copy the file "MysqlDelayed.php" into your "app/Model/Datasource" directory.

Then, you will be able to use the datasource from your database config:

    class DATABASE_CONFIG {
        public $default = array(
            'datasource' => 'MysqlDelayed',   // instead of 'Mysql'
            'persistent' => false,
            'host' => ...
        );
    }

## Usage

The component can be enabled on per-model basis. To enable delayed insert and/or update in a model, declare public boolean variables named **$delayedInserts** and **$delayedUpdates** into the class of this model:

    class MyModel {
        public $delayedInserts = true;   // each INSERT will actually be a INSERT DELAYED
        public $delayedUpdates = true;   // each UPDATE will actually be a UPDATE LOW_PRIORITY
        ...

That's all!
