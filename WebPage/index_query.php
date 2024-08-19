<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "cndemopaedia");
mysqli_set_charset($conn, "utf8mb4");

if (!$conn) {
    die("数据库连接失败: " . mysqli_connect_error());
}

$selectedChapter = $_GET['chapter'] ?? '';

$query = "SELECT concept, index_con FROM def_index WHERE section = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $selectedChapter);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$concepts = [];
while ($row = mysqli_fetch_assoc($result)) {
    $concepts[] = $row;
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
<html lang="zh-HK">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>人口學中文詞典檢索系統</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h1, h2 {
            color: #0056b3;
            text-align: center;
        }
        h1 {
            border-bottom: 2px solid #0056b3;
            padding-bottom: 10px;
        }
        p {
            margin-bottom: 1em;
        }
        a {
            color: #0056b3;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .section {
            margin-bottom: 2em;
        }
        #input1 {
            width: 300px;
            height: 30px;
            border-radius: 10px;
            border: 1px solid #ccc;
            padding: 5px;
            font-size: 16px;
            outline: none;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        #but1 {
            width: 80px;
            height: 42px;
            border-radius: 10px;
            border: 1px solid #ccc;
            padding: 5px;
            font-size: 18px;
            outline: none;
            margin-top: 10px;
            margin-bottom: 10px;
            cursor: pointer;
        }
        .footer {
            font-size: 0.9em;
            color: #555;
            margin-top: 2em;
            text-align: center;
        }
        .author { 
            font-size: 0.9em; 
            color: #555; 
            margin-top: 10px;
        } 
        .logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-bottom: 2px;
        } 
        .logo img {
            max-height: 180px;
            margin-bottom: 5px;
        } 
        .author {
            font-size: 0.9em;
            color: #555;
            margin-top: 10px;
        }
        .chapter-link {
            display: block;
            width: fit-content;
            padding: 10px 20px;
            background-color: #e6f0ff;
            color: #0056b3;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px auto 0;
            transition: background-color 0.3s;
            text-align: center;
        }
        .chapter-link:hover {
            background-color: #c7e0ff;
        }
        table {
            margin: 0 auto;
            border-collapse: collapse;
            width: 100%;
            max-width: 600px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .home-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #0056b3;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px auto;
            transition: background-color 0.3s;
            text-align: center;
        }
        .home-button:hover {
            background-color: #003d80;
        }
        .button-container {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="imgs/logo_blue.png" alt="Logo">
            <h1>章節索引</h1>
        </div>

        <form action="index_query.php" method="GET">
            <label for="chapter-select">選擇章節:</label>
            <select name="chapter" id="chapter-select">
                <option value="">選擇章節</option>
                <option value=1 <?php if ($selectedChapter == 1) echo "selected"; ?>>章節 1 • 基本概念</option>
                <option value=2 <?php if ($selectedChapter == 2) echo "selected"; ?>>章節 2 • 人口統計資料的加工整理</option>
                <option value=3 <?php if ($selectedChapter == 3) echo "selected"; ?>>章節 3 • 人口的分佈和分類</option>
                <option value=4 <?php if ($selectedChapter == 4) echo "selected"; ?>>章節 4 • 死亡和患病</option>
                <option value=5 <?php if ($selectedChapter == 5) echo "selected"; ?>>章節 5 • 結婚</option>
                <option value=6 <?php if ($selectedChapter == 6) echo "selected"; ?>>章節 6 • 出生</option>
                <option value=7 <?php if ($selectedChapter == 7) echo "selected"; ?>>章節 7 • 人口增長和更替</option>
                <option value=8 <?php if ($selectedChapter == 8) echo "selected"; ?>>章節 8 • 空間流動</option>
            </select>
            <input type="submit" value="查詢">
        </form>

        <?php if (!empty($selectedChapter)): ?>
            <table>
                <tr>
                    <th>概念</th>
                    <th>索引</th>
                </tr>
                <?php foreach ($concepts as $concept): ?>
                    <tr>
                        <td><?php echo $concept['concept']; ?></td>
                        <td><?php echo $concept['index_con']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
        <div class="button-container">
            <a href="home.html" class="home-button">回到主頁</a>
        </div>
        <div class="footer">
            <p>
                <a href="intro.html">項目簡介</a> | 
                <a href="http://www.demopaedia.org/" target="_blank">Demopædia</a> | 
                <a href="https://wanyingling.github.io/" target="_blank">聯絡站長</a>
            </p>
            <p>© 2024 ChineseDemopædia <a href="https://creativecommons.org/licenses/by-sa/3.0/" target="_blank">CC-BY-SA 3.0</a></p>
        </div>
    </div>
</body>
</html>