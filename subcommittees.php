<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Subcommittees</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,100..700;1,100..700&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body class="background">
    <div>
        
    <div class="button-container">
        <a href="index.php">Go back home</a>
    </div>
        <h1>Organizing Sub-Committees</h1>
        <p>Select a sub-committee to view its members:</p>

        <form action="subcommittees.php" method="post">
            <label for="subcommittee">Sub-Committee:</label><br>
            <select name="subcommittee" id="subcommittee" required>
                <option value="">-- Select --</option>
                <?php
                include 'config.php';

                try {
                    $stmt = $pdo->query("SELECT name FROM subcommittee ORDER BY name");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $selected = (isset($_POST['subcommittee']) && $_POST['subcommittee'] == $row['name']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($row['name']) . '" ' . $selected . '>' . htmlspecialchars($row['name']) . '</option>';
                    }
                } catch (PDOException $e) {
                    echo '<option disabled>Error loading subcommittees</option>';
                }
                ?>
            </select><br><br>
            <input type="submit" value="View Members" class="home-button">
        </form>

        <?php
        if (!empty($_POST['subcommittee'])) {
            $subcommittee = $_POST['subcommittee'];
            echo "<h2>Members of " . htmlspecialchars($subcommittee) . "</h2>";

            try {
                $sql = "
                    SELECT m.name AS member_name
                    FROM member m
                    JOIN memberOf mo ON m.id = mo.memberID
                    WHERE mo.subcommitteeName = :subcommittee
                ";
            
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['subcommittee' => $subcommittee]);
                $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
                if ($members) {
                    echo "<table><tr><th>Member Name</th></tr>";
                    foreach ($members as $m) {
                        echo "<tr><td>" . htmlspecialchars($m['member_name']) . "</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No members found for this sub-committee.</p>";
                }
            } catch (PDOException $e) {
                echo "<p>Error fetching members: " . $e->getMessage() . "</p>";
            }

            $pdo = null;
        }
        ?>
    </div>
</body>

</html>
