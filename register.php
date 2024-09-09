<?php
// データベース接続設定
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "contract_management_db";

// データベース接続
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// フォームが送信された場合
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $company_name = $_POST['company_name'];
    $contract_title = $_POST['contract_title'];
    $amount = $_POST['amount'];
    $contract_period = $_POST['contract_period'];
    $initiator = $_POST['initiator'];
    $inspector = $_POST['inspector'];

    // データベースに挿入
    $sql = "INSERT INTO contracts (company_name, contract_title, amount, contract_period, initiator, inspector, created_at, updated_at) 
            VALUES ('$company_name', '$contract_title', '$amount', '$contract_period', '$initiator', '$inspector', NOW(), NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "新しい契約が登録されました";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>契約登録</title>
    <style>
        body {
            display: flex;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .sidebar {
            width: 200px;
            background-color: #f4f4f4;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            height: 100vh;
        }
        .sidebar a {
            display: block;
            margin: 10px 0;
            text-decoration: none;
            color: #333;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>メニュー</h2>
        <a href="register.php">契約登録</a>
        <a href="manage.php">契約実施管理</a>
        <a href="inspect.php">検収管理</a>
    </div>
    <div class="content">
        <h2>契約登録フォーム</h2>
        <form method="post" action="register.php">
            <label>契約会社:</label><br>
            <input type="text" name="company_name" required><br>
            <label>契約件名:</label><br>
            <input type="text" name="contract_title" required><br>
            <label>金額（概算）:</label><br>
            <input type="text" name="amount" required><br>
            <label>契約期間:</label><br>
            <input type="date" name="contract_period" required><br>
            <label>起票者:</label><br>
            <input type="text" name="initiator" required><br>
            <label>検収者:</label><br>
            <input type="text" name="inspector" required><br><br>
            <input type="submit" value="登録">
        </form>
    </div>
</body>
</html>
