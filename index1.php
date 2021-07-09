<?php

require_once "pdo.php";

if (isset($_POST['submit'])) {

    try {

        $image1 = file_get_contents(addslashes($_FILES['f1']['tmp_name']));
        $check = getimagesize($_FILES["f1"]["tmp_name"]);

        if (!is_numeric($_POST['rank'])) {
            $failure = "Rank is required";
        } elseif (strlen($_POST['name']) < 2) {
            $failure = "Name is required.";
        } elseif (!is_numeric($_POST['classical']) || !is_numeric($_POST['rapid']) || !is_numeric($_POST['blitz'])) {
            $failure = "Classical, rapid and blitz Ratings should be numeric.";
        } elseif ($check == false) {
            $failure = "File is not an image.";
        } else {
            $sql = "INSERT INTO rank (rank, name, classical, rapid, blitz, image1)
          VALUES (:rank, :name, :classical, :rapid, :blitz, :image1)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':rank' => $_POST['rank'],
                ':name' => $_POST['name'],
                ':classical' => $_POST['classical'],
                ':rapid' => $_POST['rapid'],
                ':blitz' => $_POST['blitz'],
                ':image1' => $image1
            ));

            header('Location: index.php');
            return;
            $comment = "Successfully updated.";
        }
    } catch (\PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            $failure = "Rank already present.";
        }
    }
}


?>


<!DOCTYPE html>
<html>

<head>
    <title></title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div>
        <form name="form2" action="" method="POST" enctype="multipart/form-data">
            <table class="table  table-striped text-center">
                <thead>
                    <tr>
                        <td colspan="7">
                            <h2 class="text-center text m-0" style="color:blue; text-align: center;">Chess Ratings&nbsp;<i style="color:black"></i>
                            </h2>
                        </td>
                    </tr>
                    <tr>
                        <th>Rank</th>
                        <th>Name</th>
                        <th>Classical</th>
                        <th>Rapid</th>
                        <th>Blitz</th>
                        <th>Image</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>



                    <?php
                    require 'Dbconnect.php';
                    $stmt = $conn->prepare("SELECT * FROM rank");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) :
                    ?>
                        <tr>
                            <td><?= $row['Rank'] ?></td>
                            <td><?= $row['Name'] ?></td>
                            <td><?= $row['Classical'] ?></td>
                            <td><?= $row['Rapid'] ?></td>
                            <td><?= $row['Blitz'] ?></td>

                            <td>
                                <button type="submit" name="show" value="show">Show</i></button>
                            </td>

                            <td>
                                <?php
                                if (isset($_POST['show'])) {

                                    echo '<img src="data:image/jpeg;base64, ' . base64_encode($row['image1']) . '" alt="Image" style="width: 100px; height: 100px;" >';
                                }

                                ?>

                            </td>


                        </tr>
                    <?php endwhile; ?>
                </tbody>





            </table>
            <!-- <?php
                    if (isset($_POST['show'])) {

                        $stmt = $conn->prepare("SELECT * FROM rank");
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();

                        echo '<img src="data:image/jpeg;base64, ' . base64_encode($row['image1']) . '" alt="Image" style="width: 100px; height: 100px;" >';
                    }

                    ?>  
  -->


            <br>
            <br>
            <br>
            <br>

            <?php

            if ($failure !== false) {
                echo ('<p style="color: red; text-align:centre;">' . htmlentities($failure) . "</p>\n");
            }

            if ($comment !== false) {
                echo ('<p style="color: green; text-align:centre;">' . htmlentities($comment) . "</p>\n");
            }

            ?>

            <h2 style="color: blue;">Update Ratings</h2>

            <form name="form1" action="" method="POST" enctype="multipart/form-data">
                <table>
                    <tr>
                        <td class="text-center">Rank</td>
                        <td><input type="number" name="rank"></td>
                    </tr>
                    <tr>
                        <td class="text-center">Name</td>
                        <td><input type="text" name="name"></td>
                    </tr>
                    <tr>
                        <td class="text-center">Classical</td>
                        <td><input type="number" name="classical"></td>
                    </tr>
                    <tr>
                        <td class="text-center">Rapid</td>
                        <td><input type="number" name="rapid"></td>
                    </tr>
                    <tr>
                        <td class="text-center">Blitz</td>
                        <td><input type="number" name="blitz"></td>
                    </tr>
                    <tr>
                        <td class="text-center">Select Image file</td>
                        <td><input type="file" name="f1"></td>
                    </tr>
                    <tr>
                        <br>
                    <tr>
                    </tr>
                    <td class="text-center">Click to Upload </td>
                    <td> <input type="submit" name="submit" value="upload"> </td>
                    </tr>
                </table>
            </form>
    </div>

</body>

</html>