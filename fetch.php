<?php
require_once 'functions.php';

// Only use via htmx include
$_SERVER['HTTP_HX_REQUEST'] ?? exit();

if (isset($_POST['tag'])) {
    $tag = secureText($_POST['tag']);
} elseif (isset($_GET['tag'])) {
    $tag = secureText($_GET['tag']);
} else {
    exit();
}


ini_set('user_agent', 'Mozilla/5.0 (X11; Linux x86_64; rv:140.0) Gecko/20100101 Firefox/140.0');

$data = getJson('https://api.github.com/repos/mozilla-firefox/firefox/commits/' . $tag);

if (empty($data)) {
    echo '<article id="info-detail" class="">';
    echo '<i>This is not a known Firefox tag</i>';
    echo '</article>';
    return;
}

$git_sha = $data['sha'];
$message = $data['commit']['message'];
$hg_sha = getJson('https://lando.moz.tools/api/git2hg/firefox/' . $git_sha)['hg_hash'];
$commit = explode("\n", $message)['0'];
$commit = linkify($commit);
$commit_type = is_sha1($tag) ? 'Commit' : 'Tag';

?>
<script>
function copyToClipBoard(targetId, buttonElement) {
    const element = document.getElementById(targetId);
    if (!element) {
        console.error("Element not found.");
        return;
    }

    const text = element.innerText || element.textContent;
    navigator.clipboard.writeText(text).then(() => {
        const originalText = buttonElement.innerText;
        const originalBackground = buttonElement.style.backgroundColor;

        buttonElement.innerText = "Copied!";
        buttonElement.style.backgroundColor = "green";

        setTimeout(() => {
            buttonElement.innerText = originalText;
            buttonElement.style.backgroundColor = originalBackground;
        }, 1000);
    }).catch((err) => {
        console.error("Failed to copy: ", err);
    });
}

</script>
<article id="info-detail">
    <table>
        <tbody>
            <tr>
                <th scope="row"><?=$commit_type?></th>
                <td><span id="fx_tag"><?=$tag?></span></td>
                <td>
                    <button id="btn_fx_tag" onclick="copyToClipBoard('fx_tag', this)" class="smallbutton">Copy</button>
                </td>
            </tr>
            <tr>
                <th scope="row">GitHub</th>
                <td>
                    <a href="https://github.com/mozilla-firefox/firefox/commit/<?=$git_sha?>"><span id="git_sha"><?=$git_sha?></span></a>
                </td>
                <td>
                    <button onclick="copyToClipBoard('git_sha', this)" class="smallbutton">Copy</button>
                </td>
            </tr>
            <tr>
                <th scope="row">HG</th>
                <td><a href="https://hg-edge.mozilla.org/mozilla-unified/rev/<?=$hg_sha?>"><span id="hg_sha"><?=$hg_sha?></span></a>
            </td>
            <td>
                <button onclick="copyToClipBoard('hg_sha', this)" class="smallbutton">Copy</button>
            </td>
            </tr>
            <tr>
                <th scope="row">Commit</th>
                <td colspan="2"><?=$commit?></td>
            </tr>
        </tbody>
    </table>
</article>
