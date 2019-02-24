<?php
    include "db.php";
    $version = "0.0.3-ALPHA";

    $errPair = testDb();
    $errDesc = htmlspecialchars($errPair->err . ': ' . $errPair->errDesc);
    $dbOk = $errPair->isOk();

    $categories = new ArrayObject();
    if ($dbOk) {
        $catErrPair = fetchCategories();
        if (!$catErrPair->isOk()) {
            $dbOk = false;
        } else {
            $categories = $catErrPair->res;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styling/index.css" />
    <meta charset="UTF-8">
    <title>CalThing</title>
</head>
<body>
    <nav>
        <h4>CalThing v<?php echo $version ?></h4>
        <h5>
            <?php echo $dbOk
                ?'<span style="color: green;">✓</span> DB Check Successful'
                :"<a href='#' style='color: #e52' onclick='alert(`$errDesc`)'>DB Check Failed</a>" ?>
        </h5>
    </nav>
    <div class="flex-row-container flex-center">
        <div class="container">
            <?php
            // Stop reading here lest your mind descend into the depths of infinite madness.
            // But if you don't: nobody else needs to know of this. Ever.

            if ($dbOk) {
                $err = "";
                $postErrPair = fetchPost(1);

                if ($postErrPair->isOk()) {
                    $post = $postErrPair->res;

                    echo "
                    <table>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Location</th>
                            <th>Time</th>
                            <th>Category</th>
                        </tr>
                    ";
                    foreach ($post->events as $e) {
                        $cat = $e->getCategory($categories)->name;
                        echo "
                        <tr>
                            <td>$e->name</td>
                            <td>$e->description</td>
                            <td>$e->location</td>
                            <td>$e->time</td>
                            <td>$cat</td>
                        </tr>
                        ";
                    }
                    echo "</table>";
                } else {
                    $err = "An internal error occurred while fetching this week's post. Please try again later.";
                }
            } else {
                $err = "A fatal error occurred while testing the database. Please try again later.";
            }

            if (!empty($err)) {
                echo '
                <h3>Oops! <a href="#" onclick="alert($err)">Something went wrong. Try again later!</a></h3>
                <pre>
                  .-~*~--,.   .-.
          .-~-. ./OOOOOOOOO\.\'OOO`9~~-.
        .`OOOOOO.OOO.OOO@@@@@OOO@@OOOOOO\
       /OOOO@@@OO@@@OO@@@OOO@@@@@@@@OOOO`.
       |OO@@@WWWW@@@@OOWWW@WWWW@@@@@@@OOOO).
     .-\'OO@@@@WW@@@W@WWWWWWWWOOWW@@@@@OOOOOO}
    /OOO@@O@@@@W@@@@@OOWWWWWOOWOO@@@OOO@@@OO|
   lOOO@@@OO@@@WWWWWWW\OWWWO\WWWOOOOOO@@@O.\'
    \OOO@@@OOO@@@@@@OOW\     \WWWW@@@@@@@O\'.
     `,OO@@@OOOOOOOOOOWW\     \WWWW@@@@@@OOO)
      \,O@@@@@OOOOOOWWWWW\     \WW@@@@@OOOO.\'
        `~c~8~@@@@WWW@@W\       \WOO|\UO-~\'
             (OWWWWWW@/\W\    ___\WO)
               `~-~\'\'     \   \WW=*\'
                         __\   \
                         \      \
                          \    __\
                           \  \
                            \ \
                             \ \
                              \\
                               \\
                                \
                                 \
                </pre>
                ';
            }
            ?>
        </div>
    </div>
</body>
</html>
