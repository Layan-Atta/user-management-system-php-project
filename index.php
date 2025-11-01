<?php
// --- الجزء الأول: الاتصال بقاعدة البيانات ---
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "task_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

// --- الجزء الثاني: معالجة إضافة مستخدم جديد ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];

    $sql_insert = "INSERT INTO users (name, age, status) VALUES ('$name', '$age', 0)";

    if ($conn->query($sql_insert) !== TRUE) {
        echo "خطأ في الإضافة: " . $conn->error;
    }
    header("Location: index.php");
    exit();
}

// --- الجزء الثالث: معالجة تبديل الحالة ---
if (isset($_GET['toggle_id'])) {
    $id = $_GET['toggle_id'];
    $new_status = $_GET['status'] == '0' ? '1' : '0';

    $sql_update = "UPDATE users SET status = $new_status WHERE id = $id";
    
    if ($conn->query($sql_update) !== TRUE) {
        echo "خطأ في التحديث: " . $conn->error;
    }
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة المستخدمين المتطور</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* خلفية متحركة */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
            pointer-events: none;
            z-index: 0;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            animation: fadeInDown 0.8s ease;
        }

        .header h1 {
            color: #fff;
            font-size: 2.8em;
            font-weight: 900;
            margin-bottom: 10px;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            letter-spacing: 1px;
        }

        .header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1em;
            font-weight: 400;
        }

        /* بطاقة النموذج */
        .form-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 35px;
            margin-bottom: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 0.8s ease 0.2s both;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .form-card h2 {
            color: #667eea;
            margin-bottom: 25px;
            font-size: 1.8em;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-card h2::before {
            content: '➕';
            font-size: 1.2em;
        }

        form {
            display: grid;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        label {
            color: #4a5568;
            font-weight: 600;
            font-size: 0.95em;
        }

        input[type="text"],
        input[type="number"] {
            padding: 14px 18px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            font-family: 'Cairo', sans-serif;
            transition: all 0.3s ease;
            background: #fff;
        }

        input[type="text"]:focus,
        input[type="number"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        input[type="submit"] {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 12px;
            font-size: 1.1em;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            font-family: 'Cairo', sans-serif;
        }

        input[type="submit"]:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.5);
        }

        input[type="submit"]:active {
            transform: translateY(-1px);
        }

        /* بطاقة الجدول */
        .table-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 0.8s ease 0.4s both;
            border: 1px solid rgba(255, 255, 255, 0.3);
            overflow: hidden;
        }

        .table-card h2 {
            color: #667eea;
            margin-bottom: 25px;
            font-size: 1.8em;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-card h2::before {
            content: '👥';
            font-size: 1.2em;
        }

        .table-wrapper {
            overflow-x: auto;
            border-radius: 12px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        th {
            padding: 18px;
            text-align: center;
            color: #fff;
            font-weight: 700;
            font-size: 1em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }

        thead tr th:first-child {
            border-top-right-radius: 12px;
        }

        thead tr th:last-child {
            border-top-left-radius: 12px;
        }

        tbody tr {
            transition: all 0.3s ease;
            animation: fadeIn 0.5s ease;
        }

        tbody tr:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.2);
            z-index: 10;
        }

        td {
            padding: 18px;
            text-align: center;
            font-size: 1em;
            color: #2d3748;
            border-bottom: 1px solid #e2e8f0;
            background: #fff;
        }

        .status-0 {
            background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%) !important;
        }

        .status-1 {
            background: linear-gradient(135deg, #f0fff4 0%, #c6f6d5 100%) !important;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9em;
        }

        .status-0 .status-badge {
            background: #fc8181;
            color: #fff;
        }

        .status-1 .status-badge {
            background: #48bb78;
            color: #fff;
        }

        .btn-toggle {
            display: inline-block;
            padding: 10px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-toggle:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-toggle:active {
            transform: translateY(-1px) scale(1.02);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #718096;
        }

        .empty-state::before {
            content: '📝';
            display: block;
            font-size: 4em;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        /* الأنيميشن */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* تصميم متجاوب */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 2em;
            }
            
            .form-card, .table-card {
                padding: 25px;
            }

            th, td {
                padding: 12px 8px;
                font-size: 0.9em;
            }

            .btn-toggle {
                padding: 8px 16px;
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>نظام إدارة المستخدمين المتطور</h1>
            <p>إدارة احترافية وسهلة للمستخدمين والحالات</p>
        </div>

        <div class="form-card">
            <h2>إضافة مستخدم جديد</h2>
            <form action="index.php" method="POST">
                <div class="form-group">
                    <label for="name">الاسم</label>
                    <input type="text" id="name" name="name" placeholder="أدخل اسم المستخدم" required>
                </div>
                
                <div class="form-group">
                    <label for="age">العمر</label>
                    <input type="number" id="age" name="age" placeholder="أدخل العمر" required min="1" max="150">
                </div>
                
                <input type="submit" name="add_user" value="➕ إضافة مستخدم">
            </form>
        </div>

        <div class="table-card">
            <h2>قائمة المستخدمين</h2>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>الرقم</th>
                            <th>الاسم</th>
                            <th>العمر</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_select = "SELECT id, name, age, status FROM users";
                        $result = $conn->query($sql_select);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $status_class = $row["status"] == 0 ? 'status-0' : 'status-1';
                                $status_text = $row["status"] == 0 ? 'غير نشط' : 'نشط';
                                
                                echo "<tr class='" . $status_class . "'>";
                                echo "<td><strong>#" . $row["id"] . "</strong></td>";
                                echo "<td><strong>" . htmlspecialchars($row["name"]) . "</strong></td>";
                                echo "<td>" . $row["age"] . " سنة</td>";
                                echo "<td><span class='status-badge'>" . $status_text . "</span></td>";
                                echo "<td><a href='index.php?toggle_id=" . $row["id"] . "&status=" . $row["status"] . "' class='btn-toggle'>🔄 تبديل</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='empty-state'>لا توجد بيانات لعرضها<br><small>ابدأ بإضافة مستخدم جديد</small></td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>