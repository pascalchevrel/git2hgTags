<?php
require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Get an hg sha1 for a tag</title>
    <script src="https://cdn.jsdelivr.net/npm/htmx.org@2.0.5/dist/htmx.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <style>
        #info-detail {
            margin-top: 2em;
        }
        article {
            padding: 0.5em;
        }
        th {
            font-weight: 400;
        }
        td:has(.smallbutton) {
            text-align: right;
        }
        .smallbutton {
            padding: 0.3em 0.5em;
        }
    </style>

</head>

<body>
    <main class="container">
        <div>
            <form action="/fetch.php" method="post">
                <label for="name">Enter Firefox tag:</label>

                <input
                  type="text"
                  id="tag"
                  name="tag"
                  required
                  minlength="4"
                  maxlength="50"
                  size="10"
                  value="<?php echo isset($_GET['tag']) ? secureText($_GET['tag']) : ''; ?>"/>
                    <!-- have a button POST a click via AJAX -->
              <button
                hx-post="fetch.php"
                hx-target="#info-detail"
                hx-swap="innerHTML">
                Click Me
              </button>
              </form>
        </div>
        <article id="info-detail">
        <?php
            if (isset($_GET['tag'])) {
                $_SERVER['HTTP_HX_REQUEST'] = 'true';
                $_POST['tag'] = $_GET['tag'];
                include 'fetch.php';
            } else {
                echo 'Result';
            }
        ?>
       </article>
    </main>



</body>

</html>