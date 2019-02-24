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
    <script src="scripts/index.js"></script>
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

                    foreach ($post->getRelevantCategories($categories) as $cat) {
                        echo "<button class='collapsible'>$cat->name</button>";
                        foreach ($post->getEventsInCategory($categories, $cat) as $event) {
                            echo "
                                <button class='collapsible'>$event->name</button>
                                <div class='event'>
                                    <div class='content-header'>
                                        <h4><span class='hint'>LOCATION:&nbsp;</span>$event->location</h4>
                                        <h4 style='float: right;'><span class='hint'>TIME:&nbsp;</span>$event->time</h4>
                                    </div>
                                    <div class='content'>
                                        <p>$event->description</p>
                                    </div>
                                </div>
                            ";
                        }
                    }
                } else {
                    $err = "An internal error occurred while fetching this week's post. Please try again later.";
                }
            } else {
                $err = "A fatal error occurred while testing the database. Please try again later.";
            }

            if (!empty($err)) {
                echo <<<TAG
                <h3>Oops! <a href="#" onclick='alert("$err")'>Something went wrong. Try again later!</a></h3>
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
                TAG;
            }
            ?>
        </div>
    </div>
</body>
</html>
