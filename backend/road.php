 <?php
        include "database.php";
        $user_id = $_SESSION['user_id'];
        $result = $conn->query("SELECT * FROM tasks WHERE user_id='$user_id'");

        while($row = $result->fetch_assoc()) {
            echo "<div class='task'>
                    ".$row['title']."
                    <a href='delete.php?id=".$row['id']."'>❌</a>
                  </div>";
        }
        ?>