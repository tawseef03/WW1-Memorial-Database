<?php
function debugSQL($mysqli, $sql, $params = []) {
    $debug_info = [];
    
    // 检查数据库连接
    $debug_info['connection'] = [
        'connected' => !$mysqli->connect_error,
        'error' => $mysqli->connect_error ?? 'None'
    ];
    
    // 检查SQL语句
    $debug_info['sql'] = [
        'query' => $sql,
        'params' => $params
    ];
    
    // 检查预处理语句
    $stmt = $mysqli->prepare($sql);
    $debug_info['prepare'] = [
        'success' => $stmt !== false,
        'error' => $mysqli->error ?? 'None'
    ];
    
    if ($stmt) {
        // 检查参数绑定
        if (!empty($params)) {
            $bind_result = $stmt->bind_param(str_repeat('s', count($params)), ...$params);
            $debug_info['bind'] = [
                'success' => $bind_result,
                'error' => $stmt->error ?? 'None'
            ];
        }
        
        $stmt->close();
    }
    
    return $debug_info;
}

// 添加到日志
function logDebugInfo($debug_info) {
    error_log("SQL Debug Info: " . print_r($debug_info, true));
}
?>
