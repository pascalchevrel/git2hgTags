<?php
require_once 'functions.php';

// Only use via htmx include
$_SERVER['HTTP_HX_REQUEST'] ?? exit();

ini_set('user_agent', 'Mozilla/5.0 (X11; Linux x86_64; rv:140.0) Gecko/20100101 Firefox/140.0');

$tag = secureText($_POST['tag']);
$url = 'https://api.github.com/repos/mozilla-firefox/firefox/commits/' . $tag;
$data = getJson($url);

if (empty($data)) {
    echo "<p>Tag is <b>not</b> valid</p>";
    return;
}

$git_sha = $data['sha'];
$message = $data['commit']['message'];
$hg_sha = getJson('https://lando.moz.tools/api/git2hg/firefox/' . $git_sha)['hg_hash'];
$commit = explode("\n", $message)['0'];
$commit = linkify($commit);

?>
<script>
    function copyToClipBoard(targetId) {
        const element = document.getElementById(targetId);
        if (!element) {
            alert("Element not found.");
            return;
        }

        const text = element.innerText || element.textContent;
        if (!navigator.clipboard) {
            // Fallback for very old browsers
            const textarea = document.createElement("textarea");
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand("copy");
            document.body.removeChild(textarea);
        } else {
            navigator.clipboard.writeText(text).then(
                () => alert("Copied!"),
                (err) => alert("Failed to copy: " + err)
            );
        }
    }
</script>
<table>
  <tbody>
    <tr>
      <th scope="row">GitHub</th>
      <td>
        <a href="https://github.com/mozilla-firefox/firefox/commit/<?=$git_sha?>"><span id="git_sha"><?=$git_sha?></span></a>
    </td>
    <td>
        <button onclick="copyToClipBoard('git_sha')" class="smallbutton">Copy</button>
    </td>
    </tr>
    <tr>
      <th scope="row">HG</th>
      <td><a href="https://hg-edge.mozilla.org/mozilla-unified/rev/<?=$hg_sha?>"><span id="hg_sha"><?=$hg_sha?></span></a>
    </td>
    <td>
        <button onclick="copyToClipBoard('hg_sha')" class="smallbutton">Copy</button>
    </td>
    </tr>
    <tr>
      <th scope="row">Commit</th>
      <td colspan="2"><?=$commit?></td>
    </tr>
  </tbody>
</table>
