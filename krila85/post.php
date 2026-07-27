<?php
declare(strict_types=1);

/**
 * Create a new thread – PHP 8.5+ compatible
 */

$file_name = '';
$errors = [];

if (isset($_FILES['image']) && is_array($_FILES['image'])) {
    $file = $_FILES['image'];
    $file_name_raw = $file['name'] ?? '';
    $file_tmp      = $file['tmp_name'] ?? '';
    $file_error    = $file['error'] ?? UPLOAD_ERR_NO_FILE;

    if ($file_error === UPLOAD_ERR_OK && is_uploaded_file($file_tmp)) {
        $file_ext = strtolower(pathinfo($file_name_raw, PATHINFO_EXTENSION));
        $allowed  = ['jpeg', 'jpg', 'png', 'gif'];

        if (!in_array($file_ext, $allowed, true)) {
            $errors[] = 'extension not allowed, please choose a picture file.';
        } else {
            // Keep original basename for compatibility with original behaviour
            $file_name = basename($file_name_raw);
            $dest = 'cdn/' . $file_name;
            if (!move_uploaded_file($file_tmp, $dest)) {
                $errors[] = 'failed to move uploaded file.';
                $file_name = '';
            } else {
                echo 'Success';
            }
        }
    } elseif ($file_error !== UPLOAD_ERR_NO_FILE) {
        $errors[] = 'upload error code: ' . $file_error;
    }
} else {
    // Original behaviour: still allow posting without an image
    // (the "kys off fam" message is preserved for parity)
    echo 'kys off fam';
}

if (!empty($errors)) {
    print_r($errors);
}

// Name / tripcode
$name = 'Anonymous';
if (isset($_POST['name']) && is_string($_POST['name']) && $_POST['name'] !== '') {
    $name = strip_tags($_POST['name']);
    $arr = explode('#', $name, 2);
    if (count($arr) > 1) {
        // Nested crypt kept for original tripcode compatibility.
        // crypt() is still present in PHP 8.5; salt is always supplied.
        $tripcode = crypt(
            crypt($arr[1], crypt('your', 'own')),
            crypt(phpversion(), 'hash')
        );
        $name = $arr[0] . '!' . $tripcode;
    }
}

$title = strip_tags($_POST['title'] ?? '');
$body  = strip_tags($_POST['body'] ?? '');
$date  = date('m/d/y (D) H:i:s');

$metainfo = '[name="' . $name . '", date="' . $date . '", title="' . $title . '", include="' . $file_name . '"]';

// Determine next thread id safely
$threads = [];
if (is_dir('thread/')) {
    $raw = scandir('thread/', SCANDIR_SORT_DESCENDING) ?: [];
    $threads = array_values(array_filter($raw, static fn(string $f): bool =>
        is_file('thread/' . $f) && str_ends_with($f, '.txt')
    ));
    natsort($threads);
    $threads = array_reverse($threads, false);
}

$latestthread = 0;
if (!empty($threads)) {
    $latestthread = (int) str_replace('.txt', '', $threads[0]);
}
$latestthread += 1;

$filepath = 'thread/' . $latestthread . '.txt';
$newthread_file = fopen($filepath, 'w+');
if ($newthread_file === false) {
    http_response_code(500);
    exit('Could not create thread file');
}

fwrite($newthread_file, $metainfo . "\n");
fwrite($newthread_file, '#' . $body . "\n");
fclose($newthread_file);

header('Location: thread.php?=' . $latestthread);
exit;
