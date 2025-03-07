document.addEventListener('DOMContentLoaded', function() {
    // 加载基础统计数据
    loadBasicStats();
    // 加载最近活动
    loadRecentActivities();
    // 加载系统健康状态
    loadSystemHealth();
    
    // 每30秒刷新一次数据
    setInterval(loadBasicStats, 30000);
    setInterval(loadSystemHealth, 30000);

    function loadBasicStats() {
        fetch('../api/dashboard_stats.php')
            .then(response => response.json())
            .then(data => {
                updateBasicStats(data);
            })
            .catch(handleError);
    }

    function loadRecentActivities() {
        fetch('../api/recent_activities.php')
            .then(response => response.json())
            .then(data => {
                const activitiesList = document.getElementById('recentActivities');
                activitiesList.innerHTML = data.activities
                    .map(activity => `
                        <div class="activity-item">
                            <div>${activity.description}</div>
                            <div class="activity-time">${activity.time}</div>
                        </div>
                    `).join('');
            })
            .catch(handleError);
    }

    function loadSystemHealth() {
        fetch('../api/system_health.php')
            .then(response => response.json())
            .then(data => {
                updateSystemHealth(data);
            })
            .catch(handleError);
    }

    function updateBasicStats(data) {
        document.getElementById('totalRecords').textContent = data.records || '0';
        document.getElementById('totalUsers').textContent = data.users || '0';
        document.getElementById('lastBackup').textContent = data.lastBackup || 'Never';
        
        const statusDot = document.querySelector('.status-dot');
        statusDot.className = `status-dot ${data.systemActive ? 'active' : 'inactive'}`;
    }

    function updateSystemHealth(data) {
        // 更新数据库空间使用
        const dbSpaceBar = document.getElementById('dbSpace');
        dbSpaceBar.style.width = `${data.dbSpaceUsage}%`;
        
        // 更新备份状态
        const backupStatus = document.getElementById('backupStatus');
        backupStatus.className = `backup-status ${data.backupStatus}`;
        backupStatus.textContent = data.backupMessage;
    }

    function handleError(error) {
        console.error('Error fetching data:', error);
        // 显示错误信息给用户
    }
});
