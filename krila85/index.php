<?php
declare(strict_types=1);

/**
 * /krila/ index – compatible with PHP 8.5+
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Krila</title>
  <link rel="stylesheet" type="text/css" href="static/krila.css">
</head>
<body>
  <div id="boardsection"></div>
  <a href="javascript:location.reload();"><img src="static/logo.png" id="logo" alt="Krila"></a><br>
  <div id="boardname">/krila/ - Anything & Everything</div><br>
  <div id="threadcreate">
    <form enctype="multipart/form-data" action="post.php" method="post">
      <input type="text" name="name" id="name"><label id="threadlabel" for="name"> Name</label><br>
      <input type="text" name="title" id="title"><label id="threadlabel" for="title"> Subject</label><br>
      <textarea name="body" id="body"></textarea><label id="threadlabel" for="body"> Comment</label><br>
      <input type="file" name="image" accept="image/jpeg,image/png,image/gif">
      <input type="submit" name="submit" value="Post" style="float: right; margin-right: 50px;"><br><br>
    </form>
  </div>
  <hr>
  <?php
  $path = 'thread/';
  if (is_dir($path)) {
      $threads = scandir($path, SCANDIR_SORT_DESCENDING) ?: [];
      // Filter only .txt files and natural-sort numerically
      $threads = array_values(array_filter($threads, static fn(string $f): bool =>
          is_file($path . $f) && str_ends_with($f, '.txt')
      ));
      natsort($threads);
      $threads = array_reverse($threads, false);

      $shown = 0;
      foreach ($threads as $threadFile) {
          if ($shown >= 10) {
              break;
          }
          $full = $path . $threadFile;
          $f = @fopen($full, 'r');
          if ($f === false) {
              continue;
          }
          $metainformation = fgets($f) ?: '';
          $body = fgets($f) ?: '';
          fclose($f);

          $metainformation = str_replace(['[', ']'], '', $metainformation);
          $meta = explode(', ', $metainformation);

          $name    = trim(preg_replace('/\s\s+/', ' ', str_replace(['"', 'name="'], '', $meta[0] ?? '')));
          $date    = trim(preg_replace('/\s\s+/', ' ', str_replace(['"', 'date="'], '', $meta[1] ?? '')));
          $title   = trim(preg_replace('/\s\s+/', ' ', str_replace(['"', 'title="'], '', $meta[2] ?? '')));
          $include = trim(preg_replace('/\s\s+/', ' ', str_replace(['"', 'include="'], '', $meta[3] ?? '')));

          $threadId = str_replace('.txt', '', $threadFile);

          echo '<div id="main_postcontainer">';
          if ($include !== '') {
              echo '<span id="metadata">File: <a href="cdn/' . htmlspecialchars($include, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($include, ENT_QUOTES, 'UTF-8') . '</a></span><br>';
              echo '<a href="cdn/' . htmlspecialchars($include, ENT_QUOTES, 'UTF-8') . '"><img id="thumb" style="vertical-align: top;" src="cdn/' . htmlspecialchars($include, ENT_QUOTES, 'UTF-8') . '" alt=""></a>';
          }
          echo '<span id="posttitle">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '    </span>';
          echo '<span id="name">' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '    </span>';
          echo '<span id="date">' . htmlspecialchars($date, ENT_QUOTES, 'UTF-8') . '   </span>';
          echo '<span id="postno">No. ' . htmlspecialchars($threadId, ENT_QUOTES, 'UTF-8') . '</span><br><br>';
          echo '<span id="body">' . htmlspecialchars(str_replace('#', '', $body), ENT_QUOTES, 'UTF-8') . '</span><br>';
          echo '<a id="body" href="thread.php?=' . htmlspecialchars($threadId, ENT_QUOTES, 'UTF-8') . '">Show more</a><br><br><br><br><br><br><br><br><br>';
          echo '</div><hr>';
          $shown++;
      }
  }
  ?>
</body>
</html>
