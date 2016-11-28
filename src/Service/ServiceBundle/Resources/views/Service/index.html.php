<?php header('Content-Type: application/json');
/** @var mixed $result */
if(json_encode(array($result)) === false) echo json_encode(array(array('result' => 'error', 'message' => 'Encoding problem', 'code' => -1)));
else echo json_encode(array($result));?>