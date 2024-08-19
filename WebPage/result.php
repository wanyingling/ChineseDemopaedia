<!DOCTYPE html>
<html lang="zh-HK">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>檢索結果</title>
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
        h1, h2, h3 {
            color: #0056b3;
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
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd;
        }
        th {
            font-size: 18px;
            background-color: #eff3f5;
            padding: 8px;
        }
        td {
            padding: 8px;
        }
        tr:nth-child(odd) {
            background-color: #f7f7f7;
        }
        tr:nth-child(even) {
            background-color: #ffffff;
        }
        .hl {
            background-color: #d3d3d3;
            color: inherit;
        }
        .footer {
            font-size: 0.9em;
            color: #555;
            margin-top: 2em;
            text-align: center;
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
        <h1>人口學詞典檢索系統</h1>
        <?php
            header("Content-Type:text/html;charset=utf-8;");
            $searchword = $_GET["searchword"];

            echo "<h3>您檢索的詞是 $searchword </h3>";

            $conn = mysqli_connect("127.0.0.1","root","");
            $condb = mysqli_select_db($conn, "cndemopaedia") or die("無法連接服務器");
            mysqli_query($conn, "set names'utf8'");

            $sql = "SELECT * FROM `text` WHERE def_cn_trad LIKE '%$searchword%' OR def_cn_simp LIKE '%$searchword%' OR def_en LIKE '%$searchword%'";

            $query = mysqli_query($conn, $sql);

            echo "<table border='1'><thead>";
            echo "<tr><th>索引</th><th>繁體中文</th><th>簡體中文</th><th>English</th></tr>";

            $num = 0;
            while($row = mysqli_fetch_array($query)){
                $col2 = $row["index_para"];
                $col3 = $row["def_cn_trad"];
                $col4 = $row["def_cn_simp"];
                $col5 = $row["def_en"];

                $col3 = str_replace($searchword, "<span class='hl'>$searchword</span>", $col3);
                $col4 = str_replace($searchword, "<span class='hl'>$searchword</span>", $col4);
                $col5 = str_replace($searchword, "<span class='hl'>$searchword</span>", $col5);

                echo "<tr><td>$col2</td><td>$col3</td><td>$col4</td><td>$col5</td></tr>";
                $num += 1;
            }

            echo "</table>";

            echo "<h3>共檢索到 $num 條結果</h3>";
        ?>
          <div class="button-container">
            <a href="home.html" class="home-button">回到主頁</a>
        </div>
        <div class="footer">
            <p>
                <a href="intro.html">項目簡介</a> | 
                <a href="http://www.demopaedia.org/" target="_blank">Demopædia</a> | 
                <a href="https://wanyingling.github.io/" target="_blank">聯絡站長</a>
            </p>
            <p>© 2024  ChineseDemopædia <a href="https://creativecommons.org/licenses/by-sa/3.0/" target="_blank">CC-BY-SA 3.0</a></p>
        </div>
    </div>
</body>
</html>