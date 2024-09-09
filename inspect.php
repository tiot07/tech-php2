<?php
// データベース接続設定
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "contract_management_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// 接続確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 検収状況の更新
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contract_id = $_POST['contract_id'];
    $invoice_preparation = $_POST['invoice_preparation'];
    $inspection_status = $_POST['inspection_status'];

    // 既存データがあるか確認
    $check_sql = "SELECT * FROM inspection_status WHERE contract_id = $contract_id";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        // データが存在する場合、更新
        $sql = "UPDATE inspection_status SET 
                invoice_preparation='$invoice_preparation',
                inspection_status='$inspection_status',
                updated_at=NOW()
                WHERE contract_id=$contract_id";
    } else {
        // データが存在しない場合、新規挿入
        $sql = "INSERT INTO inspection_status (contract_id, invoice_preparation, inspection_status, created_at, updated_at) 
                VALUES ('$contract_id', '$invoice_preparation', '$inspection_status', NOW(), NOW())";
    }

    if ($conn->query($sql) === TRUE) {
        echo "検収状況が更新されました";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// 契約情報の取得
$sql = "SELECT contracts.id, contracts.company_name, contracts.contract_title, 
               IFNULL(inspection_status.invoice_preparation, '') AS invoice_preparation, 
               IFNULL(inspection_status.inspection_status, '') AS inspection_status 
        FROM contracts 
        LEFT JOIN inspection_status ON contracts.id = inspection_status.contract_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>検収管理</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
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
        <h2>検収管理</h2>
        <table>
            <tr>
                <th>会社名</th>
                <th>契約件名</th>
                <th>請求書類準備状況</th>
                <th>検収状況</th>
                <th>操作</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<form method='post' action='inspect.php'>";
                    echo "<td>" . $row['company_name'] . "</td>";
                    echo "<td>" . $row['contract_title'] . "</td>";
                    echo "<td><input type='text' name='invoice_preparation' value='" . $row['invoice_preparation'] . "'></td>";
                    echo "<td><input type='text' name='inspection_status' value='" . $row['inspection_status'] . "'></td>";
                    echo "<td>";
                    echo "<input type='hidden' name='contract_id' value='" . $row['id'] . "'>";
                    echo "<input type='submit' value='更新'>";
                    echo "</td>";
                    echo "</form>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>契約がありません</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
