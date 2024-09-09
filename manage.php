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

// 進捗状況の更新
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contract_id = $_POST['contract_id'];
    $document_preparation = $_POST['document_preparation'];
    $application_status = $_POST['application_status'];
    $seal_status = $_POST['seal_status'];

    // 既存データがあるか確認
    $check_sql = "SELECT * FROM contract_status WHERE contract_id = $contract_id";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        // データが存在する場合、更新
        $sql = "UPDATE contract_status SET 
                document_preparation='$document_preparation',
                application_status='$application_status',
                seal_status='$seal_status',
                updated_at=NOW()
                WHERE contract_id=$contract_id";
    } else {
        // データが存在しない場合、新規挿入
        $sql = "INSERT INTO contract_status (contract_id, document_preparation, application_status, seal_status, created_at, updated_at) 
                VALUES ('$contract_id', '$document_preparation', '$application_status', '$seal_status', NOW(), NOW())";
    }

    if ($conn->query($sql) === TRUE) {
        echo "進捗状況が更新されました";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// 契約情報の取得
$sql = "SELECT contracts.id, contracts.company_name, contracts.contract_title, 
               IFNULL(contract_status.document_preparation, '') AS document_preparation, 
               IFNULL(contract_status.application_status, '') AS application_status, 
               IFNULL(contract_status.seal_status, '') AS seal_status 
        FROM contracts 
        LEFT JOIN contract_status ON contracts.id = contract_status.contract_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>契約実施管理</title>
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
        <h2>契約実施管理</h2>
        <table>
            <tr>
                <th>会社名</th>
                <th>契約件名</th>
                <th>書類準備状況</th>
                <th>申請状況</th>
                <th>押印状況</th>
                <th>操作</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<form method='post' action='manage.php'>";
                    echo "<td>" . $row['company_name'] . "</td>";
                    echo "<td>" . $row['contract_title'] . "</td>";
                    echo "<td><input type='text' name='document_preparation' value='" . $row['document_preparation'] . "'></td>";
                    echo "<td><input type='text' name='application_status' value='" . $row['application_status'] . "'></td>";
                    echo "<td><input type='text' name='seal_status' value='" . $row['seal_status'] . "'></td>";
                    echo "<td>";
                    echo "<input type='hidden' name='contract_id' value='" . $row['id'] . "'>";
                    echo "<input type='submit' value='更新'>";
                    echo "</td>";
                    echo "</form>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>契約がありません</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
