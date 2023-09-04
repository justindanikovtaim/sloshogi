<?php

require_once '../config.php';
require_once ABSPATH . 'routes.php';

load_route($_SERVER['REQUEST_URI']);
