<?php

use Bleicker\Cms\Controller\NodeController;
use Bleicker\Registry\Registry;

Registry::set('bleicker.cms.modules.nodeManager.controller', NodeController::class);
Registry::set('bleicker.cms.modules.nodeManager.endpoints.index', '/manager');
Registry::set('bleicker.cms.modules.nodeManager.endpoints.show', '/manager/{node}');