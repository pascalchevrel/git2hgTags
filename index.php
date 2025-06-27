<?php
require_once 'functions.php';
$url = parse_url('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Get an hg sha1 for a tag</title>
    <link
        rel="search"
        type="application/opensearchdescription+xml"
        href="<?=$url?>opensearch.xml"
        title="Search Firefox tag/commit"
    />
    <script src="https://cdn.jsdelivr.net/npm/htmx.org@2.0.5/dist/htmx.min.js" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css" defer>
    <style>
        #info-detail {
            margin-top: 2em;
        }
        .hidden  {
            visibility: hidden;
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
    <script>
        function enableCopy(selector = "pre", childSelector = "code", btnText = "Copy Me", btnTextSuccess = "Copied", activeClass = "--copy") {
            document.querySelectorAll(`${selector}:not(.${activeClass})`).forEach(node => { // create a "copy" button
         let copyBtn = document.createElement("button");
         copyBtn.innerText = btnText;
         // activeClass acts as flag so we don't add another copy button by  mistake
          copyBtn.classList.add(activeClass);
          node.appendChild(copyBtn);  copyBtn.addEventListener("click", async () => {    // copy to clipboard
            if (navigator.clipboard) {
               let text = node.querySelector(childSelector).innerText;
               await navigator.clipboard.writeText();
            }    // change text of button after copying
            copyBtn.innerText = btnTextSuccess;    // change text back to normal after ### ms
            setTimeout(() => icon.innerText = btnText, 2000);
          })
         })
        }
    </script>
</head>
<body>
    <main class="container">
        <div>
            <form action="/fetch.php" method="post">
                <label for="name">Enter Firefox tag:</label>

                <input
                  type="search"
                  id="tag"
                  name="tag"
                  required
                  minlength="4"
                  maxlength="50"
                  size="10"
                  value="<?php echo isset($_GET['tag']) ? secureText($_GET['tag']) : ''; ?>"
                  placeholder="Search a Firefox tag…" />
                    <!-- have a button POST a click via AJAX -->
                <button
                    hx-post="fetch.php"
                    hx-target="#target"
                    hx-swap="innerHTML">
                    Search
                </button>
            </form>
        </div>
        <div id="target">
        <?php
            if (isset($_GET['tag'])) {
                $_SERVER['HTTP_HX_REQUEST'] = 'true';
                include 'fetch.php';
            }
        ?>
        </div>
    </main>
</body>
</html>